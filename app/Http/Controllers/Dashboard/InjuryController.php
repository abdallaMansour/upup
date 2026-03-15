<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use App\Models\UserInjury;
use App\Services\InjuryService;
use Illuminate\Http\Request;

class InjuryController extends Controller
{
    private function ensureWebUser(): void
    {
        if (! auth('web')->check()) {
            abort(403, 'هذه الصفحة متاحة للمستخدمين فقط.');
        }
    }

    public function index(Request $request, InjuryService $injuryService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $stageId = $request->query('stage');
        $query = UserInjury::forUser($user->id)->forStage($stageId ? (int) $stageId : null);
        $injuries = $query->with('mediaDocument')
            ->orderByDesc('record_date')
            ->orderByDesc('record_time')
            ->paginate(15)
            ->withQueryString();

        $primaryConnection = $injuryService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.injuries.index', compact('injuries', 'primaryConnection', 'stage'));
    }

    public function create(Request $request, InjuryService $injuryService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        $stageId = $request->query('stage');
        $primaryConnection = $injuryService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.injuries.create', compact('primaryConnection', 'stage'));
    }

    public function store(Request $request, InjuryService $injuryService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connection = $injuryService->resolveStorageConnection($user);
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

        $injury = UserInjury::create([
            'user_id' => $user->id,
            'user_childhood_stage_id' => $stage?->id,
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'title' => $validated['title'],
            'other_info' => $validated['other_info'] ?? null,
            'show_in_education' => $request->boolean('show_in_education'),
        ]);

        if ($connection && $request->hasFile('media')) {
            $rootFolder = $injuryService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $injuryService->uploadFile($request->file('media'), $rootFolder, $user, $connection);
                if ($doc) {
                    $injury->update(['media_document_id' => $doc->id]);
                }
            }
        }

        $redirect = $stage ? redirect()->route('dashboard.injuries.index', ['stage' => $stage->id]) : redirect()->route('dashboard.injuries.index');
        return $redirect->with('success', 'تم إضافة الإصابة بنجاح.');
    }

    public function edit(UserInjury $injury, InjuryService $injuryService)
    {
        $this->ensureWebUser();
        if ($injury->user_id !== request()->user()->id) {
            abort(403);
        }
        $primaryConnection = $injuryService->resolveStorageConnection(request()->user());

        return view('dashboard.injuries.edit', compact('injury', 'primaryConnection'));
    }

    public function update(Request $request, UserInjury $injury, InjuryService $injuryService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($injury->user_id !== $user->id) {
            abort(403);
        }

        $connection = $injuryService->resolveStorageConnection($user);
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

        $injury->update([
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'title' => $validated['title'],
            'other_info' => $validated['other_info'] ?? null,
            'show_in_education' => $request->boolean('show_in_education'),
        ]);

        if ($connection && $request->hasFile('media')) {
            if ($injury->media_document_id) {
                $oldDoc = UserDocument::find($injury->media_document_id);
                if ($oldDoc && $oldDoc->user_id === $user->id) {
                    $injuryService->deleteDocument($oldDoc, $connection);
                }
            }
            $rootFolder = $injuryService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $doc = $injuryService->uploadFile($request->file('media'), $rootFolder, $user, $connection);
                if ($doc) {
                    $injury->update(['media_document_id' => $doc->id]);
                }
            }
        }

        $stage = $injury->childhoodStage;
        $redirect = $stage ? redirect()->route('dashboard.injuries.index', ['stage' => $stage->id]) : redirect()->route('dashboard.injuries.index');
        return $redirect->with('success', 'تم تحديث الإصابة بنجاح.');
    }

    public function destroy(Request $request, UserInjury $injury, InjuryService $injuryService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($injury->user_id !== $user->id) {
            abort(403);
        }

        $connection = $injuryService->resolveStorageConnection($user);
        if ($connection && $injury->media_document_id) {
            $doc = UserDocument::find($injury->media_document_id);
            if ($doc && $doc->user_id === $user->id) {
                $injuryService->deleteDocument($doc, $connection);
            }
        }

        $stage = $injury->childhoodStage;
        $injury->delete();

        $redirect = $stage ? redirect()->route('dashboard.injuries.index', ['stage' => $stage->id]) : redirect()->route('dashboard.injuries.index');
        return $redirect->with('success', 'تم حذف الإصابة بنجاح.');
    }
}
