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
use App\Services\TranslationService;
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

        $subscription = $user->active_subscription;
        $maxPages = $subscription?->package?->max_pages ?? 1;
        $canAddPage = $stages->count() < $maxPages;

        return view('dashboard.my-pages.index', compact('stages', 'canAddPage'));
    }

    public function create(ChildhoodStageService $childhoodService)
    {
        $this->ensureWebUser();
        $user = request()->user();
        $subscription = $user->active_subscription;
        $maxPages = $subscription?->package?->max_pages ?? 1;
        $currentCount = UserChildhoodStage::forUser($user->id)->count();

        if ($currentCount >= $maxPages) {
            return redirect()->route('dashboard.my-pages.index')
                ->with('error', __('my_pages.max_pages_reached'));
        }

        $primaryConnection = $childhoodService->resolveStorageConnection($user);

        return view('dashboard.my-pages.create', [
            'stage' => null,
            'primaryConnection' => $primaryConnection,
        ]);
    }

    public function store(Request $request, ChildhoodStageService $childhoodService, GoogleDriveService $driveService, WasabiService $wasabiService, TranslationService $translationService)
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

        $subscription = $user->active_subscription;
        $maxPages = $subscription?->package?->max_pages ?? 1;
        $currentCount = UserChildhoodStage::forUser($user->id)->count();

        if ($currentCount >= $maxPages) {
            return redirect()->route('dashboard.my-pages.index')
                ->with('error', __('my_pages.max_pages_reached'));
        }

        $translatable = $translationService->prepareChildhoodStageTranslatable($validated);

        $stage = UserChildhoodStage::create(array_merge([
            'user_id' => $user->id,
            'is_public' => $request->boolean('is_public'),
            'birth_date' => $validated['birth_date'] ?? null,
            'birth_time' => $validated['birth_time'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'blood_type' => $validated['blood_type'] ?? null,
        ], $translatable));

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

    public function update(Request $request, UserChildhoodStage $stage, ChildhoodStageService $childhoodService, GoogleDriveService $driveService, WasabiService $wasabiService, TranslationService $translationService)
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

        $translatable = $translationService->prepareChildhoodStageTranslatable($validated);

        $stage->update(array_merge([
            'is_public' => $request->boolean('is_public'),
            'birth_date' => $validated['birth_date'] ?? null,
            'birth_time' => $validated['birth_time'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'blood_type' => $validated['blood_type'] ?? null,
        ], $translatable));

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

    public function showThemeLang(UserChildhoodStage $stage)
    {
        $this->ensureWebUser();
        $user = request()->user();
        if ($stage->user_id !== $user->id) {
            abort(403);
        }

        $lifeStage = $stage->life_stage;
        $childThemes = [
            ['id' => 'playfulRed', 'style' => 'background:linear-gradient(135deg, #e74c3c 0%, #ff9800 50%, #ffb74d 100%);', 'title' => 'Playful Red', 'dot' => '#e74c3c', 'badgeProducts' => '#e74c3c', 'badgeFollow' => '#fde8ea', 'badgeFollowText' => '#e74c3c'],
            ['id' => 'oceanBlue', 'style' => 'background:linear-gradient(135deg, #1e88e5 0%, #42a5f5 50%, #90caf9 100%);', 'title' => 'Ocean Blue', 'dot' => '#1e88e5', 'badgeProducts' => '#1e88e5', 'badgeFollow' => '#e8ecff', 'badgeFollowText' => '#1e88e5'],
            ['id' => 'forestGreen', 'style' => 'background:linear-gradient(135deg, #43a047 0%, #66bb6a 50%, #a5d6a7 100%);', 'title' => 'Forest Green', 'dot' => '#43a047', 'badgeProducts' => '#43a047', 'badgeFollow' => '#d8f3e0', 'badgeFollowText' => '#43a047'],
            ['id' => 'sunsetOrange', 'style' => 'background:linear-gradient(135deg, #ff6f00 0%, #ff9800 50%, #ffcc80 100%);', 'title' => 'Sunset Orange', 'dot' => '#ff6f00', 'badgeProducts' => '#ff6f00', 'badgeFollow' => '#fff0e0', 'badgeFollowText' => '#ff6f00'],
            ['id' => 'purpleDreams', 'style' => 'background:linear-gradient(135deg, #7b1fa2 0%, #ab47bc 50%, #ce93d8 100%);', 'title' => 'Purple Dreams', 'dot' => '#7b1fa2', 'badgeProducts' => '#7b1fa2', 'badgeFollow' => '#f0e4ff', 'badgeFollowText' => '#7b1fa2'],
            ['id' => 'candyPink', 'style' => 'background:linear-gradient(135deg, #ec407a 0%, #f48fb1 50%, #f8bbd0 100%);', 'title' => 'Candy Pink', 'dot' => '#ec407a', 'badgeProducts' => '#ec407a', 'badgeFollow' => '#ffe0f0', 'badgeFollowText' => '#ec407a'],
            ['id' => 'skyBlue', 'style' => 'background:linear-gradient(135deg, #039be5 0%, #4fc3f7 50%, #b3e5fc 100%);', 'title' => 'Sky Blue', 'dot' => '#039be5', 'badgeProducts' => '#039be5', 'badgeFollow' => '#caf0f8', 'badgeFollowText' => '#039be5'],
            ['id' => 'sunshineYellow', 'style' => 'background:linear-gradient(135deg, #fbc02d 0%, #fdd835 50%, #fff59d 100%);', 'title' => 'Sunshine Yellow', 'dot' => '#fbc02d', 'badgeProducts' => '#fbc02d', 'badgeFollow' => '#fff9e6', 'badgeFollowText' => '#c49000'],
            ['id' => 'berryPurple', 'style' => 'background:linear-gradient(135deg, #8e24aa 0%, #ba68c8 50%, #e1bee7 100%);', 'title' => 'Berry Purple', 'dot' => '#8e24aa', 'badgeProducts' => '#8e24aa', 'badgeFollow' => '#e1bee7', 'badgeFollowText' => '#8e24aa'],
            ['id' => 'mintFresh', 'style' => 'background:linear-gradient(135deg, #26a69a 0%, #4db6ac 50%, #b2dfdb 100%);', 'title' => 'Mint Fresh', 'dot' => '#26a69a', 'badgeProducts' => '#26a69a', 'badgeFollow' => '#d9fdf3', 'badgeFollowText' => '#028a65'],
        ];
        $teenThemes = [
            ['id' => 'neon', 'style' => 'background:linear-gradient(135deg, #A855F7 0%, #06B6D4 50%, #F472B6 100%);', 'title' => 'Neon', 'dot' => '#A855F7', 'badgeProducts' => '#A855F7', 'badgeFollow' => '#f0e4ff', 'badgeFollowText' => '#A855F7'],
            ['id' => 'electric', 'style' => 'background:linear-gradient(135deg, #3B82F6 0%, #60A5FA 50%, #22C55E 100%);', 'title' => 'Electric', 'dot' => '#3B82F6', 'badgeProducts' => '#3B82F6', 'badgeFollow' => '#dbeafe', 'badgeFollowText' => '#3B82F6'],
            ['id' => 'creative', 'style' => 'background:linear-gradient(135deg, #F97316 0%, #A855F7 50%, #EC4899 100%);', 'title' => 'Creative', 'dot' => '#F97316', 'badgeProducts' => '#F97316', 'badgeFollow' => '#fff0e0', 'badgeFollowText' => '#F97316'],
            ['id' => 'cosmic', 'style' => 'background:linear-gradient(135deg, #4C1D95 0%, #7C3AED 50%, #22D3EE 100%);', 'title' => 'Cosmic', 'dot' => '#4C1D95', 'badgeProducts' => '#4C1D95', 'badgeFollow' => '#ede9fe', 'badgeFollowText' => '#4C1D95'],
        ];
        $adultThemes = [
            ['id' => 'royalGold', 'style' => 'background:linear-gradient(135deg, #D4AF37 0%, #111827 100%);', 'title' => 'Royal Gold', 'dot' => '#D4AF37', 'badgeProducts' => '#D4AF37', 'badgeFollow' => '#fef9e7', 'badgeFollowText' => '#b8860b'],
            ['id' => 'platinumSilver', 'style' => 'background:linear-gradient(135deg, #C0C0C0 0%, #0F172A 100%);', 'title' => 'Platinum Silver', 'dot' => '#C0C0C0', 'badgeProducts' => '#94a3b8', 'badgeFollow' => '#f1f5f9', 'badgeFollowText' => '#64748b'],
            ['id' => 'roseGold', 'style' => 'background:linear-gradient(135deg, #B76E79 0%, #1A0A0F 100%);', 'title' => 'Rose Gold', 'dot' => '#B76E79', 'badgeProducts' => '#B76E79', 'badgeFollow' => '#fce7e9', 'badgeFollowText' => '#B76E79'],
            ['id' => 'indigoNight', 'style' => 'background:linear-gradient(135deg, #6366F1 0%, #020617 100%);', 'title' => 'Indigo Night', 'dot' => '#6366F1', 'badgeProducts' => '#6366F1', 'badgeFollow' => '#e0e7ff', 'badgeFollowText' => '#6366F1'],
        ];
        $themes = match ($lifeStage) {
            'child' => $childThemes,
            'teenager' => $teenThemes,
            'adult' => $adultThemes,
            default => $childThemes,
        };

        return view('dashboard.my-pages.theme-lang', compact('stage', 'themes', 'lifeStage'));
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

        return redirect()->route('dashboard.my-pages.theme-lang', $stage)->with('success', __('my_pages.theme_lang_saved'));
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
