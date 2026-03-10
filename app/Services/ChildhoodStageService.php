<?php

namespace App\Services;

use App\Models\StorageConnection;
use App\Models\User;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ChildhoodStageService
{
    public const CHILDHOOD_FOLDER = 'childhood';

    public function __construct(
        private GoogleDriveService $driveService,
        private WasabiService $wasabiService
    ) {}

    public function resolveStorageConnection(User $user): ?StorageConnection
    {
        return StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();
    }

    public function getOrCreateChildhoodFolder(User $user, StorageConnection $connection): ?UserDocument
    {
        $existing = UserDocument::where('user_id', $user->id)
            ->where('storage_connection_id', $connection->id)
            ->where('type', 'folder')
            ->where('name', self::CHILDHOOD_FOLDER)
            ->first();

        if ($existing) {
            return $existing;
        }

        if ($connection->provider === 'google_drive' && ! $connection->root_folder_id) {
            $accessToken = $this->getDriveAccessToken($connection);
            if ($accessToken) {
                $upupFolder = $this->driveService->findOrCreateUpupWebsiteFolder($accessToken);
                if ($upupFolder) {
                    $connection->update(['root_folder_id' => $upupFolder['id']]);
                }
            }
        }

        $parentExternalId = $connection->provider === 'google_drive'
            ? ($connection->root_folder_id ?? 'root')
            : null;
        if ($connection->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($connection);
            if (! $accessToken) {
                return null;
            }
            $result = $this->driveService->createFolder($accessToken, self::CHILDHOOD_FOLDER, $parentExternalId);
            if (! $result) {
                return null;
            }
            $externalId = $result['id'];
            $mimeType = 'application/vnd.google-apps.folder';
        } else {
            $credentials = $this->getWasabiCredentials($connection);
            if (! $credentials) {
                return null;
            }
            $parentPrefix = $credentials['prefix'] ?? '';
            $result = $this->wasabiService->createFolder($credentials, self::CHILDHOOD_FOLDER, $parentPrefix !== '' ? $parentPrefix : null);
            if (! $result) {
                return null;
            }
            $externalId = $result['key'];
            $mimeType = 'application/x-wasabi-folder';
        }

        $parentDoc = null;
        if ($connection->provider === 'google_drive' && $connection->root_folder_id) {
            $parentDoc = UserDocument::where('user_id', $user->id)
                ->where('storage_connection_id', $connection->id)
                ->where('external_id', $connection->root_folder_id)
                ->first();
        }

        return UserDocument::create([
            'user_id' => $user->id,
            'storage_connection_id' => $connection->id,
            'parent_id' => $parentDoc?->id,
            'name' => self::CHILDHOOD_FOLDER,
            'original_name' => self::CHILDHOOD_FOLDER,
            'path' => $externalId,
            'external_id' => $externalId,
            'mime_type' => $mimeType,
            'size' => 0,
            'provider' => $connection->provider,
            'type' => 'folder',
        ]);
    }

    public function getOrCreateChildhoodFolderForStage(User $user, StorageConnection $connection, UserChildhoodStage $stage): ?UserDocument
    {
        $mainFolder = $this->getOrCreateChildhoodFolder($user, $connection);
        if (! $mainFolder) {
            return null;
        }

        $stageFolderName = 'stage_'.$stage->id;

        return $this->getOrCreateSubfolder($user, $connection, $mainFolder, $stageFolderName);
    }

    public function getOrCreateSubfolder(User $user, StorageConnection $connection, UserDocument $parentFolder, string $subfolderName): ?UserDocument
    {
        $existing = UserDocument::where('user_id', $user->id)
            ->where('storage_connection_id', $connection->id)
            ->where('type', 'folder')
            ->where('name', $subfolderName)
            ->where('parent_id', $parentFolder->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        if ($connection->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($connection);
            if (! $accessToken) {
                return null;
            }
            $result = $this->driveService->createFolder($accessToken, $subfolderName, $parentFolder->external_id);
            if (! $result) {
                return null;
            }
            $externalId = $result['id'];
            $mimeType = 'application/vnd.google-apps.folder';
        } else {
            $credentials = $this->getWasabiCredentials($connection);
            if (! $credentials) {
                return null;
            }
            $result = $this->wasabiService->createFolder($credentials, $subfolderName, $parentFolder->external_id);
            if (! $result) {
                return null;
            }
            $externalId = $result['key'];
            $mimeType = 'application/x-wasabi-folder';
        }

        return UserDocument::create([
            'user_id' => $user->id,
            'storage_connection_id' => $connection->id,
            'parent_id' => $parentFolder->id,
            'name' => $subfolderName,
            'original_name' => $subfolderName,
            'path' => $externalId,
            'external_id' => $externalId,
            'mime_type' => $mimeType,
            'size' => 0,
            'provider' => $connection->provider,
            'type' => 'folder',
        ]);
    }

    public function uploadFile(UploadedFile $file, UserDocument $parentFolder, User $user, StorageConnection $connection): ?UserDocument
    {
        if ($connection->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($connection);
            if (! $accessToken) {
                return null;
            }
            $result = $this->driveService->uploadFile($accessToken, $file, $parentFolder->external_id);
            if (! $result) {
                Log::warning('ChildhoodStage: Drive upload failed', ['error' => $this->driveService->getLastError()]);
                return null;
            }
            $externalId = $result['id'];
            $name = $result['name'] ?? $file->getClientOriginalName();
            $mimeType = $result['mimeType'] ?? $file->getMimeType();
            $size = (int) ($result['size'] ?? $file->getSize());
        } else {
            $credentials = $this->getWasabiCredentials($connection);
            if (! $credentials) {
                return null;
            }
            $result = $this->wasabiService->uploadFile($credentials, $file, $parentFolder->external_id);
            if (! $result) {
                Log::warning('ChildhoodStage: Wasabi upload failed', ['error' => $this->wasabiService->getLastError()]);
                return null;
            }
            $externalId = $result['key'];
            $name = $result['name'];
            $mimeType = $result['mimeType'] ?? $file->getMimeType();
            $size = (int) $result['size'];
        }

        return UserDocument::create([
            'user_id' => $user->id,
            'storage_connection_id' => $connection->id,
            'parent_id' => $parentFolder->id,
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
            'path' => $externalId,
            'external_id' => $externalId,
            'mime_type' => $mimeType,
            'size' => $size,
            'provider' => $connection->provider,
            'type' => 'file',
        ]);
    }

    public function deleteDocument(UserDocument $document, StorageConnection $connection): bool
    {
        if ($connection->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($connection);
            if ($accessToken) {
                $this->driveService->deleteFile($accessToken, $document->external_id);
            }
        } else {
            $credentials = $this->getWasabiCredentials($connection);
            if ($credentials) {
                $this->wasabiService->deleteObject($credentials, $document->external_id);
            }
        }
        $document->delete();

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
