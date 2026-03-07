<?php

namespace App\Services;

use App\Models\StorageConnection;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Crypt;

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

        if ($from->provider === 'google_drive' && $to->provider === 'wasabi') {
            return $this->copyDriveToWasabi($user, $from, $to, $deleteFromSource);
        }
        if ($from->provider === 'wasabi' && $to->provider === 'google_drive') {
            return $this->copyWasabiToDrive($user, $from, $to, $deleteFromSource);
        }

        return false;
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

        $targetDocs = UserDocument::where('user_id', $user->id)
            ->where('storage_connection_id', $target->id)
            ->get();

        if ($target->provider === 'wasabi') {
            $creds = $this->getWasabiCredentials($target);
            if ($creds) {
                foreach ($targetDocs as $doc) {
                    if ($doc->isFile()) {
                        $this->wasabiService->deleteObject($creds, $doc->external_id);
                    } elseif ($doc->isFolder()) {
                        $this->wasabiService->deleteObjectRecursive($creds, $doc->external_id);
                    }
                }
            }
        } elseif ($target->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($target);
            if ($accessToken) {
                foreach ($targetDocs->reverse() as $doc) {
                    $this->driveService->deleteFile($accessToken, $doc->external_id);
                }
            }
        }

        UserDocument::where('storage_connection_id', $target->id)->where('user_id', $user->id)->delete();

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
                $content = $this->driveService->downloadFileContent($accessToken, $doc->external_id);
                if ($content === null) {
                    continue;
                }
                $parentPrefix = $doc->parent_id ? ($oldIdToNew[$doc->parent_id] ?? ($prefix ?: null)) : ($prefix ?: null);
                $result = $this->wasabiService->uploadFromContent(
                    $wasabiCreds,
                    $content,
                    $doc->name,
                    $doc->mime_type ?? 'application/octet-stream',
                    $parentPrefix
                );
                if ($result) {
                    $oldIdToNew[$doc->id] = $result['key'];
                }
            }
        }

        $newDocsByOldId = [];
        foreach ($docs as $doc) {
            $newKey = $oldIdToNew[$doc->id] ?? null;
            if (! $newKey) {
                continue;
            }

            $newDoc = UserDocument::create([
                'user_id' => $user->id,
                'storage_connection_id' => $to->id,
                'parent_id' => null,
                'name' => $doc->name,
                'original_name' => $doc->original_name,
                'path' => $newKey,
                'external_id' => $newKey,
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

        foreach ($docs as $doc) {
            if ($doc->isFolder()) {
                $parentId = $doc->parent_id && isset($oldIdToNew[$doc->parent_id])
                    ? $oldIdToNew[$doc->parent_id]
                    : null;
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
                    continue;
                }
                $parentId = $doc->parent_id && isset($oldIdToNew[$doc->parent_id])
                    ? $oldIdToNew[$doc->parent_id]
                    : null;
                $result = $this->driveService->uploadFromContent(
                    $accessToken,
                    $content,
                    $doc->name,
                    $doc->mime_type ?? 'application/octet-stream',
                    $parentId
                );
                if ($result) {
                    $oldIdToNew[$doc->id] = $result['id'];
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
