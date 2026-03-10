<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use App\Models\UserHeightWeight;
use App\Services\HeightWeightService;
use Illuminate\Http\Request;

class HeightWeightController extends Controller
{
    private function ensureWebUser(): void
    {
        if (! auth('web')->check()) {
            abort(403, 'هذه الصفحة متاحة للمستخدمين فقط.');
        }
    }

    public function index(Request $request, HeightWeightService $hwService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        $stageId = $request->query('stage');

        $query = UserHeightWeight::forUser($user->id)->forStage($stageId ? (int) $stageId : null);
        $records = $query->with(['imageDocument', 'videoDocument'])
            ->orderByDesc('record_date')
            ->orderByDesc('record_time')
            ->paginate(15)
            ->withQueryString();

        $primaryConnection = $hwService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.height-weight.index', compact('records', 'primaryConnection', 'stage'));
    }

    public function create(Request $request, HeightWeightService $hwService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        $stageId = $request->query('stage');
        $primaryConnection = $hwService->resolveStorageConnection($user);
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        return view('dashboard.height-weight.create', compact('primaryConnection', 'stage'));
    }

    public function store(Request $request, HeightWeightService $hwService)
    {
        $this->ensureWebUser();
        $user = $request->user();

        $connection = $hwService->resolveStorageConnection($user);
        $hasFiles = $request->hasFile('image') || $request->hasFile('video');

        if ($hasFiles && ! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً لرفع الملفات.');
        }

        $validated = $request->validate([
            'record_date' => ['required', 'date'],
            'record_time' => ['nullable', 'date_format:H:i'],
            'height' => ['nullable', 'numeric', 'min:0', 'max:300'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'image' => ['nullable', 'file', 'image', 'max:10240'],
            'video' => ['nullable', 'file', 'mimetypes:video/*', 'max:51200'],
        ]);

        $stageId = $request->query('stage');
        $stage = $stageId ? UserChildhoodStage::where('id', $stageId)->where('user_id', $user->id)->first() : null;

        $record = UserHeightWeight::create([
            'user_id' => $user->id,
            'user_childhood_stage_id' => $stage?->id,
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
        ]);

        if ($connection) {
            $rootFolder = $hwService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $imagesFolder = $hwService->getOrCreateSubfolder($user, $connection, $rootFolder, 'images');
                $videosFolder = $hwService->getOrCreateSubfolder($user, $connection, $rootFolder, 'videos');

                if ($request->hasFile('image') && $imagesFolder) {
                    $doc = $hwService->uploadFile($request->file('image'), $imagesFolder, $user, $connection);
                    if ($doc) {
                        $record->update(['image_document_id' => $doc->id]);
                    }
                }
                if ($request->hasFile('video') && $videosFolder) {
                    $doc = $hwService->uploadFile($request->file('video'), $videosFolder, $user, $connection);
                    if ($doc) {
                        $record->update(['video_document_id' => $doc->id]);
                    }
                }
            }
        }

        $redirect = $stage
            ? redirect()->route('dashboard.height-weight.index', ['stage' => $stage->id])
            : redirect()->route('dashboard.height-weight.index');

        return $redirect->with('success', 'تم إضافة السجل بنجاح.');
    }

    public function edit(UserHeightWeight $heightWeight, HeightWeightService $hwService)
    {
        $this->ensureWebUser();
        if ($heightWeight->user_id !== request()->user()->id) {
            abort(403);
        }
        $primaryConnection = $hwService->resolveStorageConnection(request()->user());

        return view('dashboard.height-weight.edit', compact('heightWeight', 'primaryConnection'));
    }

    public function update(Request $request, UserHeightWeight $heightWeight, HeightWeightService $hwService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($heightWeight->user_id !== $user->id) {
            abort(403);
        }

        $connection = $hwService->resolveStorageConnection($user);
        $hasFiles = $request->hasFile('image') || $request->hasFile('video');

        if ($hasFiles && ! $connection) {
            return redirect()->back()->with('error', 'لم يتم العثور على اتصال تخزين. يرجى ربط Google Drive أو Wasabi أولاً لرفع الملفات.');
        }

        $validated = $request->validate([
            'record_date' => ['required', 'date'],
            'record_time' => ['nullable', 'date_format:H:i'],
            'height' => ['nullable', 'numeric', 'min:0', 'max:300'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'image' => ['nullable', 'file', 'image', 'max:10240'],
            'video' => ['nullable', 'file', 'mimetypes:video/*', 'max:51200'],
        ]);

        $heightWeight->update([
            'record_date' => $validated['record_date'],
            'record_time' => $validated['record_time'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
        ]);

        if ($connection) {
            $rootFolder = $hwService->getOrCreateRootFolder($user, $connection);
            if ($rootFolder) {
                $imagesFolder = $hwService->getOrCreateSubfolder($user, $connection, $rootFolder, 'images');
                $videosFolder = $hwService->getOrCreateSubfolder($user, $connection, $rootFolder, 'videos');

                if ($request->hasFile('image') && $imagesFolder) {
                    if ($heightWeight->image_document_id) {
                        $oldDoc = UserDocument::find($heightWeight->image_document_id);
                        if ($oldDoc && $oldDoc->user_id === $user->id) {
                            $hwService->deleteDocument($oldDoc, $connection);
                        }
                    }
                    $doc = $hwService->uploadFile($request->file('image'), $imagesFolder, $user, $connection);
                    if ($doc) {
                        $heightWeight->update(['image_document_id' => $doc->id]);
                    }
                }
                if ($request->hasFile('video') && $videosFolder) {
                    if ($heightWeight->video_document_id) {
                        $oldDoc = UserDocument::find($heightWeight->video_document_id);
                        if ($oldDoc && $oldDoc->user_id === $user->id) {
                            $hwService->deleteDocument($oldDoc, $connection);
                        }
                    }
                    $doc = $hwService->uploadFile($request->file('video'), $videosFolder, $user, $connection);
                    if ($doc) {
                        $heightWeight->update(['video_document_id' => $doc->id]);
                    }
                }
            }
        }

        return redirect()->route('dashboard.height-weight.index')->with('success', 'تم تحديث السجل بنجاح.');
    }

    public function destroy(Request $request, UserHeightWeight $heightWeight, HeightWeightService $hwService)
    {
        $this->ensureWebUser();
        $user = $request->user();
        if ($heightWeight->user_id !== $user->id) {
            abort(403);
        }

        $connection = $hwService->resolveStorageConnection($user);
        if ($connection) {
            if ($heightWeight->image_document_id) {
                $doc = UserDocument::find($heightWeight->image_document_id);
                if ($doc && $doc->user_id === $user->id) {
                    $hwService->deleteDocument($doc, $connection);
                }
            }
            if ($heightWeight->video_document_id) {
                $doc = UserDocument::find($heightWeight->video_document_id);
                if ($doc && $doc->user_id === $user->id) {
                    $hwService->deleteDocument($doc, $connection);
                }
            }
        }

        $heightWeight->delete();

        $stage = $heightWeight->childhoodStage;
        $redirect = $stage
            ? redirect()->route('dashboard.height-weight.index', ['stage' => $stage->id])
            : redirect()->route('dashboard.height-weight.index');

        return $redirect->with('success', 'تم حذف السجل بنجاح.');
    }
}
