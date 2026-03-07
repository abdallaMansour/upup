<?php

namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;

class WasabiService
{
    private ?string $lastError = null;

    private const REGION_ENDPOINTS = [
        'us-east-1' => 'https://s3.wasabisys.com',
        'us-east-2' => 'https://s3.us-east-2.wasabisys.com',
        'us-west-1' => 'https://s3.us-west-1.wasabisys.com',
        'eu-central-1' => 'https://s3.eu-central-1.wasabisys.com',
        'eu-central-2' => 'https://s3.eu-central-2.wasabisys.com',
        'ap-northeast-1' => 'https://s3.ap-northeast-1.wasabisys.com',
        'ap-northeast-2' => 'https://s3.ap-northeast-2.wasabisys.com',
    ];

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function createClient(array $credentials): ?S3Client
    {
        $region = $credentials['region'] ?? 'us-east-1';
        $endpoint = self::REGION_ENDPOINTS[$region] ?? 'https://s3.wasabisys.com';

        try {
            return new S3Client([
                'version' => 'latest',
                'region' => $region,
                'endpoint' => $endpoint,
                'credentials' => [
                    'key' => $credentials['access_key'],
                    'secret' => $credentials['secret_key'],
                ],
                'use_path_style_endpoint' => true,
            ]);
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();

            return null;
        }
    }

    /**
     * Test connection and optionally fix the region if bucket is in a different region.
     * Updates $credentials['region'] when the correct region is found.
     */
    public function testConnection(array &$credentials): bool
    {
        $bucket = $credentials['bucket'];

        $client = $this->createClient($credentials);
        if (! $client) {
            return false;
        }

        try {
            $client->headBucket(['Bucket' => $bucket]);

            return true;
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            if (str_contains($message, '403') || str_contains($message, 'Forbidden')) {
                $foundRegion = $this->findBucketRegion($credentials);
                if ($foundRegion) {
                    $credentials['region'] = $foundRegion;
                    $client = $this->createClient($credentials);
                    if ($client) {
                        try {
                            $client->headBucket(['Bucket' => $bucket]);
                            $this->lastError = null;

                            return true;
                        } catch (\Throwable $retryEx) {
                            $this->lastError = $this->formatConnectionError($retryEx->getMessage(), $credentials);
                            return false;
                        }
                    }
                }
                $this->lastError = $this->formatConnectionError($message, $credentials);
            } else {
                $this->lastError = $this->formatConnectionError($message, $credentials);
            }

            return false;
        }
    }

    /**
     * Try HeadBucket across regions to find where the bucket exists.
     */
    private function findBucketRegion(array $credentials): ?string
    {
        $bucket = $credentials['bucket'];
        $regions = array_keys(self::REGION_ENDPOINTS);

        foreach ($regions as $region) {
            $credentials['region'] = $region;
            $client = $this->createClient($credentials);
            if (! $client) {
                continue;
            }
            try {
                $client->headBucket(['Bucket' => $bucket]);

                return $region;
            } catch (\Throwable $e) {
                continue;
            }
        }

        return null;
    }

    private function formatConnectionError(string $rawMessage, array $credentials): string
    {
        $hint = 'تأكد من: 1) صحة Access Key و Secret Key، 2) أن المنطقة المختارة تطابق منطقة الـ Bucket (تحقق من Wasabi Console)، 3) أن لديك صلاحية الوصول للـ Bucket.';
        $shortMsg = strlen($rawMessage) > 200 ? substr($rawMessage, 0, 200) . '...' : $rawMessage;

        return $shortMsg . ' — ' . $hint;
    }

