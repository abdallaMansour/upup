<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class GoogleDriveService
{
    public const UPUP_WEBSITE_FOLDER = 'UPUP_WEBSITE';

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

    /**
     * Find a folder by name under a parent. Returns first match.
     */
    public function findFolderByName(string $accessToken, string $name, ?string $parentId = null): ?array
    {
        $parentQuery = $parentId
            ? "'{$parentId}' in parents"
            : "'root' in parents";

        $params = [
            'pageSize' => 10,
            'fields' => 'files(id, name, mimeType)',
            'q' => "trashed = false and {$parentQuery} and mimeType='application/vnd.google-apps.folder' and name='{$name}'",
        ];

        $response = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/drive/v3/files', $params);

        if (! $response->successful()) {
            return null;
        }

        $files = $response->json('files') ?? [];

        return $files[0] ?? null;
    }

    /**
     * Find or create UPUP_WEBSITE folder. Returns folder array with 'id'.
     */
    public function findOrCreateUpupWebsiteFolder(string $accessToken): ?array
    {
        $folder = $this->findFolderByName($accessToken, self::UPUP_WEBSITE_FOLDER, null);
        if ($folder) {
            return $folder;
        }

        return $this->createFolder($accessToken, self::UPUP_WEBSITE_FOLDER, null);
    }

    public function listAllFilesAndFolders(string $accessToken, ?string $rootFolderId = null, int $pageSize = 100): array
    {
        if ($rootFolderId) {
            return $this->listFilesRecursiveFromFolder($accessToken, $rootFolderId, $pageSize);
        }

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

    /**
     * Recursively list all files and folders under a folder.
     */
    private function listFilesRecursiveFromFolder(string $accessToken, string $folderId, int $pageSize): array
    {
        $allFiles = [];
        $queue = [$folderId];

        while (! empty($queue)) {
            $currentFolderId = array_shift($queue);
            $pageToken = null;

            do {
                $params = [
                    'pageSize' => $pageSize,
                    'fields' => 'nextPageToken, files(id, name, mimeType, size, webViewLink, createdTime, parents)',
                    'q' => "trashed = false and '{$currentFolderId}' in parents",
                ];
                if ($pageToken) {
                    $params['pageToken'] = $pageToken;
                }

                $response = Http::withToken($accessToken)
                    ->get('https://www.googleapis.com/drive/v3/files', $params);

                if (! $response->successful()) {
                    return $allFiles;
                }

                $data = $response->json();
                $files = $data['files'] ?? [];
                foreach ($files as $file) {
                    $allFiles[] = $file;
                    if (($file['mimeType'] ?? '') === 'application/vnd.google-apps.folder') {
                        $queue[] = $file['id'];
                    }
                }
                $pageToken = $data['nextPageToken'] ?? null;
            } while ($pageToken);
        }

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

    /**
     * Download file content. For Google Workspace files (Docs, Sheets, etc.), uses export API.
     *
     * @param  string  $accessToken
     * @param  string  $fileId
     * @param  string|null  $mimeType  Source file mimeType (e.g. application/vnd.google-apps.document)
     */
    public function downloadFileContent(string $accessToken, string $fileId, ?string $mimeType = null): ?string
    {
        $exportMimeType = $this->getExportMimeTypeForGoogleWorkspace($mimeType);

        if ($exportMimeType) {
            $response = Http::withToken($accessToken)
                ->get("https://www.googleapis.com/drive/v3/files/{$fileId}/export", [
                    'mimeType' => $exportMimeType,
                ]);

            if (! $response->successful()) {
                $this->lastError = $response->json('error.message') ?? "HTTP {$response->status()}";
                \Log::warning('Google Drive export failed', [
                    'fileId' => $fileId,
                    'mimeType' => $mimeType,
                    'error' => $this->lastError,
                ]);

                return null;
            }

            return $response->body();
        }

        $response = Http::withToken($accessToken)
            ->get("https://www.googleapis.com/drive/v3/files/{$fileId}", ['alt' => 'media']);

        if (! $response->successful()) {
            $this->lastError = $response->json('error.message') ?? "HTTP {$response->status()}";
            return null;
        }

        return $response->body();
    }

    /**
     * Get export mimeType for Google Workspace files. Returns null for regular blob files.
     */
    public function getExportMimeTypeForGoogleWorkspace(?string $mimeType): ?string
    {
        if (! $mimeType || ! str_starts_with($mimeType, 'application/vnd.google-apps.')) {
            return null;
        }
        if ($mimeType === 'application/vnd.google-apps.folder') {
            return null;
        }

        return match ($mimeType) {
            'application/vnd.google-apps.document' => 'application/pdf',
            'application/vnd.google-apps.spreadsheet' => 'text/csv',
            'application/vnd.google-apps.presentation' => 'application/pdf',
            'application/vnd.google-apps.drawing' => 'image/png',
            'application/vnd.google-apps.form' => 'application/pdf',
            'application/vnd.google-apps.script' => 'application/vnd.google-apps.script+json',
            default => 'application/pdf',
        };
    }

    /**
     * Get file name and mimeType for export. Adjusts extension when exporting Google Workspace files.
     */
    public function getExportFileNameAndMimeType(string $originalName, ?string $sourceMimeType): array
    {
        $exportMimeType = $this->getExportMimeTypeForGoogleWorkspace($sourceMimeType);
        if (! $exportMimeType) {
            return ['name' => $originalName, 'mimeType' => $sourceMimeType ?: 'application/octet-stream'];
        }

        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        if ($baseName === '' || $baseName === '.') {
            $baseName = $originalName;
        }

        $newExt = match ($exportMimeType) {
            'application/pdf' => 'pdf',
            'text/csv' => 'csv',
            'image/png' => 'png',
            'application/vnd.google-apps.script+json' => 'json',
            default => 'bin',
        };

        return [
            'name' => $baseName . '.' . $newExt,
            'mimeType' => $exportMimeType,
        ];
    }

    public function uploadFromContent(string $accessToken, string $content, string $fileName, string $mimeType, ?string $parentId = null): ?array
    {
        $parentId = $parentId ?: 'root';
        $metadata = [
            'name' => $fileName,
            'parents' => [$parentId],
        ];

        $boundary = 'upup_' . bin2hex(random_bytes(8));
        $delimiter = "--{$boundary}\r\n";
        $closeDelimiter = "\r\n--{$boundary}--\r\n";

        $metadataPart = "Content-Type: application/json; charset=UTF-8\r\n\r\n" . json_encode($metadata);
        $mimeType = $mimeType ?: 'application/octet-stream';
        $filePart = "Content-Type: {$mimeType}\r\n\r\n";

        $multipartBody = $delimiter . $metadataPart . "\r\n" . $delimiter . $filePart;
        $multipartBody .= $content;
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
            return null;
        }

        return $response->json();
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
