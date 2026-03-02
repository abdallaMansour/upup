<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class GoogleDriveService
{
    private ?string $lastError = null;

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function listFiles(string $accessToken, ?string $folderId = null, int $pageSize = 100): array
    {
        return $this->listFilesAndFolders($accessToken, $folderId, $pageSize);
    }

    public function listFilesAndFolders(string $accessToken, ?string $folderId = null, int $pageSize = 100): array
    {
        $parentQuery = $folderId
            ? "'{$folderId}' in parents"
            : "'root' in parents";

        $params = [
            'pageSize' => $pageSize,
            'fields' => 'nextPageToken, files(id, name, mimeType, size, webViewLink, createdTime, parents)',
            'q' => "trashed = false and {$parentQuery}",
        ];

        $response = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/drive/v3/files', $params);

        if (! $response->successful()) {
            return [];
        }

        $data = $response->json();

        return $data['files'] ?? [];
    }

    public function listAllFilesAndFolders(string $accessToken, int $pageSize = 100): array
    {
        $allFiles = [];
        $pageToken = null;

        do {
            $params = [
                'pageSize' => $pageSize,
                'fields' => 'nextPageToken, files(id, name, mimeType, size, webViewLink, createdTime, parents)',
                'q' => 'trashed = false',
            ];
            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }

            $response = Http::withToken($accessToken)
                ->get('https://www.googleapis.com/drive/v3/files', $params);

            if (! $response->successful()) {
                return [];
            }

            $data = $response->json();
            $files = $data['files'] ?? [];
            $allFiles = array_merge($allFiles, $files);
            $pageToken = $data['nextPageToken'] ?? null;
        } while ($pageToken);

        return $allFiles;
    }

    public function createFolder(string $accessToken, string $name, ?string $parentId = null): ?array
    {
        $metadata = [
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
        ];
        if ($parentId) {
            $metadata['parents'] = [$parentId];
        } else {
            $metadata['parents'] = ['root'];
        }

        $response = Http::withToken($accessToken)
            ->asJson()
            ->post('https://www.googleapis.com/drive/v3/files', $metadata);

        if (! $response->successful()) {
            $body = $response->json();
            $this->lastError = $body['error']['message'] ?? "HTTP {$response->status()}";
            \Log::error('Google Drive createFolder failed', [
                'status' => $response->status(),
                'body' => $body,
            ]);
            return null;
        }

        return $response->json();
    }

    public function uploadFile(string $accessToken, UploadedFile $file, ?string $parentId = null): ?array
    {
        $parentId = $parentId ?: 'root';
        $metadata = [
            'name' => $file->getClientOriginalName(),
            'parents' => [$parentId],
        ];

        $boundary = 'upup_' . bin2hex(random_bytes(8));
        $delimiter = "--{$boundary}\r\n";
        $closeDelimiter = "\r\n--{$boundary}--\r\n";

        $metadataPart = "Content-Type: application/json; charset=UTF-8\r\n\r\n" . json_encode($metadata);
        $mimeType = $file->getMimeType() ?: 'application/octet-stream';
        $filePart = "Content-Type: {$mimeType}\r\n\r\n";

        $multipartBody = $delimiter . $metadataPart . "\r\n" . $delimiter . $filePart;
        $multipartBody .= $file->get();
        $multipartBody .= $closeDelimiter;

        $response = Http::withToken($accessToken)
            ->withHeaders([
                'Content-Type' => "multipart/related; boundary={$boundary}",
            ])
            ->withBody($multipartBody, "multipart/related; boundary={$boundary}")
            ->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart');

        if (! $response->successful()) {
            $body = $response->json();
            $this->lastError = $body['error']['message'] ?? "HTTP {$response->status()}";
            \Log::error('Google Drive uploadFile failed', [
                'status' => $response->status(),
                'body' => $body,
            ]);
            return null;
        }

        return $response->json();
    }

    public function deleteFile(string $accessToken, string $fileId): bool
    {
        $response = Http::withToken($accessToken)
            ->delete("https://www.googleapis.com/drive/v3/files/{$fileId}");

        return $response->successful();
    }

    public function moveFile(string $accessToken, string $fileId, string $newParentId, ?string $oldParentId = null): bool
    {
        $params = ['addParents' => $newParentId];
        if ($oldParentId) {
            $params['removeParents'] = $oldParentId;
        }

        $response = Http::withToken($accessToken)
            ->patch("https://www.googleapis.com/drive/v3/files/{$fileId}?" . http_build_query($params));

        return $response->successful();
    }

    public function getAccessTokenFromRefreshToken(string $refreshToken): ?string
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        if (! $response->successful()) {
            return null;
        }

        return $response->json('access_token');
    }
}
