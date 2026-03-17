<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ChildhoodStagePermission;
use App\Models\UserChildhoodMedia;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StagePermissionGrantedNotification;
use App\Services\ChildhoodStageService;
use App\Services\GoogleDriveService;
use App\Services\WasabiService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MyPagesController extends Controller
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

        $stages = UserChildhoodStage::forUser($user->id)
            ->with(['coverImageDocument', 'firstPhotoDocument'])
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.my-pages.index', compact('stages'));
    }

    public function create(ChildhoodStageService $childhoodService)
    {
        $this->ensureWebUser();
        $user = request()->user();
        $primaryConnection = $childhoodService->resolveStorageConnection($user);

        return view('dashboard.my-pages.create', [
            'stage' => null,
            'primaryConnection' => $primaryConnection,
        ]);
    }

    public function store(Request $request, ChildhoodStageService $childhoodService, GoogleDriveService $driveService, WasabiService $wasabiService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connection = $childhoodService->resolveStorageConnection($user);
        $hasFiles = $request->hasFile('footprint') || $request->hasFile('first_photo') || $request->hasFile('first_video')
            || $request->hasFile('first_gift') || $request->hasFile('cover_image') || $request->hasFile('other_photos') || $request->hasFile('other_videos');

        if ($hasFiles && ! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً لرفع الملفات.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'is_public' => ['nullable', 'boolean'],
            'naming_reason' => ['nullable', 'string', 'max:1000'],
            'birth_date' => ['nullable', 'date'],
            'birth_time' => ['nullable', 'date_format:H:i'],
            'gender' => ['nullable', 'in:male,female'],
            'height' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'blood_type' => ['nullable', 'string', 'max:50'],
            'doctor' => ['nullable', 'string', 'max:255'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'footprint' => ['nullable', 'file', 'image', 'max:10240'],
            'first_photo' => ['nullable', 'file', 'image', 'max:10240'],
            'cover_image' => ['nullable', 'file', 'image', 'max:10240'],
            'first_video' => ['nullable', 'file', 'mimetypes:video/*', 'max:51200'],
            'first_gift' => ['nullable', 'file', 'max:51200'],
            'other_photos' => ['nullable', 'array'],
            'other_photos.*' => ['file', 'image', 'max:10240'],
            'other_videos' => ['nullable', 'array'],
            'other_videos.*' => ['file', 'mimetypes:video/*', 'max:51200'],
        ]);

        $stage = UserChildhoodStage::create([
            'user_id' => $user->id,
            'is_public' => $request->boolean('is_public'),
            'name' => $validated['name'],
            'mother_name' => $validated['mother_name'],
            'father_name' => $validated['father_name'],
            'naming_reason' => $validated['naming_reason'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'birth_time' => $validated['birth_time'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'blood_type' => $validated['blood_type'] ?? null,
            'doctor' => $validated['doctor'] ?? null,
            'birth_place' => $validated['birth_place'] ?? null,
        ]);

        if ($connection) {
            $childhoodFolder = $childhoodService->getOrCreateChildhoodFolderForStage($user, $connection, $stage);
            if ($childhoodFolder) {
                $subfolders = $this->getOrCreateSubfolders($childhoodService, $user, $connection, $childhoodFolder);

                if ($request->hasFile('footprint')) {
                    $this->handleSingleFile($request->file('footprint'), 'footprint_document_id', $stage, $subfolders['footprint'], $childhoodService, $user, $connection);
                }
                if ($request->hasFile('first_photo')) {
                    $this->handleSingleFile($request->file('first_photo'), 'first_photo_document_id', $stage, $subfolders['first_photo'], $childhoodService, $user, $connection);
                }
                if ($request->hasFile('cover_image')) {
                    $this->handleSingleFile($request->file('cover_image'), 'cover_image_document_id', $stage, $subfolders['cover'], $childhoodService, $user, $connection);
                }
                if ($request->hasFile('first_video')) {
                    $this->handleSingleFile($request->file('first_video'), 'first_video_document_id', $stage, $subfolders['first_video'], $childhoodService, $user, $connection);
                }
                if ($request->hasFile('first_gift')) {
                    $this->handleSingleFile($request->file('first_gift'), 'first_gift_document_id', $stage, $subfolders['first_gift'], $childhoodService, $user, $connection);
                }

                if ($request->hasFile('other_photos')) {
                    foreach ($request->file('other_photos') as $file) {
                        $doc = $childhoodService->uploadFile($file, $subfolders['other_photos'], $user, $connection);
                        if ($doc) {
                            UserChildhoodMedia::create([
                                'user_childhood_stage_id' => $stage->id,
                                'user_document_id' => $doc->id,
                                'media_type' => 'other_photo',
                                'sort_order' => $stage->otherPhotos()->count(),
                            ]);
                        }
                    }
                }
                if ($request->hasFile('other_videos')) {
                    foreach ($request->file('other_videos') as $file) {
                        $doc = $childhoodService->uploadFile($file, $subfolders['other_videos'], $user, $connection);
                        if ($doc) {
                            UserChildhoodMedia::create([
                                'user_childhood_stage_id' => $stage->id,
                                'user_document_id' => $doc->id,
                                'media_type' => 'other_video',
                                'sort_order' => $stage->otherVideos()->count(),
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('dashboard.my-pages.index')->with('success', 'تم إنشاء مرحلة الطفولة بنجاح.');
    }

    public function edit(UserChildhoodStage $stage, ChildhoodStageService $childhoodService)
    {
        $this->ensureWebUser();
        $user = request()->user();
        if ($stage->user_id !== $user->id) {
            abort(403);
        }
        $primaryConnection = $childhoodService->resolveStorageConnection($user);

        return view('dashboard.my-pages.edit', [
            'stage' => $stage,
            'primaryConnection' => $primaryConnection,
        ]);
    }

    public function update(Request $request, UserChildhoodStage $stage, ChildhoodStageService $childhoodService, GoogleDriveService $driveService, WasabiService $wasabiService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($stage->user_id !== $user->id) {
            abort(403);
        }

        $connection = $childhoodService->resolveStorageConnection($user);
        $hasFiles = $request->hasFile('footprint') || $request->hasFile('first_photo') || $request->hasFile('first_video')
            || $request->hasFile('first_gift') || $request->hasFile('cover_image') || $request->hasFile('other_photos') || $request->hasFile('other_videos');

        if ($hasFiles && ! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً لرفع الملفات.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'is_public' => ['nullable', 'boolean'],
            'naming_reason' => ['nullable', 'string', 'max:1000'],
            'birth_date' => ['nullable', 'date'],
            'birth_time' => ['nullable', 'date_format:H:i'],
            'gender' => ['nullable', 'in:male,female'],
            'height' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'blood_type' => ['nullable', 'string', 'max:50'],
            'doctor' => ['nullable', 'string', 'max:255'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'footprint' => ['nullable', 'file', 'image', 'max:10240'],
            'first_photo' => ['nullable', 'file', 'image', 'max:10240'],
            'cover_image' => ['nullable', 'file', 'image', 'max:10240'],
            'first_video' => ['nullable', 'file', 'mimetypes:video/*', 'max:51200'],
            'first_gift' => ['nullable', 'file', 'max:51200'],
            'other_photos' => ['nullable', 'array'],
            'other_photos.*' => ['file', 'image', 'max:10240'],
            'other_videos' => ['nullable', 'array'],
            'other_videos.*' => ['file', 'mimetypes:video/*', 'max:51200'],
        ]);

        $stage->update([
            'is_public' => $request->boolean('is_public'),
            'name' => $validated['name'],
            'mother_name' => $validated['mother_name'],
            'father_name' => $validated['father_name'],
            'naming_reason' => $validated['naming_reason'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'birth_time' => $validated['birth_time'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'blood_type' => $validated['blood_type'] ?? null,
            'doctor' => $validated['doctor'] ?? null,
            'birth_place' => $validated['birth_place'] ?? null,
        ]);

        if ($connection) {
            $childhoodFolder = $childhoodService->getOrCreateChildhoodFolderForStage($user, $connection, $stage);
            if ($childhoodFolder) {
                $subfolders = $this->getOrCreateSubfolders($childhoodService, $user, $connection, $childhoodFolder);

                if ($request->hasFile('footprint')) {
                    $this->handleSingleFile($request->file('footprint'), 'footprint_document_id', $stage, $subfolders['footprint'], $childhoodService, $user, $connection);
                }
                if ($request->hasFile('first_photo')) {
                    $this->handleSingleFile($request->file('first_photo'), 'first_photo_document_id', $stage, $subfolders['first_photo'], $childhoodService, $user, $connection);
                }
                if ($request->hasFile('cover_image')) {
                    $this->handleSingleFile($request->file('cover_image'), 'cover_image_document_id', $stage, $subfolders['cover'], $childhoodService, $user, $connection);
                }
                if ($request->hasFile('first_video')) {
                    $this->handleSingleFile($request->file('first_video'), 'first_video_document_id', $stage, $subfolders['first_video'], $childhoodService, $user, $connection);
                }
                if ($request->hasFile('first_gift')) {
                    $this->handleSingleFile($request->file('first_gift'), 'first_gift_document_id', $stage, $subfolders['first_gift'], $childhoodService, $user, $connection);
                }

                if ($request->hasFile('other_photos')) {
                    foreach ($request->file('other_photos') as $file) {
                        $doc = $childhoodService->uploadFile($file, $subfolders['other_photos'], $user, $connection);
                        if ($doc) {
                            UserChildhoodMedia::create([
                                'user_childhood_stage_id' => $stage->id,
                                'user_document_id' => $doc->id,
                                'media_type' => 'other_photo',
                                'sort_order' => $stage->otherPhotos()->count(),
                            ]);
                        }
                    }
                }
                if ($request->hasFile('other_videos')) {
                    foreach ($request->file('other_videos') as $file) {
                        $doc = $childhoodService->uploadFile($file, $subfolders['other_videos'], $user, $connection);
                        if ($doc) {
                            UserChildhoodMedia::create([
                                'user_childhood_stage_id' => $stage->id,
                                'user_document_id' => $doc->id,
                                'media_type' => 'other_video',
                                'sort_order' => $stage->otherVideos()->count(),
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('dashboard.my-pages.index')->with('success', 'تم تحديث بيانات مرحلة الطفولة بنجاح.');
    }

    public function documents(UserChildhoodStage $stage)
    {
        $this->ensureWebUser();
        $user = request()->user();
        if ($stage->user_id !== $user->id) {
            abort(403);
        }

        return view('dashboard.my-pages.documents', compact('stage'));
    }

    public function updateEducationSections(Request $request, UserChildhoodStage $stage)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($stage->user_id !== $user->id) {
            abort(403);
        }

        $allowed = ['height_weight', 'achievements', 'voices', 'drawings', 'visits', 'injuries', 'other_events'];
        $sections = $request->input('education_sections', []);
        if (! is_array($sections)) {
            $sections = [];
        }
        $sections = array_values(array_intersect($sections, $allowed));

        $stage->update(['education_linked_sections' => $sections]);

        return redirect()->route('dashboard.my-pages.documents', $stage)->with('success', 'تم حفظ إعدادات المراحل التعليمية بنجاح.');
    }

    public function storePermission(Request $request, UserChildhoodStage $stage)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($stage->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'grantee_name' => ['required', 'string', 'max:255'],
            'grantee_email' => ['required', 'email'],
            'expires_at' => ['required', 'date'],
            'expires_time' => ['nullable', 'date_format:H:i'],
        ], [], [
            'grantee_name' => 'الاسم',
            'grantee_email' => 'البريد الإلكتروني',
            'expires_at' => 'تاريخ الانتهاء',
            'expires_time' => 'وقت الانتهاء',
        ]);

        $expiresAt = \Carbon\Carbon::parse($validated['expires_at'] . ' ' . ($validated['expires_time'] ?? '23:59'));
        if ($expiresAt->isPast()) {
            return redirect()->back()->withErrors(['expires_at' => 'يجب أن يكون تاريخ الانتهاء في المستقبل.'])->withInput();
        }

        $pin = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $permission = ChildhoodStagePermission::create([
            'user_childhood_stage_id' => $stage->id,
            'grantee_name' => $validated['grantee_name'],
            'grantee_email' => $validated['grantee_email'],
            'pin_hash' => Hash::make($pin),
            'expires_at' => $expiresAt,
        ]);

        Notification::route('mail', $validated['grantee_email'])
            ->notify(new StagePermissionGrantedNotification(
                $stage,
                $pin,
                $validated['grantee_name'],
                $expiresAt->format('Y-m-d H:i')
            ));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال البريد بنجاح.',
            ]);
        }

        return redirect()->back()->with('success', 'تم إرسال البريد بنجاح.');
    }

    public function updateThemeAndLang(Request $request, UserChildhoodStage $stage)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($stage->user_id !== $user->id) {
            abort(403);
        }

        $lifeStage = $stage->life_stage;
        $allowedThemes = match ($lifeStage) {
            'child' => ['playfulRed', 'oceanBlue', 'forestGreen', 'sunsetOrange', 'purpleDreams', 'candyPink', 'skyBlue', 'sunshineYellow', 'berryPurple', 'mintFresh'],
            'teenager' => ['neon', 'electric', 'creative', 'cosmic'],
            'adult' => ['royalGold', 'platinumSilver', 'roseGold', 'indigoNight'],
            default => [],
        };

        $themeRules = ['nullable', 'string', 'max:50'];
        if (! empty($allowedThemes)) {
            $themeRules[] = Rule::in(array_merge($allowedThemes, ['']));
        }

        $validated = $request->validate([
            'theme' => $themeRules,
            'default_language' => ['nullable', 'string', 'in:ar,en'],
        ]);

        $stage->update([
            'theme' => ! empty($validated['theme']) ? $validated['theme'] : null,
            'default_language' => ! empty($validated['default_language']) ? $validated['default_language'] : null,
        ]);

        return redirect()->back()->with('success', __('my_pages.theme_lang_saved'));
    }

    public function destroy(Request $request, UserChildhoodStage $stage)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($stage->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'name_confirmation' => ['required', 'string', 'in:'.$stage->name],
        ], [
            'name_confirmation.in' => 'اسم التأكيد غير مطابق لاسم المرحلة.',
        ]);

        $stage->delete();

        return redirect()->route('dashboard.my-pages.index')->with('success', 'تم حذف مرحلة الطفولة بنجاح.');
    }

    private function getOrCreateSubfolders(ChildhoodStageService $childhoodService, $user, $connection, $childhoodFolder): array
    {
        $names = ['footprint', 'first_photo', 'cover', 'first_video', 'first_gift', 'other_photos', 'other_videos'];
        $folders = [];
        foreach ($names as $name) {
            $folders[$name] = $childhoodService->getOrCreateSubfolder($user, $connection, $childhoodFolder, $name);
        }

        return $folders;
    }

    private function handleSingleFile($file, string $column, UserChildhoodStage $stage, $subfolder, ChildhoodStageService $childhoodService, $user, $connection): void
    {
        if (! $subfolder) {
            return;
        }
        $oldDocId = $stage->{$column};
        if ($oldDocId) {
            $oldDoc = UserDocument::find($oldDocId);
            if ($oldDoc && $oldDoc->user_id === $user->id) {
                $childhoodService->deleteDocument($oldDoc, $connection);
            }
        }
        $doc = $childhoodService->uploadFile($file, $subfolder, $user, $connection);
        if ($doc) {
            $stage->update([$column => $doc->id]);
        }
    }
}
