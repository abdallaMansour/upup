<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function privacyPolicy()
    {
        $settings = SiteSetting::get();

        return view('dashboard.site-settings.privacy-policy', compact('settings'));
    }

    public function updatePrivacyPolicy(Request $request)
    {
        $validated = $request->validate([
            'privacy_policy' => ['nullable', 'string'],
        ]);

        $settings = SiteSetting::get();
        $settings->update(['privacy_policy' => $validated['privacy_policy'] ?? '']);

        return redirect()->route('dashboard.privacy-policy.index')->with('success', __('تم تحديث سياسة الخصوصية بنجاح.'));
    }

    public function termsAndConditions()
    {
        $settings = SiteSetting::get();

        return view('dashboard.site-settings.terms-and-conditions', compact('settings'));
    }

    public function updateTermsAndConditions(Request $request)
    {
        $validated = $request->validate([
            'terms_and_conditions' => ['nullable', 'string'],
        ]);

        $settings = SiteSetting::get();
        $settings->update(['terms_and_conditions' => $validated['terms_and_conditions'] ?? '']);

        return redirect()->route('dashboard.terms-and-conditions.index')->with('success', __('تم تحديث الشروط والأحكام بنجاح.'));
    }
}
