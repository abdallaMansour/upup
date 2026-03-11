<?php

namespace App\Http\Controllers;

use App\Models\ChildhoodStagePermission;
use App\Models\UserChildhoodStage;
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
        $stage->load(['coverImageDocument', 'firstPhotoDocument']);

        if (! $this->canAccessStage($stage)) {
            return redirect()->route('profile.pin.form', $stage);
        }

        $lifeStage = $stage->life_stage;

        return match ($lifeStage) {
            'child' => view('profile_pages.child', compact('stage')),
            'teenager' => view('profile_pages.teenager', compact('stage')),
            'adult' => view('profile_pages.adults', compact('stage')),
            default => view('profile_pages.child', compact('stage')),
        };
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
}
