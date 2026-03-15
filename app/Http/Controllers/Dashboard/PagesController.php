<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EducationStage;
use App\Models\MediaDepartment;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        $media = MediaDepartment::get();
        $educationStages = EducationStage::with('grades')->orderBy('name')->get();

        return view('dashboard.index', compact('media', 'educationStages'));
    }

    public function updateEducation(Request $request)
    {
        $validated = $request->validate([
            'education_stage_id' => ['nullable', 'exists:education_stages,id'],
            'education_grade_id' => ['nullable', 'exists:education_grades,id'],
            'school_name' => ['nullable', 'string', 'max:255'],
        ], [], [
            'education_stage_id' => 'المرحلة',
            'education_grade_id' => 'الصف',
            'school_name' => 'اسم المدرسة أو الجامعة',
        ]);

        if (!empty($validated['education_grade_id']) && !empty($validated['education_stage_id'])) {
            $grade = \App\Models\EducationGrade::find($validated['education_grade_id']);
            if ($grade && $grade->education_stage_id != $validated['education_stage_id']) {
                $validated['education_grade_id'] = null;
            }
        }

        $user = auth()->user();
        $user->update($validated);

        return redirect()->route('dashboard.index')->with('success', 'تم تحديث المرحلة التعليمية بنجاح.');
    }
}