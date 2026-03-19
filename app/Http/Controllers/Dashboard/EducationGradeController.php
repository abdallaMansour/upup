<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EducationGrade;
use App\Models\EducationStage;
use Illuminate\Http\Request;

class EducationGradeController extends Controller
{
    public function index()
    {
        if (auth('admin')->check() && !auth('admin')->user()->hasPermission('education-stages.manage')) {
            return abort(403, 'ليس لديك صلاحية لإدارة المراحل التعليمية');
        }

        $grades = EducationGrade::with('stage')->orderBy('education_stage_id')->get();

        return view('dashboard.education-grades.index', compact('grades'));
    }

    public function create()
    {
        $stages = EducationStage::get();

        return view('dashboard.education-grades.create', compact('stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'education_stage_id' => ['required', 'exists:education_stages,id'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
        ], [], [
            'education_stage_id' => 'المرحلة',
            'name_ar' => 'اسم الصف (عربي)',
            'name_en' => 'اسم الصف (إنجليزي)',
        ]);

        EducationGrade::create($validated);

        return redirect()->route('dashboard.education-grades.index')->with('success', __('تم إنشاء الصف بنجاح.'));
    }

    public function edit(EducationGrade $education_grade)
    {
        $stages = EducationStage::get();

        return view('dashboard.education-grades.edit', compact('education_grade', 'stages'));
    }

    public function update(Request $request, EducationGrade $education_grade)
    {
        $validated = $request->validate([
            'education_stage_id' => ['required', 'exists:education_stages,id'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
        ], [], [
            'education_stage_id' => 'المرحلة',
            'name_ar' => 'اسم الصف (عربي)',
            'name_en' => 'اسم الصف (إنجليزي)',
        ]);

        $education_grade->update($validated);

        return redirect()->route('dashboard.education-grades.index')->with('success', __('تم تحديث الصف بنجاح.'));
    }

    public function destroy(EducationGrade $education_grade)
    {
        $education_grade->delete();

        return redirect()->route('dashboard.education-grades.index')->with('success', __('تم حذف الصف بنجاح.'));
    }
}
