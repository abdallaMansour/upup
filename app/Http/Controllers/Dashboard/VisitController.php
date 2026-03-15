<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use App\Models\UserVisit;
use App\Services\VisitService;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    private function ensureWebUser(): void
    {
        if (! auth('web')->check()) {
            abort(403, 'هذه الصفحة متاحة للمستخدمين فقط.');
        }
    }

    public function index(Request $request, VisitService $visitService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $stageId = $request->query('stage');
        $query = UserVisit::forUser($user->id)->forStage($stageId ? (int) $stageId : null);
        $visits = $query->with('mediaDocument')
            ->orderByDesc('record_date')
            ->orderByDesc('record_time')
            ->paginate(15)
            ->withQueryString();

        $primaryConnection = $visitService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.visits.index', compact('visits', 'primaryConnection', 'stage'));
    }

    public function create(Request $request, VisitService $visitService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        $stageId = $request->query('stage');
        $primaryConnection = $visitService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.visits.create', compact('primaryConnection', 'stage'));
    }

    public function store(Request $request, VisitService $visitService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connection = $visitService->resolveStorageConnection($user);
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

        $visit = UserVisit::create([
            'user_id' => $user->id,
            'user_childhood_stage_id' => $stage?->id,
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'title' => $validated['title'],
            'other_info' => $validated['other_info'] ?? null,
            'show_in_education' => $request->boolean('show_in_education'),
        ]);

        if ($connection && $request->hasFile('media')) {
            $rootFolder = $visitService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $visitService->uploadFile($request->file('media'), $rootFolder, $user, $connection);
                if ($doc) {
                    $visit->update(['media_document_id' => $doc->id]);
                }
            }
        }

        $redirect = $stage ? redirect()->route('dashboard.visits.index', ['stage' => $stage->id]) : redirect()->route('dashboard.visits.index');
        return $redirect->with('success', 'تم إضافة الزيارة بنجاح.');
    }

    public function edit(UserVisit $visit, VisitService $visitService)
    {
        $this->ensureWebUser();
        if ($visit->user_id !== request()->user()->id) {
            abort(403);
        }
        $primaryConnection = $visitService->resolveStorageConnection(request()->user());

        return view('dashboard.visits.edit', compact('visit', 'primaryConnection'));
    }

    public function update(Request $request, UserVisit $visit, VisitService $visitService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($visit->user_id !== $user->id) {
            abort(403);
        }

        $connection = $visitService->resolveStorageConnection($user);
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

        $visit->update([
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'title' => $validated['title'],
            'other_info' => $validated['other_info'] ?? null,
            'show_in_education' => $request->boolean('show_in_education'),
        ]);

        if ($connection && $request->hasFile('media')) {
            if ($visit->media_document_id) {
                $oldDoc = UserDocument::find($visit->media_document_id);
                if ($oldDoc && $oldDoc->user_id === $user->id) {
                    $visitService->deleteDocument($oldDoc, $connection);
                }
            }
            $rootFolder = $visitService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $visitService->uploadFile($request->file('media'), $rootFolder, $user, $connection);
                if ($doc) {
                    $visit->update(['media_document_id' => $doc->id]);
                }
            }
        }

        $stage = $visit->childhoodStage;
        $redirect = $stage ? redirect()->route('dashboard.visits.index', ['stage' => $stage->id]) : redirect()->route('dashboard.visits.index');
        return $redirect->with('success', 'تم تحديث الزيارة بنجاح.');
    }

    public function destroy(Request $request, UserVisit $visit, VisitService $visitService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($visit->user_id !== $user->id) {
            abort(403);
        }

        $connection = $visitService->resolveStorageConnection($user);
        if ($connection && $visit->media_document_id) {
            $doc = UserDocument::find($visit->media_document_id);
            if ($doc && $doc->user_id === $user->id) {
                $visitService->deleteDocument($doc, $connection);
            }
        }

        $stage = $visit->childhoodStage;
        $visit->delete();

        $redirect = $stage ? redirect()->route('dashboard.visits.index', ['stage' => $stage->id]) : redirect()->route('dashboard.visits.index');
        return $redirect->with('success', 'تم حذف الزيارة بنجاح.');
    }
}
