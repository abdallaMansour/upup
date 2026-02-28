<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\StorageConnection;
use App\Models\UserDocument;
use App\Services\GoogleDriveService;
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

        $documents = UserDocument::where('user_id', $user->id)
            ->with('storageConnection')
            ->latest()
            ->paginate(15);

        $storageConnections = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->get();

        return view('dashboard.documents.index', compact('documents', 'storageConnections'));
    }

    public function storageConnections(Request $request)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connections = StorageConnection::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $connectedProviderKeys = StorageConnection::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('provider')
            ->toArray();

        return view('dashboard.documents.storage-connections', compact('connections', 'connectedProviderKeys'));
    }

    public function connectGoogleDrive(Request $request)
    {
        $this->ensureWebUser();

        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'لم يتم إعداد Google Drive. يرجى التواصل مع المسؤول.');
        }

        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/drive.readonly'])
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect();
    }

    public function callbackGoogleDrive(Request $request, GoogleDriveService $driveService)
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
                'root_folder_id' => null,
            ]
        );

        // Sync files from Google Drive
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
            ->first();

        if (! $connection) {
            return redirect()->route('dashboard.documents.storage-connections')
                ->with('error', 'لم يتم العثور على اتصال Google Drive.');
        }

        $this->syncGoogleDriveFiles($user, $connection, $driveService);

        return redirect()->route('dashboard.documents.index')
            ->with('success', 'تم مزامنة الملفات من Google Drive بنجاح.');
    }

    private function syncGoogleDriveFiles($user, StorageConnection $connection, GoogleDriveService $driveService): void
    {
        $credentials = $connection->credentials;
        $accessToken = null;

        if (isset($credentials['encrypted'])) {
            $decrypted = json_decode(Crypt::decryptString($credentials['encrypted']), true);
            $accessToken = $decrypted['access_token'] ?? null;
            $refreshToken = $decrypted['refresh_token'] ?? null;

            if (! $accessToken && $refreshToken) {
                $accessToken = $driveService->getAccessTokenFromRefreshToken($refreshToken);
            }
        }

        if (! $accessToken) {
            return;
        }

        $files = $driveService->listFiles($accessToken, $connection->root_folder_id);

        foreach ($files as $file) {
            UserDocument::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'storage_connection_id' => $connection->id,
                    'external_id' => $file['id'],
                ],
                [
                    'user_id' => $user->id,
                    'storage_connection_id' => $connection->id,
                    'name' => $file['name'],
                    'original_name' => $file['name'],
                    'path' => $file['id'],
                    'external_id' => $file['id'],
                    'mime_type' => $file['mimeType'] ?? null,
                    'size' => (int) ($file['size'] ?? 0),
                    'provider' => 'google_drive',
                ]
            );
        }
    }
}
