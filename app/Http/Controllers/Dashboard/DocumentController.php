<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\StorageConnection;
use App\Models\StoragePlatform;
use App\Models\UserDocument;
use App\Services\GoogleDriveService;
use App\Services\StorageMigrationService;
use App\Services\WasabiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Laravel\Socialite\Facades\Socialite;

class DocumentController extends Controller
{
    private function ensureWebUser(): void
    {
        if (! auth('web')->check()) {
            abort(403, 'هذه الصفحة متاحة للمستخدمين فقط.');
        }
    }

    public function index(Request $request)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $primaryConnection = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();

        $folderId = $request->query('folder');
        $parentId = null;
        $breadcrumb = [['id' => null, 'name' => 'الرئيسية']];

        if ($folderId) {
            $folderQuery = UserDocument::where('user_id', $user->id)
                ->where('id', $folderId)
                ->where('type', 'folder')
                ->with('parent')
                ->when($primaryConnection, fn ($q) => $q->where('storage_connection_id', $primaryConnection->id), fn ($q) => $q->whereRaw('1=0'));
            $folder = $folderQuery->firstOrFail();
            $parentId = $folder->id;
            $breadcrumb = $this->buildBreadcrumb($folder);
        }

        $documents = UserDocument::where('user_id', $user->id)
            ->when($primaryConnection, fn ($q) => $q->where('storage_connection_id', $primaryConnection->id), fn ($q) => $q->whereRaw('1=0'))
            ->where('parent_id', $parentId)
            ->with('storageConnection')
            ->orderByRaw("type = 'folder' DESC")
            ->orderBy('name')
            ->paginate(15);

        $allFolders = UserDocument::where('user_id', $user->id)
            ->when($primaryConnection, fn ($q) => $q->where('storage_connection_id', $primaryConnection->id), fn ($q) => $q->whereRaw('1=0'))
            ->where('type', 'folder')
            ->orderBy('name')
            ->get();

