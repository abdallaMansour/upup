<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EducationStage;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountSettingsController extends Controller
{
    public function index()
    {
        $this->ensureWebUser();

        $user = auth()->user();
        $educationStages = EducationStage::with('grades')->get();

        return view('dashboard.account-settings.index', compact('user', 'educationStages'));
    }

    public function updateAccount(Request $request, TranslationService $translationService)
    {
        $this->ensureWebUser();

        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'education_stage_id' => ['nullable', 'exists:education_stages,id'],
            'education_grade_id' => ['nullable', 'exists:education_grades,id'],
            'school_name' => ['nullable', 'string', 'max:255'],
        ], [], [
            'name' => __('dashboard.account_settings.name'),
            'email' => __('dashboard.account_settings.email'),
            'phone' => __('dashboard.account_settings.phone'),
            'education_stage_id' => __('dashboard.modal.stage'),
            'education_grade_id' => __('dashboard.modal.grade'),
            'school_name' => __('dashboard.modal.school_name'),
        ]);

        if (! empty($validated['education_grade_id']) && ! empty($validated['education_stage_id'])) {
            $grade = \App\Models\EducationGrade::find($validated['education_grade_id']);
            if ($grade && $grade->education_stage_id != $validated['education_stage_id']) {
                $validated['education_grade_id'] = null;
            }
        }

        $schoolName = trim($validated['school_name'] ?? '');
        if ($schoolName !== '') {
            [$ar, $en] = $translationService->translateForBothLocales($schoolName);
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?: null,
                'education_stage_id' => $validated['education_stage_id'] ?? null,
                'education_grade_id' => $validated['education_grade_id'] ?? null,
                'school_name_ar' => $ar ?: null,
                'school_name_en' => $en ?: null,
            ]);
        } else {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?: null,
                'education_stage_id' => $validated['education_stage_id'] ?? null,
                'education_grade_id' => $validated['education_grade_id'] ?? null,
                'school_name_ar' => null,
                'school_name_en' => null,
            ]);
        }

        return redirect()->route('dashboard.account-settings.index')
            ->with('success', __('dashboard.account_settings.account_updated'));
    }

    public function updatePassword(Request $request)
    {
        $this->ensureWebUser();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ], [], [
            'current_password' => __('dashboard.account_settings.current_password'),
            'password' => __('dashboard.account_settings.new_password'),
        ]);

        $user = auth()->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()
                ->withInput($request->only('current_password'))
                ->withErrors(['current_password' => __('dashboard.account_settings.current_password_invalid')]);
        }

        $user->update(['password' => $validated['password']]);

        return redirect()->route('dashboard.account-settings.index', ['tab' => 'security'])
            ->with('success', __('dashboard.account_settings.password_updated'));
    }

    private function ensureWebUser(): void
    {
        if (! auth('web')->check()) {
            abort(403);
        }
    }
}
