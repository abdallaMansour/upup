<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use App\Models\UserVoice;
use App\Services\TranslationService;
use App\Services\VoiceService;
use Illuminate\Http\Request;

class VoiceController extends Controller
{
    private function ensureWebUser(): void
    {
        if (! auth('web')->check()) {
            abort(403, 'هذه الصفحة متاحة للمستخدمين فقط.');
        }
    }

    public function index(Request $request, VoiceService $voiceService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $stageId = $request->query('stage');
        $query = UserVoice::forUser($user->id)->forStage($stageId ? (int) $stageId : null);
        $voices = $query->with('audioDocument')
            ->orderByDesc('record_date')
            ->orderByDesc('record_time')
            ->paginate(15)
            ->withQueryString();

        $primaryConnection = $voiceService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.voices.index', compact('voices', 'primaryConnection', 'stage'));
    }

    public function create(Request $request, VoiceService $voiceService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        $stageId = $request->query('stage');
        $primaryConnection = $voiceService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.voices.create', compact('primaryConnection', 'stage'));
    }

    public function store(Request $request, VoiceService $voiceService, TranslationService $translationService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connection = $voiceService->resolveStorageConnection($user);
        if ($request->hasFile('audio') && ! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً لرفع الملفات.');
        }

        $validated = $request->validate([
            'record_date' => ['required', 'date'],
            'record_time' => ['nullable', 'date_format:H:i'],
            'title' => ['required', 'string', 'max:255'],
            'other_info' => ['nullable', 'string', 'max:2000'],
            'audio' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/wav,audio/ogg,audio/m4a,audio/x-m4a,audio/webm', 'max:51200'],
        ]);

        $stageId = $request->query('stage');
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        $translatable = $translationService->prepareTitleOtherInfoTranslatable($validated);

        $voice = UserVoice::create(array_merge([
            'user_id' => $user->id,
            'user_childhood_stage_id' => $stage?->id,
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'show_in_education' => $request->boolean('show_in_education'),
        ], $translatable));

        if ($connection && $request->hasFile('audio')) {
            $rootFolder = $voiceService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $voiceService->uploadFile($request->file('audio'), $rootFolder, $user, $connection);
                if ($doc) {
                    $voice->update(['audio_document_id' => $doc->id]);
                }
            }
        }

        $redirect = $stage ? redirect()->route('dashboard.voices.index', ['stage' => $stage->id]) : redirect()->route('dashboard.voices.index');
        return $redirect->with('success', 'تم إضافة الصوت بنجاح.');
    }

    public function edit(UserVoice $voice, VoiceService $voiceService)
    {
        $this->ensureWebUser();
        if ($voice->user_id !== request()->user()->id) {
            abort(403);
        }
        $primaryConnection = $voiceService->resolveStorageConnection(request()->user());

        return view('dashboard.voices.edit', compact('voice', 'primaryConnection'));
    }

    public function update(Request $request, UserVoice $voice, VoiceService $voiceService, TranslationService $translationService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($voice->user_id !== $user->id) {
            abort(403);
        }

        $connection = $voiceService->resolveStorageConnection($user);
        if ($request->hasFile('audio') && ! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً لرفع الملفات.');
        }

        $validated = $request->validate([
            'record_date' => ['required', 'date'],
            'record_time' => ['nullable', 'date_format:H:i'],
            'title' => ['required', 'string', 'max:255'],
            'other_info' => ['nullable', 'string', 'max:2000'],
            'audio' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/wav,audio/ogg,audio/m4a,audio/x-m4a,audio/webm', 'max:51200'],
        ]);

        $translatable = $translationService->prepareTitleOtherInfoTranslatable($validated);

        $voice->update(array_merge([
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'show_in_education' => $request->boolean('show_in_education'),
        ], $translatable));

        if ($connection && $request->hasFile('audio')) {
            if ($voice->audio_document_id) {
                $oldDoc = UserDocument::find($voice->audio_document_id);
                if ($oldDoc && $oldDoc->user_id === $user->id) {
                    $voiceService->deleteDocument($oldDoc, $connection);
                }
            }
            $rootFolder = $voiceService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $voiceService->uploadFile($request->file('audio'), $rootFolder, $user, $connection);
                if ($doc) {
                    $voice->update(['audio_document_id' => $doc->id]);
                }
            }
        }

        $stage = $voice->childhoodStage;
        $redirect = $stage ? redirect()->route('dashboard.voices.index', ['stage' => $stage->id]) : redirect()->route('dashboard.voices.index');
        return $redirect->with('success', 'تم تحديث الصوت بنجاح.');
    }

    public function destroy(Request $request, UserVoice $voice, VoiceService $voiceService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($voice->user_id !== $user->id) {
            abort(403);
        }

        $connection = $voiceService->resolveStorageConnection($user);
        if ($connection && $voice->audio_document_id) {
            $doc = UserDocument::find($voice->audio_document_id);
            if ($doc && $doc->user_id === $user->id) {
                $voiceService->deleteDocument($doc, $connection);
            }
        }

        $voice->delete();

        $stage = $voice->childhoodStage;
        $redirect = $stage ? redirect()->route('dashboard.voices.index', ['stage' => $stage->id]) : redirect()->route('dashboard.voices.index');
        return $redirect->with('success', 'تم حذف الصوت بنجاح.');
    }
}
