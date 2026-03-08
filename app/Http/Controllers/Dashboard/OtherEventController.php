<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserDocument;
use App\Models\UserOtherEvent;
use App\Services\OtherEventService;
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

        $otherEvents = UserOtherEvent::forUser($user->id)
            ->with('mediaDocument')
            ->orderByDesc('record_date')
            ->orderByDesc('record_time')
            ->paginate(15);

        $primaryConnection = $otherEventService->resolveStorageConnection($user);

        return view('dashboard.other-events.index', compact('otherEvents', 'primaryConnection'));
    }

    public function create(OtherEventService $otherEventService)
    {
        $this->ensureWebUser();
        $user = request()->user();
        $primaryConnection = $otherEventService->resolveStorageConnection($user);

        return view('dashboard.other-events.create', compact('primaryConnection'));
    }

    public function store(Request $request, OtherEventService $otherEventService)
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

        $otherEvent = UserOtherEvent::create([
            'user_id' => $user->id,
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'title' => $validated['title'],
            'other_info' => $validated['other_info'] ?? null,
        ]);

        if ($connection && $request->hasFile('media')) {
            $rootFolder = $otherEventService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $otherEventService->uploadFile($request->file('media'), $rootFolder, $user, $connection);
                if ($doc) {
                    $otherEvent->update(['media_document_id' => $doc->id]);
                }
            }
        }

        return redirect()->route('dashboard.other-events.index')->with('success', 'تم إضافة الحدث بنجاح.');
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

    public function update(Request $request, UserOtherEvent $other_event, OtherEventService $otherEventService)
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

        $other_event->update([
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'title' => $validated['title'],
            'other_info' => $validated['other_info'] ?? null,
        ]);

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

        return redirect()->route('dashboard.other-events.index')->with('success', 'تم تحديث الحدث بنجاح.');
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

        $other_event->delete();

        return redirect()->route('dashboard.other-events.index')->with('success', 'تم حذف الحدث بنجاح.');
    }
}