    /**
     * List all objects and "folders" (prefixes) in the bucket recursively.
     * Returns array of items with: key, name, size, lastModified, isFolder, parents.
     */
    public function listAllObjects(array $credentials): array
    {
        $client = $this->createClient($credentials);
        if (! $client) {
            return [];
        }

        $bucket = $credentials['bucket'];
        $prefix = $credentials['prefix'] ?? '';
        $allItems = [];
        $continuationToken = null;

        do {
            $params = [
                'Bucket' => $bucket,
                'Prefix' => $prefix,
                'Delimiter' => '/',
            ];
            if ($continuationToken) {
                $params['ContinuationToken'] = $continuationToken;
            }

            try {
                $result = $client->listObjectsV2($params);
            } catch (\Throwable $e) {
                $this->lastError = $e->getMessage();

                return [];
            }

            $contents = $result['Contents'] ?? [];
            $commonPrefixes = $result['CommonPrefixes'] ?? [];

            foreach ($commonPrefixes as $commonPrefix) {
                $folderKey = $commonPrefix['Prefix'];
                $folderName = trim(str_replace($prefix, '', $folderKey), '/');
                $parentKey = $this->getParentKeyForS3($folderKey, $prefix);
                $allItems[] = [
                    'key' => $folderKey,
                    'name' => $folderName,
                    'size' => 0,
                    'lastModified' => null,
                    'isFolder' => true,
                    'parents' => $parentKey ? [$parentKey] : [null],
                ];
            }

            foreach ($contents as $object) {
                $key = $object['Key'];
                if (str_ends_with($key, '/')) {
                    continue;
                }
                $name = basename($key);
                $parentKey = $this->getParentKeyForS3($key, $prefix);
                $allItems[] = [
                    'key' => $key,
                    'name' => $name,
                    'size' => (int) ($object['Size'] ?? 0),
                    'lastModified' => $object['LastModified'] ?? null,
                    'isFolder' => false,
                    'parents' => [$parentKey],
                ];
            }

            $continuationToken = $result['IsTruncated'] ? ($result['NextContinuationToken'] ?? null) : null;
        } while ($continuationToken);

        return $allItems;
    }

    /**
     * Recursively list all objects - for full sync we need to traverse all prefixes.
     */
    public function listAllObjectsRecursive(array $credentials): array
    {
        $allItems = [];
        $prefix = $credentials['prefix'] ?? '';
        $this->listPrefixRecursive($credentials, $prefix, $allItems);

        return $allItems;
    }

    private function listPrefixRecursive(array $credentials, string $prefix, array &$allItems): void
    {
        $client = $this->createClient($credentials);
        if (! $client) {
            return;
        }

        $bucket = $credentials['bucket'];
        $continuationToken = null;

        do {
            $params = [
                'Bucket' => $bucket,
                'Prefix' => $prefix,
                'Delimiter' => '/',
            ];
            if ($continuationToken) {
                $params['ContinuationToken'] = $continuationToken;
            }

            try {
                $result = $client->listObjectsV2($params);
            } catch (\Throwable $e) {
                return;
            }

            foreach ($result['CommonPrefixes'] ?? [] as $commonPrefix) {
                $folderKey = $commonPrefix['Prefix'];
                $folderName = trim(str_replace($prefix, '', $folderKey), '/');
                $parentKey = $this->getParentKeyForS3($folderKey, $credentials['prefix'] ?? '');
                $allItems[] = [
                    'key' => $folderKey,
                    'name' => $folderName,
                    'size' => 0,
                    'lastModified' => null,
                    'isFolder' => true,
                    'parents' => $parentKey ? [$parentKey] : [null],
                ];
                $this->listPrefixRecursive($credentials, $folderKey, $allItems);
            }

            foreach ($result['Contents'] ?? [] as $object) {
                $key = $object['Key'];
                if (str_ends_with($key, '/')) {
                    continue;
                }
                $name = basename($key);
                $parentKey = $this->getParentKeyForS3($key, $credentials['prefix'] ?? '');
                $allItems[] = [
                    'key' => $key,
                    'name' => $name,
                    'size' => (int) ($object['Size'] ?? 0),
                    'lastModified' => $object['LastModified'] ?? null,
                    'isFolder' => false,
                    'parents' => [$parentKey],
                ];
            }

            $continuationToken = $result['IsTruncated'] ? ($result['NextContinuationToken'] ?? null) : null;
        } while ($continuationToken);
    }

    private function getParentKeyForS3(string $key, string $rootPrefix): ?string
    {
        $key = rtrim($key, '/');
        $dir = dirname($key);
        if ($dir === '.' || $dir === '') {
            return null;
        }
        $dir = $dir . '/';
        $rootPrefix = rtrim($rootPrefix, '/');
        if ($rootPrefix && (str_starts_with($dir, $rootPrefix . '/') || $dir === $rootPrefix . '/')) {
            $relative = substr($dir, strlen($rootPrefix) + 1);
            if ($relative === '' || $relative === '/') {
                return null;
            }
        }

        return $dir;
    }

    public function createFolder(array $credentials, string $name, ?string $parentPrefix = null): ?array
    {
        $client = $this->createClient($credentials);
        if (! $client) {
            return null;
        }

        $prefix = $credentials['prefix'] ?? '';
        $folderKey = $parentPrefix
            ? rtrim($parentPrefix, '/') . '/' . $name . '/'
            : $prefix . $name . '/';

        try {
            $client->putObject([
                'Bucket' => $credentials['bucket'],
                'Key' => $folderKey,
                'Body' => '',
            ]);

            return ['key' => $folderKey, 'id' => $folderKey];
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();

            return null;
        }
    }