        return view('dashboard.documents.index', compact('documents', 'primaryConnection', 'breadcrumb', 'folderId', 'allFolders'));
    }

    private function buildBreadcrumb(UserDocument $folder): array
    {
        $breadcrumb = [];
        $current = $folder;
        while ($current) {
            array_unshift($breadcrumb, ['id' => $current->id, 'name' => $current->name]);
            $current = $current->parent;
        }
        array_unshift($breadcrumb, ['id' => null, 'name' => 'الرئيسية']);

        return $breadcrumb;
    }

    public function storeFolder(Request $request, GoogleDriveService $driveService, WasabiService $wasabiService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:user_documents,id'],
        ]);

        $connection = $this->resolveStorageConnection($user, $validated['parent_id'] ?? null);
        if (! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً.');
        }

        $parentDocId = $validated['parent_id'] ?? null;

        if ($connection->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($connection, $driveService);
            if (! $accessToken) {
                return redirect()->back()->with('error', 'فشل في الحصول على صلاحية الوصول.');
            }
            $parentExternalId = $parentDocId ? UserDocument::where('user_id', $user->id)->findOrFail($parentDocId)->external_id : ($connection->root_folder_id ?? 'root');
            $result = $driveService->createFolder($accessToken, $validated['name'], $parentExternalId);
            if (! $result) {
                return redirect()->back()->with('error', 'فشل في إنشاء المجلد على Google Drive. SYNC_FAILED');
            }
            $externalId = $result['id'];
            $mimeType = 'application/vnd.google-apps.folder';
        } else {
            $credentials = $this->getWasabiCredentials($connection);
            if (! $credentials) {
                return redirect()->back()->with('error', 'فشل في الحصول على بيانات Wasabi.');
            }
            $parentPrefix = $parentDocId ? UserDocument::where('user_id', $user->id)->findOrFail($parentDocId)->external_id : null;
            $result = $wasabiService->createFolder($credentials, $validated['name'], $parentPrefix);
            if (! $result) {
                return redirect()->back()->with('error', 'فشل في إنشاء المجلد على Wasabi: ' . ($wasabiService->getLastError() ?? ''));
            }
            $externalId = $result['key'];
            $mimeType = 'application/x-wasabi-folder';
        }

        UserDocument::create([
            'user_id' => $user->id,
            'storage_connection_id' => $connection->id,
            'parent_id' => $parentDocId,
            'name' => $validated['name'],
            'original_name' => $validated['name'],
            'path' => $externalId,
            'external_id' => $externalId,
            'mime_type' => $mimeType,
            'size' => 0,
            'provider' => $connection->provider,
            'type' => 'folder',
        ]);

        $redirectUrl = $parentDocId
            ? route('dashboard.documents.index', ['folder' => $parentDocId])
            : route('dashboard.documents.index');

        return redirect($redirectUrl)->with('success', 'تم إنشاء المجلد بنجاح.');
    }

    public function storeFile(Request $request, GoogleDriveService $driveService, WasabiService $wasabiService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:51200'], // 50MB for Wasabi
            'parent_id' => ['nullable', 'exists:user_documents,id'],
        ]);

        $connection = $this->resolveStorageConnection($user, $validated['parent_id'] ?? null);
        if (! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين.');
        }

        $parentDocId = $validated['parent_id'] ?? null;

        if ($connection->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($connection, $driveService);
            if (! $accessToken) {
                return redirect()->back()->with('error', 'فشل في الحصول على صلاحية الوصول.');
            }
            $parentExternalId = $parentDocId ? UserDocument::where('user_id', $user->id)->findOrFail($parentDocId)->external_id : ($connection->root_folder_id ?? 'root');
            $result = $driveService->uploadFile($accessToken, $validated['file'], $parentExternalId);
            if (! $result) {
                return redirect()->back()->with('error', 'فشل في رفع الملف إلى Google Drive. SYNC_FAILED');
            }
            $externalId = $result['id'];
            $name = $result['name'] ?? $validated['file']->getClientOriginalName();
            $mimeType = $result['mimeType'] ?? $validated['file']->getMimeType();
            $size = (int) ($result['size'] ?? $validated['file']->getSize());
        } else {
            $credentials = $this->getWasabiCredentials($connection);
            if (! $credentials) {
                return redirect()->back()->with('error', 'فشل في الحصول على بيانات Wasabi.');
            }
            $parentPrefix = $parentDocId ? UserDocument::where('user_id', $user->id)->findOrFail($parentDocId)->external_id : null;
            $result = $wasabiService->uploadFile($credentials, $validated['file'], $parentPrefix);
            if (! $result) {
                return redirect()->back()->with('error', 'فشل في رفع الملف إلى Wasabi: ' . ($wasabiService->getLastError() ?? ''));
            }
            $externalId = $result['key'];
            $name = $result['name'];
            $mimeType = $result['mimeType'] ?? $validated['file']->getMimeType();
            $size = (int) $result['size'];
        }

        UserDocument::create([
            'user_id' => $user->id,
            'storage_connection_id' => $connection->id,
            'parent_id' => $parentDocId,
            'name' => $name,
            'original_name' => $validated['file']->getClientOriginalName(),
            'path' => $externalId,
            'external_id' => $externalId,
            'mime_type' => $mimeType,
            'size' => $size,
            'provider' => $connection->provider,
            'type' => 'file',
        ]);

        $redirectUrl = $parentDocId
            ? route('dashboard.documents.index', ['folder' => $parentDocId])
            : route('dashboard.documents.index');

        return redirect($redirectUrl)->with('success', 'تم رفع الملف بنجاح.');
    }

    public function destroy(Request $request, UserDocument $document, GoogleDriveService $driveService, WasabiService $wasabiService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        if ($document->user_id !== $user->id) {
            abort(403);
        }

        $connection = StorageConnection::where('user_id', $user->id)
            ->where('id', $document->storage_connection_id)
            ->first();

        if ($connection) {
            if ($connection->provider === 'google_drive') {
                $accessToken = $this->getDriveAccessToken($connection, $driveService);
                if ($accessToken) {
                    $driveService->deleteFile($accessToken, $document->external_id);
                }
            } elseif ($connection->provider === 'wasabi') {
                $credentials = $this->getWasabiCredentials($connection);
                if ($credentials) {
                    if ($document->isFolder()) {
                        $wasabiService->deleteObjectRecursive($credentials, $document->external_id);
                    } else {
                        $wasabiService->deleteObject($credentials, $document->external_id);
                    }
                }
            }
        }

        $document->delete();

        $redirectUrl = $document->parent_id
            ? route('dashboard.documents.index', ['folder' => $document->parent_id])
            : route('dashboard.documents.index');

        return redirect($redirectUrl)->with('success', 'تم الحذف بنجاح.');
    }

    public function move(Request $request, UserDocument $document, GoogleDriveService $driveService, WasabiService $wasabiService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        if ($document->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'new_parent_id' => ['nullable', 'exists:user_documents,id'],
        ]);

        $newParentId = $validated['new_parent_id'] ?? null;

        if ($newParentId === $document->parent_id) {
            return redirect()->back()->with('info', 'الملف موجود بالفعل في هذا المجلد.');
        }

        if ($newParentId) {
            $newParent = UserDocument::where('user_id', $user->id)->where('type', 'folder')->findOrFail($newParentId);
            if ($newParent->id === $document->id || $this->isDescendant($document, $newParent)) {
                return redirect()->back()->with('error', 'لا يمكن نقل المجلد إلى نفسه أو إلى مجلد فرعي منه.');
            }
        }

        $connection = StorageConnection::where('user_id', $user->id)
            ->where('id', $document->storage_connection_id)
            ->first();

        if (! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال التخزين.');
        }

        if ($connection->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($connection, $driveService);
            if ($accessToken) {
                $newParentExternalId = $newParentId
                    ? UserDocument::find($newParentId)->external_id
                    : ($connection->root_folder_id ?? 'root');
                $oldParentExternalId = $document->parent_id
                    ? UserDocument::find($document->parent_id)->external_id
                    : ($connection->root_folder_id ?? 'root');
                $driveService->moveFile($accessToken, $document->external_id, $newParentExternalId, $oldParentExternalId);
            }
        } elseif ($connection->provider === 'wasabi' && ! $document->isFolder()) {
            $credentials = $this->getWasabiCredentials($connection);
            if ($credentials) {
                $newPrefix = $newParentId
                    ? UserDocument::find($newParentId)->external_id
                    : ($credentials['prefix'] ?? '');
                $wasabiService->moveObject($credentials, $document->external_id, $newPrefix);
            }
        } elseif ($connection->provider === 'wasabi' && $document->isFolder()) {
            return redirect()->back()->with('error', 'نقل المجلدات غير مدعوم في Wasabi حالياً.');
        }

        $document->update(['parent_id' => $newParentId]);

        $redirectUrl = $document->parent_id
            ? route('dashboard.documents.index', ['folder' => $document->parent_id])
            : route('dashboard.documents.index');

        return redirect($redirectUrl)->with('success', 'تم نقل العنصر بنجاح.');
    }

    private function resolveStorageConnection($user, ?string $parentId): ?StorageConnection
    {
        $primary = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();

        if (! $primary) {
            return null;
        }

        if ($parentId) {
            $parent = UserDocument::where('user_id', $user->id)->find($parentId);
            if ($parent && $parent->storage_connection_id !== $primary->id) {
                return null;
            }
        }

        return $primary;
    }

    private function isDescendant(UserDocument $folder, UserDocument $potentialAncestor): bool
    {
        $current = $folder->parent;
        while ($current) {
            if ($current->id === $potentialAncestor->id) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }

    private function getDriveAccessToken(StorageConnection $connection, GoogleDriveService $driveService): ?string
    {
        $credentials = $connection->credentials;
        if (! isset($credentials['encrypted'])) {
            return null;
        }
        $decrypted = json_decode(Crypt::decryptString($credentials['encrypted']), true);
        $refreshToken = $decrypted['refresh_token'] ?? null;

        // Always use refresh token when available - access tokens expire after ~1 hour
        if ($refreshToken) {
            return $driveService->getAccessTokenFromRefreshToken($refreshToken);
        }

        return $decrypted['access_token'] ?? null;
    }

    public function storageConnections(Request $request)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connections = StorageConnection::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $primaryConnection = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();

        $connectedProviderKeys = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('provider')
            ->toArray();

        $connectionsForRestore = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->when($primaryConnection, fn ($q) => $q->where('id', '!=', $primaryConnection->id))
            ->get()
            ->keyBy('provider');

        $storagePlatforms = StoragePlatform::orderBy('provider')->get();

        $hasPrimaryConnection = (bool) $primaryConnection;

        return view('dashboard.documents.storage-connections', compact('connections', 'connectedProviderKeys', 'storagePlatforms', 'primaryConnection', 'hasPrimaryConnection', 'connectionsForRestore'));
    }

    public function switchStorageConfirm(Request $request)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $to = $request->query('to');
        if (! in_array($to, ['google_drive', 'wasabi'], true)) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'منصة غير صالحة.');
        }

        $primaryConnection = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();

        if (! $primaryConnection) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'لا يوجد منصة أساسية.');
        }

        $targetPlatform = StoragePlatform::where('provider', $to)->first();
        $targetName = $targetPlatform ? $targetPlatform->name : $to;

        return view('dashboard.documents.switch-storage-confirm', [
            'primaryConnection' => $primaryConnection,
            'to' => $to,
            'targetName' => $targetName,
        ]);
    }

    public function switchStorageConfirmRestore(Request $request)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connectionId = $request->query('connection_id');
        if (! $connectionId) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'معرف الاتصال مطلوب.');
        }

        $target = StorageConnection::where('id', $connectionId)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->firstOrFail();

        $primary = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();

        if (! $primary || $primary->id === $target->id) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'لا يمكن تنفيذ هذه العملية.');
        }

        return view('dashboard.documents.switch-storage-confirm-restore', [
            'primaryConnection' => $primary,
            'targetConnection' => $target,
        ]);
    }

    public function switchStorageProceed(Request $request)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $validated = $request->validate([
            'to' => ['required', 'string', 'in:google_drive,wasabi'],
            'delete_source' => ['nullable', 'boolean'],
        ]);

        $primaryConnection = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();

        if (! $primaryConnection) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'لا يوجد منصة أساسية.');
        }

        session([
            'storage_switch_migrate' => true,
            'storage_switch_delete_source' => (bool) ($validated['delete_source'] ?? false),
        ]);

        if ($validated['to'] === 'google_drive') {
            return redirect()->route('dashboard.documents.google-drive.connect');
        }

        return redirect()->route('dashboard.documents.wasabi.connect');
    }

    public function switchStorageRestore(Request $request, StorageMigrationService $migrationService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $validated = $request->validate([
            'connection_id' => ['required', 'integer', 'exists:storage_connections,id'],
        ]);

        $target = StorageConnection::where('id', $validated['connection_id'])
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->firstOrFail();

        $primary = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();

        if (! $primary || $primary->id === $target->id) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'لا يمكن تنفيذ هذه العملية.');
        }

        if (! $migrationService->restoreFromPrimaryToTarget($target)) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'فشل في مزامنة الملفات إلى المنصة المستهدفة.');
        }

        return redirect()->route('dashboard.documents.storage-connections')
            ->with('success', 'تم الانتقال إلى المنصة بنجاح.');
    }

    public function connectGoogleDrive(Request $request)
    {
        $this->ensureWebUser();

        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'لم يتم إعداد Google Drive. يرجى التواصل مع المسؤول.');
        }

        $platform = StoragePlatform::where('provider', 'google_drive')->first();
        if (! $platform || ! $platform->is_active) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'منصة Google Drive غير متاحة للربط حالياً.');
        }

        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/drive'])
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect();
    }

    public function callbackGoogleDrive(Request $request, GoogleDriveService $driveService, StorageMigrationService $migrationService)
    {
        $this->ensureWebUser();
        $user = $request->user('web');

        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'لم يتم إعداد Google Drive.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'فشل في الربط مع Google: ' . $e->getMessage());
        }

        $credentials = [
            'access_token' => $googleUser->token,
            'refresh_token' => $googleUser->refreshToken,
            'expires_in' => $googleUser->expiresIn,
        ];

        $accessToken = $driveService->getAccessTokenFromRefreshToken($credentials['refresh_token'] ?? '')
            ?? $credentials['access_token'] ?? null;
        $upupFolder = $accessToken ? $driveService->findOrCreateUpupWebsiteFolder($accessToken) : null;
        $rootFolderId = $upupFolder['id'] ?? null;

        $connection = StorageConnection::updateOrCreate(
            [
                'user_id' => $user->id,
                'provider' => 'google_drive',
            ],
            [
                'user_id' => $user->id,
                'provider' => 'google_drive',
                'name' => $googleUser->email,
                'is_active' => true,
                'credentials' => ['encrypted' => Crypt::encryptString(json_encode($credentials))],
                'root_folder_id' => $rootFolderId,
            ]
        );

        if (session('storage_switch_migrate')) {
            $deleteSource = session('storage_switch_delete_source', false);
            session()->forget(['storage_switch_migrate', 'storage_switch_delete_source']);
            $primary = StorageConnection::where('user_id', $user->id)->where('is_active', true)->where('is_primary', true)->first();
            if ($primary && $primary->id !== $connection->id && $migrationService->migrate($primary, $connection, $deleteSource)) {
                return redirect()->route('dashboard.documents.storage-connections')
                    ->with('success', 'تم نقل ملفاتك إلى Google Drive بنجاح.');
            }
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'فشل في نقل الملفات. تم الربط لكن يرجى التحقق من الملفات.');
        }

        $primaryExists = StorageConnection::where('user_id', $user->id)->where('is_active', true)->where('is_primary', true)->exists();
        if (! $primaryExists) {
            StorageConnection::setAsPrimary($connection);
        }

        $this->syncGoogleDriveFiles($user, $connection, $driveService);

        return redirect()->route('dashboard.documents.storage-connections')
            ->with('success', 'تم ربط Google Drive بنجاح! تم جلب ملفاتك.');
    }

    public function syncGoogleDrive(Request $request, GoogleDriveService $driveService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connection = StorageConnection::where('user_id', $user->id)
            ->where('provider', 'google_drive')
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();

        if (! $connection) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'لم يتم العثور على اتصال Google Drive النشط.');
        }

        $this->syncGoogleDriveFiles($user, $connection, $driveService);

        return redirect()->route('dashboard.documents.index')
            ->with('success', 'تم مزامنة الملفات من Google Drive بنجاح.');
    }

    private function syncGoogleDriveFiles($user, StorageConnection $connection, GoogleDriveService $driveService): void
    {
        $accessToken = $this->getDriveAccessToken($connection, $driveService);
        if (! $accessToken) {
            return;
        }

        $rootFolderId = $connection->root_folder_id;
        if (! $rootFolderId) {
            $upupFolder = $driveService->findOrCreateUpupWebsiteFolder($accessToken);
            if ($upupFolder) {
                $connection->update(['root_folder_id' => $upupFolder['id']]);
                $rootFolderId = $upupFolder['id'];
            }
        }

        $files = $driveService->listAllFilesAndFolders($accessToken, $rootFolderId);

        $externalIds = [];
        $externalToId = [];
        foreach ($files as $file) {
            $fileId = $file['id'];
            $externalIds[] = $fileId;
            $parentExternalId = $file['parents'][0] ?? null;
            $isFolder = ($file['mimeType'] ?? '') === 'application/vnd.google-apps.folder';

            $doc = UserDocument::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'storage_connection_id' => $connection->id,
                    'external_id' => $fileId,
                ],
                [
                    'user_id' => $user->id,
                    'storage_connection_id' => $connection->id,
                    'parent_id' => null,
                    'name' => $file['name'],
                    'original_name' => $file['name'],
                    'path' => $fileId,
                    'external_id' => $fileId,
                    'mime_type' => $file['mimeType'] ?? null,
                    'size' => (int) ($file['size'] ?? 0),
                    'provider' => 'google_drive',
                    'type' => $isFolder ? 'folder' : 'file',
                ]
            );
            $externalToId[$fileId] = ['doc_id' => $doc->id, 'parent_external' => $parentExternalId];
        }

        foreach ($externalToId as $externalId => $info) {
            $parentExternal = $info['parent_external'];
            if (! $parentExternal || $parentExternal === 'root') {
                continue;
            }
            $parentDocId = $externalToId[$parentExternal]['doc_id'] ?? null;
            if ($parentDocId) {
                UserDocument::where('id', $info['doc_id'])->update(['parent_id' => $parentDocId]);
            }
        }

        $query = UserDocument::where('user_id', $user->id)
            ->where('storage_connection_id', $connection->id);
        if (empty($externalIds)) {
            $query->delete();
        } else {
            $query->whereNotIn('external_id', $externalIds)->delete();
        }
    }

    public function connectWasabi(Request $request)
    {
        $this->ensureWebUser();

        $platform = StoragePlatform::where('provider', 'wasabi')->first();
        if (! $platform || ! $platform->is_active) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'منصة Wasabi غير متاحة للربط حالياً.');
        }

        return view('dashboard.documents.wasabi-connect');
    }

    public function storeWasabi(Request $request, WasabiService $wasabiService, StorageMigrationService $migrationService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $validated = $request->validate([
            'access_key' => ['required', 'string'],
            'secret_key' => ['required', 'string'],
            'bucket' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'in:us-east-1,us-east-2,us-west-1,eu-central-1,eu-central-2,ap-northeast-1,ap-northeast-2'],
            'prefix' => ['nullable', 'string', 'max:500'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $basePrefix = isset($validated['prefix']) && $validated['prefix'] !== '' ? rtrim($validated['prefix'], '/') . '/' : '';
        $credentials = [
            'access_key' => $validated['access_key'],
            'secret_key' => $validated['secret_key'],
            'bucket' => $validated['bucket'],
            'region' => $validated['region'],
            'prefix' => $basePrefix . GoogleDriveService::UPUP_WEBSITE_FOLDER . '/',
        ];

        if (! $wasabiService->testConnection($credentials)) {
            return redirect()->back()
                ->withInput($request->except('secret_key'))
                ->with('error', 'فشل في الاتصال بـ Wasabi: ' . ($wasabiService->getLastError() ?? 'تحقق من البيانات المدخلة.'));
        }

        $connection = StorageConnection::updateOrCreate(
            [
                'user_id' => $user->id,
                'provider' => 'wasabi',
            ],
            [
                'user_id' => $user->id,
                'provider' => 'wasabi',
                'name' => $validated['name'] ?? $validated['bucket'],
                'is_active' => true,
                'credentials' => ['encrypted' => Crypt::encryptString(json_encode($credentials))],
                'root_folder_id' => null,
            ]
        );

        if (session('storage_switch_migrate')) {
            $deleteSource = session('storage_switch_delete_source', false);
            session()->forget(['storage_switch_migrate', 'storage_switch_delete_source']);
            $primary = StorageConnection::where('user_id', $user->id)->where('is_active', true)->where('is_primary', true)->first();
            if ($primary && $primary->id !== $connection->id && $migrationService->migrate($primary, $connection, $deleteSource)) {
                return redirect()->route('dashboard.documents.storage-connections')
                    ->with('success', 'تم نقل ملفاتك إلى Wasabi بنجاح.');
            }
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'فشل في نقل الملفات. تم الربط لكن يرجى التحقق من الملفات.');
        }

        $primaryExists = StorageConnection::where('user_id', $user->id)->where('is_active', true)->where('is_primary', true)->exists();
        if (! $primaryExists) {
            StorageConnection::setAsPrimary($connection);
        }

        $this->syncWasabiFiles($user, $connection, $wasabiService);

        return redirect()->route('dashboard.documents.storage-connections')
            ->with('success', 'تم ربط Wasabi بنجاح! تم جلب ملفاتك.');
    }

    public function syncWasabi(Request $request, WasabiService $wasabiService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connection = StorageConnection::where('user_id', $user->id)
            ->where('provider', 'wasabi')
            ->where('is_active', true)
            ->where('is_primary', true)
            ->first();

        if (! $connection) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'لم يتم العثور على اتصال Wasabi النشط.');
        }

        $this->syncWasabiFiles($user, $connection, $wasabiService);

        return redirect()->route('dashboard.documents.index')
            ->with('success', 'تم مزامنة الملفات من Wasabi بنجاح.');
    }

    private function syncWasabiFiles($user, StorageConnection $connection, WasabiService $wasabiService): void
    {
        $credentials = $this->getWasabiCredentials($connection);
        if (! $credentials) {
            return;
        }

        $files = $wasabiService->listAllObjectsRecursive($credentials);

        $externalKeys = [];
        $keyToDocId = [];
        foreach ($files as $file) {
            $key = $file['key'];
            $externalKeys[] = $key;
            $parentKey = $file['parents'][0] ?? null;
            $isFolder = $file['isFolder'] ?? false;

            $mimeType = $isFolder ? 'application/x-wasabi-folder' : $this->inferMimeFromFileName($file['name']);

            $doc = UserDocument::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'storage_connection_id' => $connection->id,
                    'external_id' => $key,
                ],
                [
                    'user_id' => $user->id,
                    'storage_connection_id' => $connection->id,
                    'parent_id' => null,
                    'name' => $file['name'],
                    'original_name' => $file['name'],
                    'path' => $key,
                    'external_id' => $key,
                    'mime_type' => $mimeType,
                    'size' => (int) ($file['size'] ?? 0),
                    'provider' => 'wasabi',
                    'type' => $isFolder ? 'folder' : 'file',
                ]
            );
            $keyToDocId[$key] = ['doc_id' => $doc->id, 'parent_key' => $parentKey];
        }

        $rootPrefix = $credentials['prefix'] ?? '';

        foreach ($keyToDocId as $key => $info) {
            $parentKey = $info['parent_key'];
            if (! $parentKey || $parentKey === $rootPrefix) {
                continue;
            }
            $parentDocId = $keyToDocId[$parentKey]['doc_id'] ?? null;
            if ($parentDocId) {
                UserDocument::where('id', $info['doc_id'])->update(['parent_id' => $parentDocId]);
            }
        }

        $query = UserDocument::where('user_id', $user->id)
            ->where('storage_connection_id', $connection->id);
        if (empty($externalKeys)) {
            $query->delete();
        } else {
            $query->whereNotIn('external_id', $externalKeys)->delete();
        }
    }

    public function viewFile(Request $request, UserDocument $document, WasabiService $wasabiService)
    {
        $this->ensureWebUser();

        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($document->provider !== 'wasabi' || $document->isFolder()) {
            return redirect()->back()->with('error', 'لا يمكن فتح هذا العنصر.');
        }

        $connection = $document->storageConnection;
        if (! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال التخزين.');
        }

        $credentials = $this->getWasabiCredentials($connection);
        if (! $credentials) {
            return redirect()->back()->with('error', 'فشل في الحصول على بيانات الاتصال.');
        }

        $url = $wasabiService->getPresignedUrl($credentials, $document->external_id);
        if (! $url) {
            return redirect()->back()->with('error', 'فشل في إنشاء رابط التحميل.');
        }

        return redirect($url);
    }

    /**
     * Stream file content for embedding in img tags. Works for images from Google Drive and Wasabi.
     * For Wasabi, mime_type may be null (e.g. from sync) - we infer from file extension.
     */
    public function embedFile(Request $request, UserDocument $document, GoogleDriveService $driveService, WasabiService $wasabiService)
    {
        $this->ensureWebUser();

        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($document->isFolder()) {
            abort(404);
        }

        $mime = $document->mime_type ?? 'application/octet-stream';
        if (! str_starts_with($mime, 'image/')) {
            // Wasabi sync may not set mime_type - allow if extension suggests image
            if (! $document->looksLikeImageByExtension()) {
                abort(404);
            }
            $mime = $this->inferImageMimeFromExtension($document);
        }

        $connection = $document->storageConnection;
        if (! $connection) {
            abort(404);
        }

        $content = null;

        if ($connection->provider === 'google_drive') {
            $accessToken = $this->getDriveAccessToken($connection, $driveService);
            if (! $accessToken) {
                abort(404);
            }
            $content = $driveService->downloadFileContent($accessToken, $document->external_id, $document->mime_type);
        } elseif ($connection->provider === 'wasabi') {
            $credentials = $this->getWasabiCredentials($connection);
            if (! $credentials) {
                abort(404);
            }
            $content = $wasabiService->downloadFileContent($credentials, $document->external_id);
        }

        if (! $content) {
            abort(404);
        }

        return response($content)
            ->header('Content-Type', $mime)
            ->header('Cache-Control', 'private, max-age=3600');
    }

    private function inferImageMimeFromExtension(UserDocument $document): string
    {
        $name = $document->original_name ?? $document->name ?? $document->path ?? '';
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        return $this->getExtensionToMimeMap()[$ext] ?? 'image/jpeg';
    }

    private function inferMimeFromFileName(?string $fileName): ?string
    {
        if (! $fileName) {
            return null;
        }
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $map = $this->getExtensionToMimeMap();

        return $map[$ext] ?? null;
    }

    private function getExtensionToMimeMap(): array
    {
        return [
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
