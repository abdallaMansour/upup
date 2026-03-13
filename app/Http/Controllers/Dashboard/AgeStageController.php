<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AgeStageController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::get();

        return view('dashboard.age-stages.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'age_stage_childhood_max' => ['required', 'integer', 'min:0', 'max:120'],
            'age_stage_teenager_max' => ['required', 'integer', 'min:0', 'max:120'],
            'age_stage_adult_max' => ['required', 'integer', 'min:0', 'max:120'],
        ], [], [
            'age_stage_childhood_max' => 'أقصى عمر لمرحلة الطفولة',
            'age_stage_teenager_max' => 'أقصى عمر لمرحلة المراهقة',
            'age_stage_adult_max' => 'أقصى عمر لمرحلة البالغين',
        ]);

        if ($validated['age_stage_teenager_max'] <= $validated['age_stage_childhood_max']) {
            return redirect()->back()
                ->withErrors(['age_stage_teenager_max' => 'أقصى عمر المراهقة يجب أن يكون أكبر من أقصى عمر الطفولة.'])
                ->withInput();
        }

        if ($validated['age_stage_adult_max'] <= $validated['age_stage_teenager_max']) {
            return redirect()->back()
                ->withErrors(['age_stage_adult_max' => 'أقصى عمر البالغين يجب أن يكون أكبر من أقصى عمر المراهقة.'])
                ->withInput();
        }

        $settings = SiteSetting::get();
        $settings->update($validated);

        return redirect()->route('dashboard.age-stages.index')->with('success', 'تم تحديث المراحل العمرية بنجاح.');
    }
}
