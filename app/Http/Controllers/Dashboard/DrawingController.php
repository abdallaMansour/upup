<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use App\Models\UserDrawing;
use App\Services\DrawingService;
use Illuminate\Http\Request;

class DrawingController extends Controller
{
    private function ensureWebUser(): void
    {
        if (! auth('web')->check()) {
            abort(403, 'هذه الصفحة متاحة للمستخدمين فقط.');
        }
    }

    public function index(Request $request, DrawingService $drawingService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $stageId = $request->query('stage');
        $query = UserDrawing::forUser($user->id)->forStage($stageId ? (int) $stageId : null);
        $drawings = $query->with('mediaDocument')
            ->orderByDesc('record_date')
            ->orderByDesc('record_time')
            ->paginate(15)
            ->withQueryString();

        $primaryConnection = $drawingService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.drawings.index', compact('drawings', 'primaryConnection', 'stage'));
    }

    public function create(Request $request, DrawingService $drawingService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        $stageId = $request->query('stage');
        $primaryConnection = $drawingService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.drawings.create', compact('primaryConnection', 'stage'));
    }

    public function store(Request $request, DrawingService $drawingService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connection = $drawingService->resolveStorageConnection($user);
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

        $drawing = UserDrawing::create([
            'user_id' => $user->id,
            'user_childhood_stage_id' => $stage?->id,
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'title' => $validated['title'],
            'other_info' => $validated['other_info'] ?? null,
        ]);

        if ($connection && $request->hasFile('media')) {
            $rootFolder = $drawingService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $drawingService->uploadFile($request->file('media'), $rootFolder, $user, $connection);
                if ($doc) {
                    $drawing->update(['media_document_id' => $doc->id]);
                }
            }
        }

        $redirect = $stage ? redirect()->route('dashboard.drawings.index', ['stage' => $stage->id]) : redirect()->route('dashboard.drawings.index');
        return $redirect->with('success', 'تم إضافة الرسم بنجاح.');
    }

    public function edit(UserDrawing $drawing, DrawingService $drawingService)
    {
        $this->ensureWebUser();
        if ($drawing->user_id !== request()->user()->id) {
            abort(403);
        }
        $primaryConnection = $drawingService->resolveStorageConnection(request()->user());

        return view('dashboard.drawings.edit', compact('drawing', 'primaryConnection'));
    }

    public function update(Request $request, UserDrawing $drawing, DrawingService $drawingService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($drawing->user_id !== $user->id) {
            abort(403);
        }

        $connection = $drawingService->resolveStorageConnection($user);
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

        $drawing->update([
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'title' => $validated['title'],
            'other_info' => $validated['other_info'] ?? null,
        ]);

        if ($connection && $request->hasFile('media')) {
            if ($drawing->media_document_id) {
                $oldDoc = UserDocument::find($drawing->media_document_id);
                if ($oldDoc && $oldDoc->user_id === $user->id) {
                    $drawingService->deleteDocument($oldDoc, $connection);
                }
            }
            $rootFolder = $drawingService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $drawingService->uploadFile($request->file('media'), $rootFolder, $user, $connection);
                if ($doc) {
                    $drawing->update(['media_document_id' => $doc->id]);
                }
            }
        }

        $stage = $drawing->childhoodStage;
        $redirect = $stage ? redirect()->route('dashboard.drawings.index', ['stage' => $stage->id]) : redirect()->route('dashboard.drawings.index');
        return $redirect->with('success', 'تم تحديث الرسم بنجاح.');
    }

    public function destroy(Request $request, UserDrawing $drawing, DrawingService $drawingService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($drawing->user_id !== $user->id) {
            abort(403);
        }

        $connection = $drawingService->resolveStorageConnection($user);
        if ($connection && $drawing->media_document_id) {
            $doc = UserDocument::find($drawing->media_document_id);
            if ($doc && $doc->user_id === $user->id) {
                $drawingService->deleteDocument($doc, $connection);
            }
        }

        $stage = $drawing->childhoodStage;
        $drawing->delete();
        $redirect = $stage ? redirect()->route('dashboard.drawings.index', ['stage' => $stage->id]) : redirect()->route('dashboard.drawings.index');
        return $redirect->with('success', 'تم حذف الرسم بنجاح.');
    }
}
