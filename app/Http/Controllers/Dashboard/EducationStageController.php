<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EducationStage;
use Illuminate\Http\Request;

class EducationStageController extends Controller
{
    public function index()
    {
        if (auth('admin')->check() && !auth('admin')->user()->hasPermission('education-stages.manage')) {
            return abort(403, 'ليس لديك صلاحية لإدارة المراحل التعليمية');
        }

        $stages = EducationStage::withCount('grades')->get();

        return view('dashboard.education-stages.index', compact('stages'));
    }

    public function create()
    {
        return view('dashboard.education-stages.create');
    }

    public function storeStage(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
        ], [], [
            'name_ar' => 'الاسم (عربي)',
            'name_en' => 'الاسم (إنجليزي)',
        ]);

        EducationStage::create($validated);

        return redirect()->route('dashboard.education-stages.index')->with('success', __('تم إنشاء المرحلة بنجاح.'));
    }

    public function edit(EducationStage $education_stage)
    {
        return view('dashboard.education-stages.edit', compact('education_stage'));
    }

    public function updateStage(Request $request, EducationStage $education_stage)
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
        ], [], [
            'name_ar' => 'الاسم (عربي)',
            'name_en' => 'الاسم (إنجليزي)',
        ]);

        $education_stage->update($validated);

        return redirect()->route('dashboard.education-stages.index')->with('success', __('تم تحديث المرحلة بنجاح.'));
    }

    public function destroyStage(EducationStage $education_stage)
    {
        $education_stage->delete();

        return redirect()->route('dashboard.education-stages.index')->with('success', __('تم حذف المرحلة بنجاح.'));
    }
}
