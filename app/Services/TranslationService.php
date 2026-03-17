<?php

namespace App\Services;

use Stichoza\GoogleTranslate\Exceptions\RateLimitException;
use Stichoza\GoogleTranslate\Exceptions\TranslationDecodingException;
use Stichoza\GoogleTranslate\Exceptions\TranslationRequestException;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Throwable;

class TranslationService
{
    /**
     * Translate text from source language to target language.
     *
     * @param  string  $text  Text to translate
     * @param  string  $from  Source language code (e.g. 'ar', 'en')
     * @param  string  $to  Target language code
     * @return string|null Translated text or null on failure
     */
    public function translate(string $text, string $from, string $to): ?string
    {
        $text = trim($text);
        if ($text === '' || strlen($text) < 2) {
            return null;
        }

        if ($from === $to) {
            return $text;
        }

        try {
            $translator = new GoogleTranslate($to, $from);
            $result = $translator->translate($text);

            return $result !== null ? trim($result) : null;
        } catch (RateLimitException|TranslationRequestException|TranslationDecodingException $e) {
            report($e);

            return null;
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * Translate text from current app locale to the other language (ar <-> en).
     *
     * @param  string  $text  Text to translate
     * @return string|null Translated text or null on failure
     */
    public function translateToOtherLocale(string $text): ?string
    {
        $locale = app()->getLocale();
        $other = $locale === 'ar' ? 'en' : 'ar';

        return $this->translate($text, $locale, $other);
    }

    /**
     * Translate multiple fields and return array of [field_ar, field_en] values.
     * Input is the user's value in current locale.
     *
     * @param  string  $value  User input in current locale
     * @return array{0: string, 1: string|null} [ar_value, en_value]
     */
    public function translateForBothLocales(string $value): array
    {
        $value = trim($value);
        if ($value === '') {
            return ['', ''];
        }

        $locale = app()->getLocale();
        $translated = $this->translateToOtherLocale($value);

        if ($locale === 'ar') {
            return [$value, $translated ?? $value];
        }

        return [$translated ?? $value, $value];
    }

    /**
     * Prepare achievement translatable data from request input.
     *
     * @return array<string, string|null>
     */
    public function prepareAchievementTranslatable(array $input): array
    {
        $fields = ['title', 'place', 'academic_year', 'school'];
        $result = [];

        foreach ($fields as $field) {
            $value = trim($input[$field] ?? '');
            [$ar, $en] = $this->translateForBothLocales($value);
            $result[$field.'_ar'] = $ar ?: null;
            $result[$field.'_en'] = $en ?: null;
        }

        return $result;
    }

    /**
     * Prepare title + other_info translatable data from request input.
     *
     * @return array<string, string|null>
     */
    public function prepareTitleOtherInfoTranslatable(array $input): array
    {
        $result = [];
        foreach (['title', 'other_info'] as $field) {
            $value = trim($input[$field] ?? '');
            [$ar, $en] = $this->translateForBothLocales($value);
            $result[$field.'_ar'] = $ar ?: null;
            $result[$field.'_en'] = $en ?: null;
        }

        return $result;
    }

    /**
     * Prepare childhood stage translatable data from request input.
     *
     * @return array<string, string|null>
     */
    public function prepareChildhoodStageTranslatable(array $input): array
    {
        $fields = ['name', 'mother_name', 'father_name', 'naming_reason', 'birth_place', 'doctor'];
        $result = [];

        foreach ($fields as $field) {
            $value = trim($input[$field] ?? '');
            [$ar, $en] = $this->translateForBothLocales($value);
            $result[$field.'_ar'] = $ar ?: null;
            $result[$field.'_en'] = $en ?: null;
        }

        return $result;
    }
}
