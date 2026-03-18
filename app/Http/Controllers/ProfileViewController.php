<?php

namespace App\Http\Controllers;

use App\Models\ChildhoodStagePermission;
use App\Models\UserChildhoodStage;
use App\Models\UserDocument;
use App\Services\DocumentEmbedService;
use App\Services\GoogleDriveService;
use App\Services\WasabiService;
use Illuminate\Http\Request;

class ProfileViewController extends Controller
{
    private function canAccessStage(?UserChildhoodStage $stage): bool
    {
        if (! $stage) {
            return false;
        }

        if ($stage->is_public) {
            return true;
        }

        $user = auth('web')->user();
        if ($user && $stage->user_id === $user->id) {
            return true;
        }

        $sessionKey = "profile_access_{$stage->id}";
        $access = session($sessionKey);
        if (! $access) {
            return false;
        }

        $expiresAt = $access['expires_at'] ?? 0;
        if ($expiresAt < time()) {
            session()->forget($sessionKey);

            return false;
        }

        $permission = ChildhoodStagePermission::find($access['permission_id'] ?? 0);
        if (! $permission || $permission->isExpired()) {
            session()->forget($sessionKey);

            return false;
        }

        return true;
    }

    public function show(UserChildhoodStage $stage)
    {
        $stage->load([
            'coverImageDocument',
            'firstPhotoDocument',
            'footprintDocument',
            'firstVideoDocument',
            'firstGiftDocument',
            'otherPhotos.userDocument',
            'heightWeights.imageDocument',
            'achievements' => fn ($q) => $q->with(['certificateImageDocument', 'mediaItems.userDocument']),
            'visits.mediaDocument',
            'otherEvents.mediaDocument',
            'drawings.mediaDocument',
            'voices.audioDocument',
            'injuries.mediaDocument',
        ]);

        if (! $this->canAccessStage($stage)) {
            return redirect()->route('profile.pin.form', $stage);
        }

        $locale = in_array($stage->default_language ?? '', ['ar', 'en']) ? $stage->default_language : config('app.locale', 'ar');
        app()->setLocale($locale);

        $isPrivate = ! $stage->is_public;
        $expiresAt = null;
        if ($isPrivate) {
            $access = session("profile_access_{$stage->id}");
            if ($access && isset($access['expires_at'])) {
                $expiresAt = \Carbon\Carbon::createFromTimestamp($access['expires_at'])->format('Y-m-d H:i');
            }
        }

        $educationYears = $this->buildEducationYears($stage);
        $lifeStage = $stage->life_stage;

        return match ($lifeStage) {
            'child' => view('profile_pages.child', compact('stage', 'educationYears', 'isPrivate', 'expiresAt')),
            'teenager' => view('profile_pages.teenager', compact('stage', 'educationYears', 'isPrivate', 'expiresAt')),
            'adult' => view('profile_pages.adults', compact('stage', 'educationYears', 'isPrivate', 'expiresAt')),
            default => view('profile_pages.child', compact('stage', 'educationYears', 'isPrivate', 'expiresAt')),
        };
    }

    private function buildEducationYears(UserChildhoodStage $stage): array
    {
        $years = [];

        foreach ($stage->heightWeights->where('show_in_education', true)->sortByDesc('record_date') as $hw) {
            $y = $hw->record_date?->format('Y') ?? date('Y');
            if (! isset($years[$y])) {
                $years[$y] = ['height_weight' => null, 'events' => []];
            }
            if ($years[$y]['height_weight'] === null) {
                $years[$y]['height_weight'] = $hw;
            }
        }

        $eventTypes = [
            'achievements' => ['type' => 'achievement', 'label_attr' => 'type_label'],
            'visits' => ['type' => 'visit', 'label' => 'زيارة'],
            'other_events' => ['type' => 'event', 'label' => 'حدث'],
            'drawings' => ['type' => 'drawing', 'label' => 'رسم'],
            'voices' => ['type' => 'voice', 'label' => 'صوت'],
            'injuries' => ['type' => 'injury', 'label' => 'إصابة'],
        ];

        foreach ($eventTypes as $section => $config) {
            $collection = ($stage->{$section} ?? collect())->where('show_in_education', true);
            foreach ($collection as $item) {
                $y = $item->record_date?->format('Y') ?? (isset($item->academic_year) ? $item->academic_year : date('Y'));
                if (! isset($years[$y])) {
                    $years[$y] = ['height_weight' => null, 'events' => []];
                }
                $label = $config['label'] ?? (isset($config['label_attr']) ? ($item->{$config['label_attr']} ?? $config['type']) : $config['type']);
                $years[$y]['events'][] = [
                    'item' => $item,
                    'type' => $config['type'],
                    'label' => $label,
                ];
            }
        }

        foreach (array_keys($years) as $y) {
            if ($years[$y]['height_weight'] === null && empty($years[$y]['events'])) {
                unset($years[$y]);
            }
        }

        krsort($years, SORT_NUMERIC);

        return $years;
    }

    public function pinForm(UserChildhoodStage $stage)
    {
        return view('profile_pages.pin-login', compact('stage'));
    }

    public function verifyPin(Request $request, UserChildhoodStage $stage)
    {
        $request->validate([
            'pin' => ['required', 'string', 'size:6'],
        ]);

        $permission = $stage->temporaryPermissions()
            ->valid()
            ->get()
            ->first(fn ($p) => $p->verifyPin($request->pin));

        if (! $permission) {
            return redirect()->route('profile.pin.form', $stage)
                ->with('error', 'رمز PIN غير صحيح أو منتهي الصلاحية.');
        }

        session()->put("profile_access_{$stage->id}", [
            'permission_id' => $permission->id,
            'expires_at' => $permission->expires_at->timestamp,
        ]);

        return redirect()->route('profile.show', $stage)
            ->with('success', 'تم التحقق بنجاح.');
    }

    public function logout(UserChildhoodStage $stage)
    {
        session()->forget("profile_access_{$stage->id}");

        return redirect()->route('profile.pin.form', $stage);
    }

    /**
     * Stream document (image) for embedding when viewer has permission via PIN.
     * Allows grantees to see images stored on Google Drive/Wasabi.
     */
    public function embedDocument(
        UserChildhoodStage $stage,
        UserDocument $document,
        DocumentEmbedService $embedService,
        GoogleDriveService $driveService,
        WasabiService $wasabiService
    ) {
        if (! $this->canAccessStage($stage)) {
            abort(403, 'لا تملك صلاحية لعرض هذا المحتوى.');
        }

        if (! $stage->documentBelongsToStage($document)) {
            abort(404);
        }

        $response = $embedService->streamImage($document, $driveService, $wasabiService);
        if (! $response) {
            abort(404);
        }

        return $response;
    }
}
