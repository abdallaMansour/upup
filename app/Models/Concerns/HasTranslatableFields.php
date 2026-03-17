<?php

namespace App\Models\Concerns;

trait HasTranslatableFields
{
    /**
     * Get translatable value for current locale.
     */
    protected function getTranslated(string $key): string
    {
        $locale = app()->getLocale();
        $localeKey = $key.'_'.$locale;
        $fallbackKey = $locale === 'ar' ? $key.'_en' : $key.'_ar';

        return $this->attributes[$localeKey] ?? $this->attributes[$fallbackKey] ?? '';
    }
}