    public function uploadFile(array $credentials, UploadedFile $file, ?string $parentPrefix = null): ?array
    {
        return $this->uploadFromContent(
            $credentials,
            $file->get(),
            $file->getClientOriginalName(),
            $file->getMimeType() ?: 'application/octet-stream',
            $parentPrefix
        );
    }

    public function uploadFromContent(array $credentials, string $content, string $fileName, string $mimeType, ?string $parentPrefix = null): ?array
    {
        $client = $this->createClient($credentials);
        if (! $client) {
            return null;
        }

        $prefix = $credentials['prefix'] ?? '';
        $fileKey = $parentPrefix
            ? rtrim($parentPrefix, '/') . '/' . $fileName
            : $prefix . $fileName;

        try {
            $client->putObject([
                'Bucket' => $credentials['bucket'],
                'Key' => $fileKey,
                'Body' => $content,
                'ContentType' => $mimeType ?: 'application/octet-stream',
            ]);

            return [
                'key' => $fileKey,
                'id' => $fileKey,
                'name' => $fileName,
                'size' => strlen($content),
                'mimeType' => $mimeType,
            ];
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();

            return null;
        }
    }

    public function downloadFileContent(array $credentials, string $key): ?string
    {
        $client = $this->createClient($credentials);
        if (! $client) {
            return null;
        }

        try {
            $result = $client->getObject([
                'Bucket' => $credentials['bucket'],
                'Key' => $key,
            ]);

            return (string) $result['Body'];
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();

            return null;
        }
    }

    public function deleteObject(array $credentials, string $key): bool
    {
        $client = $this->createClient($credentials);
        if (! $client) {
            return false;
        }

        try {
            $client->deleteObject([
                'Bucket' => $credentials['bucket'],
                'Key' => $key,
            ]);

            return true;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();

            return false;
        }
    }

    /**
     * Delete object and if it's a folder prefix, delete all objects under it.
     */
    public function deleteObjectRecursive(array $credentials, string $key): bool
    {
        $client = $this->createClient($credentials);
        if (! $client) {
            return false;
        }

        $bucket = $credentials['bucket'];
        $prefix = str_ends_with($key, '/') ? $key : $key . '/';

        try {
            $paginator = $client->getPaginator('ListObjectsV2', [
                'Bucket' => $bucket,
                'Prefix' => $prefix,
            ]);

            $objectsToDelete = [];
            foreach ($paginator as $result) {
                foreach ($result['Contents'] ?? [] as $object) {
                    $objectsToDelete[] = ['Key' => $object['Key']];
                }
            }

            if (! empty($objectsToDelete)) {
                $client->deleteObjects([
                    'Bucket' => $bucket,
                    'Delete' => ['Objects' => $objectsToDelete],
                ]);
            }

            $client->deleteObject([
                'Bucket' => $bucket,
                'Key' => $key,
            ]);

            return true;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();

            return false;
        }
    }

    /**
     * Move/copy object to new prefix. S3 has no native move - copy then delete.
     */
    public function moveObject(array $credentials, string $sourceKey, string $newPrefix): bool
    {
        $client = $this->createClient($credentials);
        if (! $client) {
            return false;
        }

        $bucket = $credentials['bucket'];
        $fileName = basename(rtrim($sourceKey, '/'));
        $destKey = rtrim($newPrefix, '/') . '/' . $fileName;

        try {
            if (str_ends_with($sourceKey, '/')) {
                $this->lastError = 'نقل المجلدات غير مدعوم في Wasabi - استخدم النسخ يدوياً.';

                return false;
            }

            $client->copyObject([
                'Bucket' => $bucket,
                'CopySource' => $bucket . '/' . $sourceKey,
                'Key' => $destKey,
            ]);

            $client->deleteObject([
                'Bucket' => $bucket,
                'Key' => $sourceKey,
            ]);

            return true;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();

            return false;
        }
    }

    /**
     * Get a pre-signed URL for viewing/downloading a file.
     */
    public function getPresignedUrl(array $credentials, string $key, int $expiresIn = 3600): ?string
    {
        $client = $this->createClient($credentials);
        if (! $client) {
            return null;
        }

        try {
            $cmd = $client->getCommand('GetObject', [
                'Bucket' => $credentials['bucket'],
                'Key' => $key,
            ]);

            $request = $client->createPresignedRequest($cmd, "+{$expiresIn} seconds");

            return (string) $request->getUri();
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();

            return null;
        }
    }
}
