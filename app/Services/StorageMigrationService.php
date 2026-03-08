<?php

namespace App\Services;

use App\Models\StorageConnection;
use App\Models\UserChildhoodMedia;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use App\Models\UserHeightWeight;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class StorageMigrationService
{
    public function __construct(
        private GoogleDriveService $driveService,
        private WasabiService $wasabiService
    ) {}

    public function migrate(StorageConnection $from, StorageConnection $to, bool $deleteFromSource): bool
    {
        $user = $from->user;
        if ($from->user_id !== $to->user_id) {
            return false;
        }

        $this->ensureTargetHasUpupWebsiteFolder($to);
        $this->deleteAllFromTarget($to);

        if ($from->provider === 'google_drive' && $to->provider === 'wasabi') {
            return $this->copyDriveToWasabi($user, $from, $to, $deleteFromSource);
        }
        if ($from->provider === 'wasabi' && $to->provider === 'google_drive') {
            return $this->copyWasabiToDrive($user, $from, $to, $deleteFromSource);
        }

        return false;
    }

    /**
     * Ensure target has UPUP_WEBSITE folder. For Drive: create if needed, set root_folder_id. For Wasabi: ensure prefix includes UPUP_WEBSITE.
     */
    private function ensureTargetHasUpupWebsiteFolder(StorageConnection $target): void
    {
        if ($target->provider === 'google_drive' && ! $target->root_folder_id) {
            $accessToken = $this->getDriveAccessToken($target);
            if ($accessToken) {
                $folder = $this->driveService->findOrCreateUpupWebsiteFolder($accessToken);
                if ($folder) {
                    $target->update(['root_folder_id' => $folder['id']]);
                }
            }
        } elseif ($target->provider === 'wasabi') {
            $creds = $this->getWasabiCredentials($target);
            if ($creds && ! str_ends_with(rtrim($creds['prefix'] ?? '', '/'), GoogleDriveService::UPUP_WEBSITE_FOLDER)) {
                $basePrefix = rtrim($creds['prefix'] ?? '', '/');
                $creds['prefix'] = ($basePrefix ? $basePrefix . '/' : '') . GoogleDriveService::UPUP_WEBSITE_FOLDER . '/';
                $target->update([
                    'credentials' => ['encrypted' => Crypt::encryptString(json_encode($creds))],
                ]);
            }
        }
    }

    /**
     * Delete all files and folders from UPUP_WEBSITE on the target platform before migration.
     */
    private function deleteAllFromTarget(StorageConnection $target): void
    {
        if ($target->provider === 'wasabi') {
            $creds = $this->getWasabiCredentials($target);
            if ($creds) {
                $prefix = rtrim($creds['prefix'] ?? '', '/') . '/';
                if ($prefix !== '/') {
                    $this->wasabiService->deleteObjectRecursive($creds, $prefix);
                }
            }
        } elseif ($target->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($target);
            $rootFolderId = $target->root_folder_id;
            if ($accessToken && $rootFolderId) {
                $files = $this->driveService->listAllFilesAndFolders($accessToken, $rootFolderId);
                foreach (array_reverse($files) as $file) {
                    $this->driveService->deleteFile($accessToken, $file['id']);
                }
            }
        }

        UserDocument::where('storage_connection_id', $target->id)->where('user_id', $target->user_id)->delete();
    }

    /**
     * Restore/sync from current primary to a previously connected target platform.
     * Deletes old content on target, copies from primary, sets target as primary.
     */
    public function restoreFromPrimaryToTarget(StorageConnection $target): bool
    {
        $user = $target->user;
        $primary = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();

        if (! $primary || $primary->id === $target->id) {
            return false;
        }

        return $this->migrate($primary, $target, false);
    }

    private function copyDriveToWasabi($user, StorageConnection $from, StorageConnection $to, bool $deleteFromSource): bool
    {
        $accessToken = $this->getDriveAccessToken($from);
        $wasabiCreds = $this->getWasabiCredentials($to);
        if (! $accessToken || ! $wasabiCreds) {
            return false;
        }

        $docs = UserDocument::where('user_id', $user->id)
            ->where('storage_connection_id', $from->id)
            ->orderByRaw("type = 'folder' DESC")
            ->orderBy('name')
            ->get();

        $oldIdToNew = [];
        $fileSizes = [];
        $prefix = $wasabiCreds['prefix'] ?? '';

        foreach ($docs as $doc) {
            if ($doc->isFolder()) {
                $parentPrefix = $doc->parent_id ? ($oldIdToNew[$doc->parent_id] ?? null) : null;
                $result = $this->wasabiService->createFolder($wasabiCreds, $doc->name, $parentPrefix);
                if ($result) {
                    $oldIdToNew[$doc->id] = $result['key'];
                }
            }
        }

        foreach ($docs as $doc) {
            if ($doc->isFile()) {
                $content = $this->driveService->downloadFileContent($accessToken, $doc->external_id, $doc->mime_type);
                if ($content === null) {
                    Log::warning('Storage migration: file download failed', [
                        'doc_id' => $doc->id,
                        'name' => $doc->name,
                        'error' => $this->driveService->getLastError(),
                    ]);
                    continue;
                }
                $exportInfo = $this->driveService->getExportFileNameAndMimeType($doc->name, $doc->mime_type);
                $uploadName = $exportInfo['name'];
                $uploadMimeType = $exportInfo['mimeType'];
                $parentPrefix = $doc->parent_id ? ($oldIdToNew[$doc->parent_id] ?? ($prefix ?: null)) : ($prefix ?: null);
                $result = $this->wasabiService->uploadFromContent(
                    $wasabiCreds,
                    $content,
                    $uploadName,
                    $uploadMimeType,
                    $parentPrefix
                );
                if ($result) {
                    $oldIdToNew[$doc->id] = $result['key'];
                    $fileSizes[$doc->id] = $result['size'] ?? strlen($content);
                } else {
                    Log::warning('Storage migration: file upload to Wasabi failed', [
                        'doc_id' => $doc->id,
                        'name' => $uploadName,
                        'error' => $this->wasabiService->getLastError(),
                    ]);
                }
            }
        }

        $newDocsByOldId = [];
        foreach ($docs as $doc) {
            $newKey = $oldIdToNew[$doc->id] ?? null;
            if (! $newKey) {
                continue;
            }

            $exportInfo = $doc->isFile()
                ? $this->driveService->getExportFileNameAndMimeType($doc->name, $doc->mime_type)
                : ['name' => $doc->name, 'mimeType' => $doc->mime_type ?? 'application/octet-stream'];

            $newDoc = UserDocument::create([
                'user_id' => $user->id,
                'storage_connection_id' => $to->id,
                'parent_id' => null,
                'name' => $exportInfo['name'],
                'original_name' => $doc->original_name,
                'path' => $newKey,
                'external_id' => $newKey,
                'mime_type' => $exportInfo['mimeType'],
                'size' => $doc->isFile() ? ($fileSizes[$doc->id] ?? $doc->size) : $doc->size,
                'provider' => $to->provider,
                'type' => $doc->type,
            ]);
            $newDocsByOldId[$doc->id] = $newDoc->id;
        }
        foreach ($docs as $doc) {
            if (isset($newDocsByOldId[$doc->id]) && $doc->parent_id && isset($newDocsByOldId[$doc->parent_id])) {
                UserDocument::where('id', $newDocsByOldId[$doc->id])->update([
                    'parent_id' => $newDocsByOldId[$doc->parent_id],
                ]);
            }
        }

        $this->updateDocumentReferences($user->id, $newDocsByOldId);

        if ($deleteFromSource) {
            foreach ($docs->reverse() as $doc) {
                if ($doc->isFile()) {
                    $this->driveService->deleteFile($accessToken, $doc->external_id);
                }
                $doc->delete();
            }
            $folderDocs = $docs->where('type', 'folder');
            foreach ($folderDocs->reverse() as $doc) {
                $this->driveService->deleteFile($accessToken, $doc->external_id);
                $doc->delete();
            }
        } else {
            UserDocument::where('storage_connection_id', $from->id)->where('user_id', $user->id)->delete();
        }

        StorageConnection::setAsPrimary($to);

        return true;
    }

    private function copyWasabiToDrive($user, StorageConnection $from, StorageConnection $to, bool $deleteFromSource): bool
    {
        $wasabiCreds = $this->getWasabiCredentials($from);
        $accessToken = $this->getDriveAccessToken($to);
        if (! $wasabiCreds || ! $accessToken) {
            return false;
        }

        $docs = UserDocument::where('user_id', $user->id)
            ->where('storage_connection_id', $from->id)
            ->orderByRaw("type = 'folder' DESC")
            ->orderBy('name')
            ->get();

        $oldIdToNew = [];

        $driveRootFolderId = $to->root_folder_id;

        foreach ($docs as $doc) {
            if ($doc->isFolder()) {
                $parentId = $doc->parent_id && isset($oldIdToNew[$doc->parent_id])
                    ? $oldIdToNew[$doc->parent_id]
                    : $driveRootFolderId;
                $result = $this->driveService->createFolder($accessToken, $doc->name, $parentId);
                if ($result) {
                    $oldIdToNew[$doc->id] = $result['id'];
                }
            }
        }

        $newDocsByOldId = [];
        foreach ($docs as $doc) {
            if ($doc->isFile()) {
                $content = $this->wasabiService->downloadFileContent($wasabiCreds, $doc->external_id);
                if ($content === null) {
                    Log::warning('Storage migration: Wasabi file download failed', [
                        'doc_id' => $doc->id,
                        'name' => $doc->name,
                        'error' => $this->wasabiService->getLastError(),
                    ]);
                    continue;
                }
                $parentId = $doc->parent_id && isset($oldIdToNew[$doc->parent_id])
                    ? $oldIdToNew[$doc->parent_id]
                    : $driveRootFolderId;
                $result = $this->driveService->uploadFromContent(
                    $accessToken,
                    $content,
                    $doc->name,
                    $doc->mime_type ?? 'application/octet-stream',
                    $parentId
                );
                if ($result) {
                    $oldIdToNew[$doc->id] = $result['id'];
                } else {
                    Log::warning('Storage migration: file upload to Drive failed', [
                        'doc_id' => $doc->id,
                        'name' => $doc->name,
                        'error' => $this->driveService->getLastError(),
                    ]);
                }
            }
        }

        $newDocsByOldId = [];
        foreach ($docs as $doc) {
            $newExternalId = $oldIdToNew[$doc->id] ?? null;
            if (! $newExternalId) {
                continue;
            }

            $newDoc = UserDocument::create([
                'user_id' => $user->id,
                'storage_connection_id' => $to->id,
                'parent_id' => null,
                'name' => $doc->name,
                'original_name' => $doc->original_name,
                'path' => $newExternalId,
                'external_id' => $newExternalId,
                'mime_type' => $doc->mime_type,
                'size' => $doc->size,
                'provider' => $to->provider,
                'type' => $doc->type,
            ]);
            $newDocsByOldId[$doc->id] = $newDoc->id;
        }
        foreach ($docs as $doc) {
            if (isset($newDocsByOldId[$doc->id]) && $doc->parent_id && isset($newDocsByOldId[$doc->parent_id])) {
                UserDocument::where('id', $newDocsByOldId[$doc->id])->update([
                    'parent_id' => $newDocsByOldId[$doc->parent_id],
                ]);
            }
        }

        $this->updateDocumentReferences($user->id, $newDocsByOldId);

        if ($deleteFromSource) {
            foreach ($docs as $doc) {
                if ($doc->isFile()) {
                    $this->wasabiService->deleteObject($wasabiCreds, $doc->external_id);
                } elseif ($doc->isFolder()) {
                    $this->wasabiService->deleteObjectRecursive($wasabiCreds, $doc->external_id);
                }
                $doc->delete();
            }
        } else {
            UserDocument::where('storage_connection_id', $from->id)->where('user_id', $user->id)->delete();
        }

        StorageConnection::setAsPrimary($to);

        return true;
    }

    /**
     * Update document references in user_height_weights, user_childhood_stages, user_childhood_media
     * after migration so they point to the new UserDocument IDs on the target platform.
     */
    private function updateDocumentReferences(int $userId, array $newDocsByOldId): void
    {
        foreach ($newDocsByOldId as $oldId => $newId) {
            if ($oldId === $newId) {
                continue;
            }

            UserHeightWeight::where('user_id', $userId)
                ->where('image_document_id', $oldId)
                ->update(['image_document_id' => $newId]);
            UserHeightWeight::where('user_id', $userId)
                ->where('video_document_id', $oldId)
                ->update(['video_document_id' => $newId]);

            UserChildhoodStage::where('user_id', $userId)
                ->where('footprint_document_id', $oldId)
                ->update(['footprint_document_id' => $newId]);
            UserChildhoodStage::where('user_id', $userId)
                ->where('first_photo_document_id', $oldId)
                ->update(['first_photo_document_id' => $newId]);
            UserChildhoodStage::where('user_id', $userId)
                ->where('first_video_document_id', $oldId)
                ->update(['first_video_document_id' => $newId]);
            UserChildhoodStage::where('user_id', $userId)
                ->where('first_gift_document_id', $oldId)
                ->update(['first_gift_document_id' => $newId]);

            UserChildhoodMedia::where('user_document_id', $oldId)
                ->whereHas('childhoodStage', fn ($q) => $q->where('user_id', $userId))
                ->update(['user_document_id' => $newId]);
        }
    }

    private function getDriveAccessToken(StorageConnection $connection): ?string
    {
        $credentials = $connection->credentials;
        if (! isset($credentials['encrypted'])) {
            return null;
        }
        $decrypted = json_decode(Crypt::decryptString($credentials['encrypted']), true);
        $refreshToken = $decrypted['refresh_token'] ?? null;
        if ($refreshToken) {
            return $this->driveService->getAccessTokenFromRefreshToken($refreshToken);
        }

        return $decrypted['access_token'] ?? null;
    }

    private function getWasabiCredentials(StorageConnection $connection): ?array
    {
        $credentials = $connection->credentials;
        if (! isset($credentials['encrypted'])) {
            return null;
        }

        return json_decode(Crypt::decryptString($credentials['encrypted']), true);
    }
}
