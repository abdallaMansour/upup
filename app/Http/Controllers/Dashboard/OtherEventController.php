<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use App\Models\UserOtherEvent;
use App\Services\OtherEventService;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class OtherEventController extends Controller
{
    private function ensureWebUser(): void
    {
        if (! auth('web')->check()) {
            abort(403, 'هذه الصفحة متاحة للمستخدمين فقط.');
        }
    }

    public function index(Request $request, OtherEventService $otherEventService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $stageId = $request->query('stage');
        $query = UserOtherEvent::forUser($user->id)->forStage($stageId ? (int) $stageId : null);
        $otherEvents = $query->with('mediaDocument')
            ->orderByDesc('record_date')
            ->orderByDesc('record_time')
            ->paginate(15)
            ->withQueryString();

        $primaryConnection = $otherEventService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.other-events.index', compact('otherEvents', 'primaryConnection', 'stage'));
    }

    public function create(Request $request, OtherEventService $otherEventService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        $stageId = $request->query('stage');
        $primaryConnection = $otherEventService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.other-events.create', compact('primaryConnection', 'stage'));
    }

    public function store(Request $request, OtherEventService $otherEventService, TranslationService $translationService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connection = $otherEventService->resolveStorageConnection($user);
        if ($request->hasFile('media') && ! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً لرفع الملفات.');
        }

        $validated = $request->validate([
            'record_date' => ['required', 'date'],
            'record_time' => ['nullable', 'date_format:H:i'],
            'title' => ['required', 'string', 'max:255'],
            'other_info' => ['nullable', 'string', 'max:2000'],
            'media' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime',
                'max:51200',
            ],
        ]);

        $stageId = $request->query('stage');
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        $translatable = $translationService->prepareTitleOtherInfoTranslatable($validated);

        $otherEvent = UserOtherEvent::create(array_merge([
            'user_id' => $user->id,
            'user_childhood_stage_id' => $stage?->id,
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'show_in_education' => $request->boolean('show_in_education'),
        ], $translatable));

        if ($connection && $request->hasFile('media')) {
            $rootFolder = $otherEventService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $otherEventService->uploadFile($request->file('media'), $rootFolder, $user, $connection);
                if ($doc) {
                    $otherEvent->update(['media_document_id' => $doc->id]);
                }
            }
        }

        $redirect = $stage ? redirect()->route('dashboard.other-events.index', ['stage' => $stage->id]) : redirect()->route('dashboard.other-events.index');
        return $redirect->with('success', 'تم إضافة الحدث بنجاح.');
    }

    public function edit(UserOtherEvent $other_event, OtherEventService $otherEventService)
    {
        $this->ensureWebUser();
        if ($other_event->user_id !== request()->user()->id) {
            abort(403);
        }
        $primaryConnection = $otherEventService->resolveStorageConnection(request()->user());

        return view('dashboard.other-events.edit', compact('other_event', 'primaryConnection'));
    }

    public function update(Request $request, UserOtherEvent $other_event, OtherEventService $otherEventService, TranslationService $translationService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($other_event->user_id !== $user->id) {
            abort(403);
        }

        $connection = $otherEventService->resolveStorageConnection($user);
        if ($request->hasFile('media') && ! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً لرفع الملفات.');
        }

        $validated = $request->validate([
            'record_date' => ['required', 'date'],
            'record_time' => ['nullable', 'date_format:H:i'],
            'title' => ['required', 'string', 'max:255'],
            'other_info' => ['nullable', 'string', 'max:2000'],
            'media' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime',
                'max:51200',
            ],
        ]);

        $translatable = $translationService->prepareTitleOtherInfoTranslatable($validated);

        $other_event->update(array_merge([
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'show_in_education' => $request->boolean('show_in_education'),
        ], $translatable));

        if ($connection && $request->hasFile('media')) {
            if ($other_event->media_document_id) {
                $oldDoc = UserDocument::find($other_event->media_document_id);
                if ($oldDoc && $oldDoc->user_id === $user->id) {
                    $otherEventService->deleteDocument($oldDoc, $connection);
                }
            }
            $rootFolder = $otherEventService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $otherEventService->uploadFile($request->file('media'), $rootFolder, $user, $connection);
                if ($doc) {
                    $other_event->update(['media_document_id' => $doc->id]);
                }
            }
        }

        $stage = $other_event->childhoodStage;
        $redirect = $stage ? redirect()->route('dashboard.other-events.index', ['stage' => $stage->id]) : redirect()->route('dashboard.other-events.index');
        return $redirect->with('success', 'تم تحديث الحدث بنجاح.');
    }

    public function destroy(Request $request, UserOtherEvent $other_event, OtherEventService $otherEventService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($other_event->user_id !== $user->id) {
            abort(403);
        }

        $connection = $otherEventService->resolveStorageConnection($user);
        if ($connection && $other_event->media_document_id) {
            $doc = UserDocument::find($other_event->media_document_id);
            if ($doc && $doc->user_id === $user->id) {
                $otherEventService->deleteDocument($doc, $connection);
            }
        }

        $stage = $other_event->childhoodStage;
        $other_event->delete();

        $redirect = $stage ? redirect()->route('dashboard.other-events.index', ['stage' => $stage->id]) : redirect()->route('dashboard.other-events.index');
        return $redirect->with('success', 'تم حذف الحدث بنجاح.');
    }
}
