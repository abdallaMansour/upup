<?php

namespace App\Services;

use App\Models\StorageConnection;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class DocumentEmbedService
{
    public function streamImage(UserDocument $document, GoogleDriveService $driveService, WasabiService $wasabiService): ?Response
    {
        if ($document->isFolder()) {
            return null;
        }

        $mime = $document->mime_type ?? 'application/octet-stream';
        if (! str_starts_with($mime, 'image/')) {
            if (! $document->looksLikeImageByExtension()) {
                return null;
            }
            $mime = $this->inferImageMimeFromExtension($document);
        }

        $connection = $document->storageConnection;
        if (! $connection) {
            return null;
        }

        $content = null;

        if ($connection->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($connection, $driveService);
            if (! $accessToken) {
                return null;
            }
            $content = $driveService->downloadFileContent($accessToken, $document->external_id, $document->mime_type);
        } elseif ($connection->provider === 'wasabi') {
            $credentials = $this->getWasabiCredentials($connection);
            if (! $credentials) {
                return null;
            }
            $content = $wasabiService->downloadFileContent($credentials, $document->external_id);
        }

        if (! $content) {
            return null;
        }

        return response($content)
            ->header('Content-Type', $mime)
            ->header('Cache-Control', 'private, max-age=3600');
    }

    private function getDriveAccessToken(StorageConnection $connection, GoogleDriveService $driveService): ?string
    {
        $credentials = $connection->credentials;
        if (! isset($credentials['encrypted'])) {
            return null;
        }
        $decrypted = json_decode(Crypt::decryptString($credentials['encrypted']), true);
        $refreshToken = $decrypted['refresh_token'] ?? null;

        if ($refreshToken) {
            return $driveService->getAccessTokenFromRefreshToken($refreshToken);
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

    private function inferImageMimeFromExtension(UserDocument $document): string
    {
        $name = $document->original_name ?? $document->name ?? $document->path ?? '';
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $map = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'heic' => 'image/heic',
        ];

        return $map[$ext] ?? 'image/jpeg';
    }
}
