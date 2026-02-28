<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleDriveService
{
    public function listFiles(string $accessToken, ?string $folderId = null, int $pageSize = 100): array
    {
        $parentQuery = $folderId
            ? "'{$folderId}' in parents"
            : "'root' in parents";

        $params = [
            'pageSize' => $pageSize,
            'fields' => 'nextPageToken, files(id, name, mimeType, size, webViewLink, createdTime)',
            'q' => "trashed = false and {$parentQuery} and mimeType != 'application/vnd.google-apps.folder'",
        ];

        $response = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/drive/v3/files', $params);

        if (! $response->successful()) {
            return [];
        }

        $data = $response->json();

        return $data['files'] ?? [];
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
