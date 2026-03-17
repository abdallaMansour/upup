<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserAchievement;
use App\Models\UserAchievementMedia;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use App\Services\AchievementService;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    private function ensureWebUser(): void
    {
        if (! auth('web')->check()) {
            abort(403, 'هذه الصفحة متاحة للمستخدمين فقط.');
        }
    }

    public function index(Request $request, AchievementService $achievementService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $stageId = $request->query('stage');
        $query = UserAchievement::forUser($user->id)->forStage($stageId ? (int) $stageId : null);
        $achievements = $query->with(['certificateImageDocument', 'photos.userDocument', 'videos.userDocument'])
            ->orderByDesc('record_date')
            ->orderByDesc('record_time')
            ->paginate(15)
            ->withQueryString();

        $primaryConnection = $achievementService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.achievements.index', compact('achievements', 'primaryConnection', 'stage'));
    }

    public function create(Request $request, AchievementService $achievementService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        $stageId = $request->query('stage');
        $primaryConnection = $achievementService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.achievements.create', compact('primaryConnection', 'stage'));
    }

    public function store(Request $request, AchievementService $achievementService, TranslationService $translationService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connection = $achievementService->resolveStorageConnection($user);
        $hasFiles = $request->hasFile('certificate_image') || $request->hasFile('photos') || $request->hasFile('videos');

        if ($hasFiles && ! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً لرفع الملفات.');
        }

        $validated = $request->validate([
            'record_date' => ['required', 'date'],
            'record_time' => ['nullable', 'date_format:H:i'],
            'type' => ['required', 'in:honor,success,championship,volunteering,appreciation,competition'],
            'title' => ['required', 'string', 'max:255'],
            'place' => ['nullable', 'string', 'max:255'],
            'academic_year' => ['nullable', 'string', 'max:100'],
            'school' => ['nullable', 'string', 'max:255'],
            'certificate_image' => ['nullable', 'file', 'image', 'max:10240'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['file', 'image', 'max:10240'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['file', 'mimetypes:video/*', 'max:51200'],
        ]);

        $stageId = $request->query('stage');
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        $translatable = $translationService->prepareAchievementTranslatable($validated);

        $achievement = UserAchievement::create(array_merge([
            'user_id' => $user->id,
            'user_childhood_stage_id' => $stage?->id,
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'type' => $validated['type'],
            'show_in_education' => $request->boolean('show_in_education'),
        ], $translatable));

        if ($connection) {
            $rootFolder = $achievementService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $certFolder = $achievementService->getOrCreateSubfolder($user, $connection, $rootFolder, 'certificates');
                $photosFolder = $achievementService->getOrCreateSubfolder($user, $connection, $rootFolder, 'photos');
                $videosFolder = $achievementService->getOrCreateSubfolder($user, $connection, $rootFolder, 'videos');

                if ($request->hasFile('certificate_image') && $certFolder) {
                    $doc = $achievementService->uploadFile($request->file('certificate_image'), $certFolder, $user, $connection);
                    if ($doc) {
                        $achievement->update(['certificate_image_document_id' => $doc->id]);
                    }
                }
                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $file) {
                        $doc = $achievementService->uploadFile($file, $photosFolder, $user, $connection);
                        if ($doc) {
                            UserAchievementMedia::create([
                                'user_achievement_id' => $achievement->id,
                                'user_document_id' => $doc->id,
                                'media_type' => 'photo',
                                'sort_order' => $achievement->photos()->count(),
                            ]);
                        }
                    }
                }
                if ($request->hasFile('videos')) {
                    foreach ($request->file('videos') as $file) {
                        $doc = $achievementService->uploadFile($file, $videosFolder, $user, $connection);
                        if ($doc) {
                            UserAchievementMedia::create([
                                'user_achievement_id' => $achievement->id,
                                'user_document_id' => $doc->id,
                                'media_type' => 'video',
                                'sort_order' => $achievement->videos()->count(),
                            ]);
                        }
                    }
                }
            }
        }

        $redirect = $stage ? redirect()->route('dashboard.achievements.index', ['stage' => $stage->id]) : redirect()->route('dashboard.achievements.index');
        return $redirect->with('success', 'تم إضافة الإنجاز بنجاح.');
    }

    public function edit(UserAchievement $achievement, AchievementService $achievementService)
    {
        $this->ensureWebUser();
        if ($achievement->user_id !== request()->user()->id) {
            abort(403);
        }
        $primaryConnection = $achievementService->resolveStorageConnection(request()->user());

        return view('dashboard.achievements.edit', compact('achievement', 'primaryConnection'));
    }

    public function update(Request $request, UserAchievement $achievement, AchievementService $achievementService, TranslationService $translationService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($achievement->user_id !== $user->id) {
            abort(403);
        }

        $connection = $achievementService->resolveStorageConnection($user);
        $hasFiles = $request->hasFile('certificate_image') || $request->hasFile('photos') || $request->hasFile('videos');

        if ($hasFiles && ! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً لرفع الملفات.');
        }

        $validated = $request->validate([
            'record_date' => ['required', 'date'],
            'record_time' => ['nullable', 'date_format:H:i'],
            'type' => ['required', 'in:honor,success,championship,volunteering,appreciation,competition'],
            'title' => ['required', 'string', 'max:255'],
            'place' => ['nullable', 'string', 'max:255'],
            'academic_year' => ['nullable', 'string', 'max:100'],
            'school' => ['nullable', 'string', 'max:255'],
            'certificate_image' => ['nullable', 'file', 'image', 'max:10240'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['file', 'image', 'max:10240'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['file', 'mimetypes:video/*', 'max:51200'],
        ]);

        $translatable = $translationService->prepareAchievementTranslatable($validated);

        $achievement->update(array_merge([
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'type' => $validated['type'],
            'show_in_education' => $request->boolean('show_in_education'),
        ], $translatable));

        if ($connection) {
            $rootFolder = $achievementService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $certFolder = $achievementService->getOrCreateSubfolder($user, $connection, $rootFolder, 'certificates');
                $photosFolder = $achievementService->getOrCreateSubfolder($user, $connection, $rootFolder, 'photos');
                $videosFolder = $achievementService->getOrCreateSubfolder($user, $connection, $rootFolder, 'videos');

                if ($request->hasFile('certificate_image') && $certFolder) {
                    if ($achievement->certificate_image_document_id) {
                        $oldDoc = UserDocument::find($achievement->certificate_image_document_id);
                        if ($oldDoc && $oldDoc->user_id === $user->id) {
                            $achievementService->deleteDocument($oldDoc, $connection);
                        }
                    }
                    $doc = $achievementService->uploadFile($request->file('certificate_image'), $certFolder, $user, $connection);
                    if ($doc) {
                        $achievement->update(['certificate_image_document_id' => $doc->id]);
                    }
                }
                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $file) {
                        $doc = $achievementService->uploadFile($file, $photosFolder, $user, $connection);
                        if ($doc) {
                            UserAchievementMedia::create([
                                'user_achievement_id' => $achievement->id,
                                'user_document_id' => $doc->id,
                                'media_type' => 'photo',
                                'sort_order' => $achievement->photos()->count(),
                            ]);
                        }
                    }
                }
                if ($request->hasFile('videos')) {
                    foreach ($request->file('videos') as $file) {
                        $doc = $achievementService->uploadFile($file, $videosFolder, $user, $connection);
                        if ($doc) {
                            UserAchievementMedia::create([
                                'user_achievement_id' => $achievement->id,
                                'user_document_id' => $doc->id,
                                'media_type' => 'video',
                                'sort_order' => $achievement->videos()->count(),
                            ]);
                        }
                    }
                }
            }
        }

        $stage = $achievement->childhoodStage;
        $redirect = $stage ? redirect()->route('dashboard.achievements.index', ['stage' => $stage->id]) : redirect()->route('dashboard.achievements.index');
        return $redirect->with('success', 'تم تحديث الإنجاز بنجاح.');
    }

    public function destroy(Request $request, UserAchievement $achievement, AchievementService $achievementService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($achievement->user_id !== $user->id) {
            abort(403);
        }

        $connection = $achievementService->resolveStorageConnection($user);
        if ($connection) {
            if ($achievement->certificate_image_document_id) {
                $doc = UserDocument::find($achievement->certificate_image_document_id);
                if ($doc && $doc->user_id === $user->id) {
                    $achievementService->deleteDocument($doc, $connection);
                }
            }
            foreach ($achievement->mediaItems as $media) {
                $doc = $media->userDocument;
                if ($doc && $doc->user_id === $user->id) {
                    $achievementService->deleteDocument($doc, $connection);
                }
            }
        }

        $achievement->delete();

        $stage = $achievement->childhoodStage;
        $redirect = $stage ? redirect()->route('dashboard.achievements.index', ['stage' => $stage->id]) : redirect()->route('dashboard.achievements.index');
        return $redirect->with('success', 'تم حذف الإنجاز بنجاح.');
    }
}
