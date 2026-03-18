<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="pageTitle" data-content-ar="صفحة الطفولة - {{ $stage->name_ar ?? $stage->name_en ?? '' }}" data-content-en="Childhood Page - {{ $stage->name_en ?? $stage->name_ar ?? '' }}">{{ $stage->name }}</title>
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap{{ app()->getLocale() === 'ar' ? '.rtl' : '' }}.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts - Alexandria & Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>

    @include('profile_pages.components.header', ['stage' => $stage, 'isPrivate' => $isPrivate ?? false, 'expiresAt' => $expiresAt ?? null])

    <!-- ====== COVER / PROFILE HEADER ====== -->
    <section class="profile-cover">
        <!-- Gradient Background -->
        <div class="cover-gradient">
            <!-- Rainbow arc decoration -->
        </div>

        <!-- Kids Decoration — bottom-left (RTL) / bottom-right (LTR) -->
        <div class="cover-kids-deco">
            <div class="deco-rocket">
                <i class="fas fa-rocket"></i>
            </div>
            <div class="deco-star deco-star-1"><i class="fas fa-star"></i></div>
            <div class="deco-star deco-star-2"><i class="fas fa-star"></i></div>
            <div class="deco-star deco-star-3"><i class="fas fa-star"></i></div>
            <div class="deco-cloud deco-cloud-1"><i class="fas fa-cloud"></i></div>
            <div class="deco-cloud deco-cloud-2"><i class="fas fa-cloud"></i></div>
            <div class="deco-planet"><i class="fas fa-globe-americas"></i></div>
        </div>

        <!-- Profile Info overlay at bottom-right of cover -->
        <div class="cover-profile-info">
            <div class="cover-profile-text">
                <p class="cover-bio"><span data-translate="coverBioMyName">انا اسمي</span> </p>
                <h1 class="cover-name" data-translate="profileName" data-content-ar="{{ $stage->name_ar ?? '' }}" data-content-en="{{ $stage->name_en ?? '' }}">{{ $stage->name }}</h1>
                <p class="cover-bio">
                    @if ($stage->age_in_years !== null)
                        <span data-translate="coverBioAndAge">و عمري</span> {{ $stage->age_in_years }} <span data-translate="unitYear">سنة</span>
                    @endif
                </p>
                <div class="cover-badges" id="coverBadges">
                    <span class="cover-badge" data-translate="badgeChildhood">صدقة الطفولة</span>
                </div>
                @if ($stage->naming_reason)
                    <p class="cover-bio" data-content-ar="{{ $stage->naming_reason_ar ?? '' }}" data-content-en="{{ $stage->naming_reason_en ?? '' }}">{{ $stage->naming_reason }}</p>
                @else
                    <p class="cover-bio" data-content-ar="طفلي عزاز يحب الاستكشاف والتعليم" data-content-en="My child loves exploring and learning">طفلي عزاز يحب الاستكشاف والتعليم</p>
                @endif
            </div>
            <div class="cover-avatar">
                @php
                    $avatarDoc = $stage->coverImageDocument ?? $stage->firstPhotoDocument;
                    $avatarUrl = $avatarDoc ? route('profile.document.embed', [$stage, $avatarDoc]) : null;
                @endphp
                <div class="avatar-circle">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $stage->name }}" class="avatar-img" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    @else
                        <i class="fas fa-user avatar-icon"></i>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- ====== STATS BAR ====== -->
    @php
        $visitsCount = $stage->visits->count();
        $achievementsCount = $stage->achievements->count();
        $heightWeightsCount = $stage->heightWeights->count();
        $otherEventsCount = $stage->otherEvents->count();
    @endphp
    <section class="stats-bar">
        <div class="container">
            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-num">{{ $visitsCount + $otherEventsCount }}</span>
                    <span class="stat-lbl" data-translate="statVisits">الزيارات والأحداث</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">{{ $achievementsCount }}</span>
                    <span class="stat-lbl" data-translate="statAchievements">الإنجازات</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">{{ $heightWeightsCount }}</span>
                    <span class="stat-lbl" data-translate="statMeasurements">سجل القياسات</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">{{ $stage->age_in_years ?? '-' }}</span>
                    <span class="stat-lbl" data-translate="statAge">العمر (سنة)</span>
                </div>
            </div>
        </div>
    </section>



    <!-- ====== TAB NAVIGATION ====== -->
    <section class="tab-navigation">
        <div class="container">
            <ul class="profile-tabs" id="profileTabs">
                <li><a href="#tab-home" class="tab-link active" data-translate="tabHome"><i class="fas fa-home"></i> الرئيسية<div class="tab-star tab-star-1"><svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-3"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-4"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-6"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                    </a></li>
                <li><a href="#tab-birth" class="tab-link" data-translate="tabBirth"><i class="fas fa-baby-carriage"></i> الولادة<div class="tab-star tab-star-1"><svg
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-3"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-4"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-6"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                    </a></li>
                <li><a href="#tab-height" class="tab-link" data-translate="tabHeight"><i class="fas fa-ruler-vertical"></i> الطول<div class="tab-star tab-star-1"><svg
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-3"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-4"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-6"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                    </a></li>
                <li><a href="#tab-certificates" class="tab-link" data-translate="tabCertificates"><i class="fas fa-certificate"></i> الشهادات<div class="tab-star tab-star-1"><svg
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-3"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-4"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-6"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                    </a></li>
                <li><a href="#tab-education" class="tab-link" data-translate="tabEducation"><i class="fas fa-graduation-cap"></i> التعليم<div class="tab-star tab-star-1"><svg
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-3"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-4"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-6"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                    </a></li>
                <li><a href="#tab-info" class="tab-link" data-translate="tabInfo"><i class="fas fa-calendar-check"></i> الأحداث<div class="tab-star tab-star-1"><svg
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-3"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-4"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                        <div class="tab-star tab-star-6"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 784.11 815.53">
                                <path class="fil0"
                                    d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.05,-407.78z" />
                            </svg></div>
                    </a></li>
            </ul>
        </div>
    </section>

    <!-- ====== MAIN CONTENT ====== -->
    <section class="main-content">
        <div class="container">
            <div class="row g-4">

                <!-- ===== LEFT COLUMN (Posts) ===== -->
                <div class="col-lg-7">
                    <!-- Tab Panels -->
                    <div class="tab-panels">

                        <!-- Home Tab (active) -->
                        <div class="tab-panel active" id="tab-home">

                            <!-- Latest Events Section -->
                            @php
                                $arDays = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                                $homeEvents = collect();
                                foreach ($stage->visits as $v) {
                                    $homeEvents->push(
                                        (object) [
                                            'type' => 'visit',
                                            'title' => $v->title ?? 'زيارة',
                                            'title_ar' => $v->title_ar ?? '',
                                            'title_en' => $v->title_en ?? '',
                                            'record_date' => $v->record_date,
                                            'record_time' => $v->record_time_formatted ?? $v->record_time,
                                            'desc' => $v->other_info ?? '',
                                            'desc_ar' => $v->other_info_ar ?? '',
                                            'desc_en' => $v->other_info_en ?? '',
                                            'badge' => 'زيارة',
                                            'badge_class' => 'evt-milestone',
                                            'doc' => $v->mediaDocument,
                                            'photos' => $v->mediaDocument ? [route('profile.document.embed', [$stage, $v->mediaDocument])] : [],
                                            'video' => null,
                                        ],
                                    );
                                }
                                foreach ($stage->otherEvents as $e) {
                                    $homeEvents->push(
                                        (object) [
                                            'type' => 'event',
                                            'title' => $e->title ?? 'حدث',
                                            'title_ar' => $e->title_ar ?? '',
                                            'title_en' => $e->title_en ?? '',
                                            'record_date' => $e->record_date,
                                            'record_time' => $e->record_time_formatted ?? $e->record_time,
                                            'desc' => $e->other_info ?? '',
                                            'desc_ar' => $e->other_info_ar ?? '',
                                            'desc_en' => $e->other_info_en ?? '',
                                            'badge' => 'حدث',
                                            'badge_class' => 'evt-education',
                                            'doc' => $e->mediaDocument,
                                            'photos' => $e->mediaDocument ? [route('profile.document.embed', [$stage, $e->mediaDocument])] : [],
                                            'video' => null,
                                        ],
                                    );
                                }
                                foreach ($stage->achievements as $a) {
                                    $photos = $a->mediaItems->map(fn($m) => $m->userDocument ? route('profile.document.embed', [$stage, $m->userDocument]) : null)->filter()->values()->all();
                                    if ($a->certificateImageDocument) {
                                        array_unshift($photos, route('profile.document.embed', [$stage, $a->certificateImageDocument]));
                                    }
                                    $homeEvents->push(
                                        (object) [
                                            'type' => 'achievement',
                                            'title' => $a->title ?? 'إنجاز',
                                            'title_ar' => $a->title_ar ?? '',
                                            'title_en' => $a->title_en ?? '',
                                            'record_date' => $a->record_date,
                                            'record_time' => $a->record_time_formatted ?? $a->record_time,
                                            'desc' => $a->place ?? '',
                                            'desc_ar' => $a->place_ar ?? '',
                                            'desc_en' => $a->place_en ?? '',
                                            'badge' => $a->type_label ?? 'إنجاز',
                                            'badge_class' => 'evt-milestone',
                                            'doc' => $a->certificateImageDocument,
                                            'photos' => $photos,
                                            'video' => null,
                                        ],
                                    );
                                }
                                $homeEvents = $homeEvents->sortByDesc('record_date')->take(6);
                            @endphp
                            <div class="home-events-section">
                                <div class="home-events-header">
                                    <h3><i class="fas fa-calendar-check"></i> <span data-translate="homeEventsTitle">آخر الأحداث</span></h3>
                                    <a href="#tab-info" class="home-events-viewall" id="homeViewAllEvents"><i class="fas fa-arrow-left"></i> <span data-translate="viewAllEvents">عرض الكل</span></a>
                                </div>
                                <div class="events-list">
                                    @forelse($homeEvents as $evt)
                                        @php
                                            $dayName = $evt->record_date ? $arDays[$evt->record_date->dayOfWeek] : '';
                                            $dateStr = $evt->record_date ? $evt->record_date->format('Y-m-d') : '';
                                            $thumbUrl = !empty($evt->photos) ? $evt->photos[0] : null;
                                        @endphp
                                        <div class="event-card scroll-reveal" data-event-year="{{ $evt->record_date?->format('Y') ?? '' }}" data-evt-title="{{ $evt->title }}"
                                            data-evt-type="{{ $evt->badge }}" data-evt-badge="{{ $evt->badge }}" data-evt-badge-class="{{ $evt->badge_class }}"
                                            data-evt-stage="{{ $stage->age_in_years ? $stage->age_in_years . ' سنوات' : '' }}" data-evt-day="{{ $dayName }}"
                                            data-evt-date="{{ $dateStr }}" data-evt-time="{{ $evt->record_time ?? '' }}" data-evt-desc="{{ $evt->desc }}"
                                            data-evt-photos='@json($evt->photos ?? [])' data-evt-video="{{ $evt->video ?? '' }}">
                                            <div class="event-card-inner">
                                                <span class="event-badge {{ $evt->badge_class }}"><i class="fas fa-calendar-check"></i> {{ $evt->badge }}</span>
                                                <div class="event-card-thumbnail">
                                                    @if ($thumbUrl)
                                                        <img src="{{ $thumbUrl }}" alt="{{ $evt->title }}">
                                                    @else
                                                        <div style="background:#f0f0f0;height:120px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-image"
                                                                style="font-size:2rem;color:#ccc"></i></div>
                                                    @endif
                                                </div>
                                                <div class="event-card-body">
                                                    <div class="event-meta-tags">
                                                        <span class="event-meta-tag"><i class="fas fa-bookmark"></i> {{ $evt->badge }}</span>
                                                        @if ($stage->age_in_years)
                                                            <span class="event-meta-tag"><i class="fas fa-child"></i> {{ $stage->age_in_years }} <span data-translate="unitYears">سنوات</span></span>
                                                        @endif
                                                    </div>
                                                    <h4 class="event-card-title" data-content-ar="{{ $evt->title_ar ?? $evt->title }}" data-content-en="{{ $evt->title_en ?? $evt->title }}">{{ $evt->title }}</h4>
                                                    <p class="event-card-desc" data-content-ar="{{ Str::limit($evt->desc_ar ?? '', 80) ?: ($evt->title_ar ?? $evt->title) }}" data-content-en="{{ Str::limit($evt->desc_en ?? '', 80) ?: ($evt->title_en ?? $evt->title) }}">{{ Str::limit($evt->desc, 80) ?: $evt->title }}</p>
                                                    <div class="event-date-row">
                                                        <span class="event-day-name">{{ $dayName }}</span>
                                                        @if ($dateStr)
                                                            <span><i class="fas fa-calendar-alt"></i> {{ $dateStr }}</span>
                                                        @endif
                                                        @if ($evt->record_time)
                                                            <span><i class="fas fa-clock"></i> {{ $evt->record_time }}</span>
                                                        @endif
                                                        @if ($stage->age_in_years)
                                                            <span class="event-meta-tag"><i class="fas fa-child"></i> {{ $stage->age_in_years }} <span data-translate="unitYears">سنوات</span></span>
                                                        @endif
                                                    </div>
                                                    <div class="post-media-btns">
                                                        <button class="media-btn media-btn-blue evt-btn-details" data-translate-title="btnDetails"><i class="fas fa-th"></i></button>
                                                        @if (!empty($evt->photos))
                                                            <button class="media-btn media-btn-green evt-btn-photos" data-translate-title="btnPhotos"><i class="fas fa-image"></i></button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center py-4" data-translate="noEvents">لا توجد أحداث بعد.</p>
                                    @endforelse
                                </div>
                            </div>

                        </div><!-- /tab-home -->

                        <!-- Birth Tab -->
                        <div class="tab-panel" id="tab-birth">
                            <div class="birth-section">
                                <!-- Add Birth Data Button -->
                                <div class="birth-header">
                                    <h3 class="birth-section-title"><i class="fas fa-baby"></i> <span data-translate="birthTitle">بيانات الولادة</span></h3>
                                </div>

                                <!-- Main Birth Thumbnail -->


                                <!-- Footprint Section -->
                                @if ($stage->footprintDocument)
                                    <div class="birth-card birth-footprint-card scroll-reveal">
                                        <div class="birth-card-header">
                                            <i class="fas fa-shoe-prints"></i>
                                            <span data-translate="footprintTitle">بصمة القدم</span>
                                        </div>
                                        <div class="footprint-container" id="footprintContainer">
                                            <img src="{{ route('profile.document.embed', [$stage, $stage->footprintDocument]) }}" alt="" data-translate="altFootprint"
                                                style="width:100%;height:220px;object-fit:cover;border-radius:10px;display:block;">
                                        </div>
                                    </div>
                                @endif

                                <!-- Birth Info Card -->
                                <div class="birth-card birth-info-card scroll-reveal">
                                    <div class="birth-card-header">
                                        <i class="fas fa-info-circle"></i>
                                        <span data-translate="birthInfoTitle">معلومات الولادة</span>
                                    </div>
                                    <div class="birth-info-grid" id="birthInfoGrid">
                                        <div class="birth-info-item">
                                            <div class="birth-info-icon"><i class="fas fa-baby"></i></div>
                                            <div class="birth-info-detail">
                                                <span class="birth-info-label" data-translate="birthName">اسم المولود</span>
                                                <span class="birth-info-value" id="birthNameVal" data-content-ar="{{ $stage->name_ar ?? '' }}" data-content-en="{{ $stage->name_en ?? '' }}">{{ $stage->name }}</span>
                                            </div>
                                        </div>
                                        @if ($stage->birth_date)
                                            <div class="birth-info-item">
                                                <div class="birth-info-icon birth-icon-pink"><i class="fas fa-calendar-day"></i></div>
                                                <div class="birth-info-detail">
                                                    <span class="birth-info-label" data-translate="birthDateLabel">تاريخ الولادة</span>
                                                    <span class="birth-info-value" id="birthDateVal">{{ $stage->birth_date->format('Y-m-d') }}@if ($stage->age_in_years !== null)
                                                            ({{ $stage->age_in_years }} سنة)
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($stage->birth_time)
                                            <div class="birth-info-item">
                                                <div class="birth-info-icon birth-icon-purple"><i class="fas fa-clock"></i></div>
                                                <div class="birth-info-detail">
                                                    <span class="birth-info-label" data-translate="birthTimeLabel">وقت الولادة</span>
                                                    <span class="birth-info-value" id="birthTimeVal">{{ $stage->birth_time_formatted }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($stage->height !== null)
                                            <div class="birth-info-item">
                                                <div class="birth-info-icon birth-icon-blue"><i class="fas fa-ruler-vertical"></i></div>
                                                <div class="birth-info-detail">
                                                    <span class="birth-info-label" data-translate="birthHeightLabel">الطول عند الولادة</span>
                                                    <span class="birth-info-value" id="birthHeightVal">{{ $stage->height }} <span data-translate="unitCm">سم</span></span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($stage->weight !== null)
                                            <div class="birth-info-item">
                                                <div class="birth-info-icon birth-icon-green"><i class="fas fa-weight"></i></div>
                                                <div class="birth-info-detail">
                                                    <span class="birth-info-label" data-translate="birthWeightLabel">الوزن عند الولادة</span>
                                                    <span class="birth-info-value" id="birthWeightVal">{{ $stage->weight }} <span data-translate="unitKg">كج</span></span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($stage->birth_place)
                                            <div class="birth-info-item">
                                                <div class="birth-info-icon birth-icon-orange"><i class="fas fa-hospital"></i></div>
                                                <div class="birth-info-detail">
                                                    <span class="birth-info-label" data-translate="birthPlaceLabel">مكان الولادة</span>
                                                    <span class="birth-info-value" id="birthPlaceVal" data-content-ar="{{ $stage->birth_place_ar ?? '' }}" data-content-en="{{ $stage->birth_place_en ?? '' }}">{{ $stage->birth_place }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($stage->blood_type)
                                            <div class="birth-info-item">
                                                <div class="birth-info-icon birth-icon-pink"><i class="fas fa-tint"></i></div>
                                                <div class="birth-info-detail">
                                                    <span class="birth-info-label" data-translate="labelBloodType">فصيلة الدم</span>
                                                    <span class="birth-info-value">{{ $stage->blood_type }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($stage->father_name)
                                            <div class="birth-info-item">
                                                <div class="birth-info-icon birth-icon-blue"><i class="fas fa-male"></i></div>
                                                <div class="birth-info-detail">
                                                    <span class="birth-info-label" data-translate="labelFatherName">اسم الأب</span>
                                                    <span class="birth-info-value" data-content-ar="{{ $stage->father_name_ar ?? '' }}" data-content-en="{{ $stage->father_name_en ?? '' }}">{{ $stage->father_name }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($stage->mother_name)
                                            <div class="birth-info-item">
                                                <div class="birth-info-icon birth-icon-purple"><i class="fas fa-female"></i></div>
                                                <div class="birth-info-detail">
                                                    <span class="birth-info-label" data-translate="labelMotherName">اسم الأم</span>
                                                    <span class="birth-info-value" data-content-ar="{{ $stage->mother_name_ar ?? '' }}" data-content-en="{{ $stage->mother_name_en ?? '' }}">{{ $stage->mother_name }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($stage->doctor)
                                            <div class="birth-info-item">
                                                <div class="birth-info-icon birth-icon-green"><i class="fas fa-user-md"></i></div>
                                                <div class="birth-info-detail">
                                                    <span class="birth-info-label" data-translate="labelDoctor">الطبيب</span>
                                                    <span class="birth-info-value" data-content-ar="{{ $stage->doctor_ar ?? '' }}" data-content-en="{{ $stage->doctor_en ?? '' }}">{{ $stage->doctor }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Birth Photos -->
                                @php
                                    $birthPhotos = collect();
                                    if ($stage->firstPhotoDocument) {
                                        $birthPhotos->push($stage->firstPhotoDocument);
                                    }
                                    $birthPhotos = $birthPhotos->merge($stage->otherPhotos->pluck('userDocument')->filter());
                                @endphp
                                @if ($birthPhotos->isNotEmpty())
                                    <div class="birth-card birth-photos-card scroll-reveal">
                                        <div class="birth-card-header">
                                            <i class="fas fa-camera"></i>
                                            <span data-translate="birthPhotosTitle">صور الولادة</span>
                                        </div>
                                        <div class="birth-photos-grid" id="birthPhotosGrid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                                            @foreach ($birthPhotos as $index => $doc)
                                                <div style="border-radius:10px;overflow:hidden;{{ $index === 0 ? 'height:200px;grid-column:1/-1;' : 'aspect-ratio:1;' }}">
                                                    <img src="{{ route('profile.document.embed', [$stage, $doc]) }}" alt="{{ $stage->name }}" style="width:100%;height:100%;object-fit:cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- Height Tab -->
                        <div class="tab-panel" id="tab-height">
                            <div class="height-section" data-image-group="height">
                                <!-- Header with cartoon ruler -->
                                <div class="height-header">
                                    <div class="height-header-info">
                                        <div class="height-header-icon">
                                            <img src="https://cdn-icons-png.flaticon.com/128/3468/3468377.png" alt="ruler" class="cartoon-icon">
                                        </div>
                                        <div>
                                            <h3 class="height-section-title" data-translate="heightTitle">سجل القياسات</h3>
                                            <p class="height-section-sub" data-translate="heightSubtitle">تتبع نمو طفلك بمرور الوقت</p>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $hwSorted = $stage->heightWeights->sortByDesc('record_date');
                                    $latestHw = $hwSorted->first();
                                    $latestHeight = $latestHw?->height;
                                    $latestWeight = $latestHw?->weight;
                                @endphp
                                <!-- Summary Cards Row -->
                                <div class="height-summary scroll-reveal">
                                    <div class="summary-card summary-card-pink">
                                        <img src="https://cdn-icons-png.flaticon.com/128/3468/3468292.png" alt="" class="summary-cartoon">
                                        <div class="summary-info">
                                            <span class="summary-label" data-translate="latestHeight">آخر طول</span>
                                            <span class="summary-value" id="latestHeightVal">@if($latestHeight){{ $latestHeight }} <span data-translate="unitCm">سم</span>@else-@endif</span>
                                        </div>
                                    </div>
                                    <div class="summary-card summary-card-blue">
                                        <img src="https://cdn-icons-png.flaticon.com/128/3468/3468081.png" alt="" class="summary-cartoon">
                                        <div class="summary-info">
                                            <span class="summary-label" data-translate="latestWidth">آخر وزن</span>
                                            <span class="summary-value" id="latestWeightVal">@if($latestWeight){{ $latestWeight }} <span data-translate="unitKg">كج</span>@else-@endif</span>
                                        </div>
                                    </div>
                                    <div class="summary-card summary-card-green">
                                        <img src="https://cdn-icons-png.flaticon.com/128/2965/2965567.png" alt="" class="summary-cartoon">
                                        <div class="summary-info">
                                            <span class="summary-label" data-translate="totalMeasurements">عدد القياسات</span>
                                            <span class="summary-value" id="totalMeasurementsVal">{{ $stage->heightWeights->count() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Timeline of Measurements -->
                                <div class="measurements-timeline" id="measurementsTimeline">
                                    @php $arDays = ['الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت']; @endphp
                                    @forelse($hwSorted as $idx => $hw)
                                        @php
                                            $dayName = $hw->record_date ? $arDays[$hw->record_date->dayOfWeek] : '';
                                            $dateStr = $hw->record_date ? $hw->record_date->format('Y-m-d') : '';
                                            $imgUrl = $hw->imageDocument ? route('profile.document.embed', [$stage, $hw->imageDocument]) : null;
                                            $revealClass = $idx % 3 === 0 ? 'scroll-reveal' : ($idx % 3 === 1 ? 'scroll-reveal-left' : 'scroll-reveal-right');
                                        @endphp
                                        <div class="measurement-item {{ $revealClass }}">
                                            <div class="measurement-card">
                                                <div class="measurement-day">
                                                    <span class="day-name">{{ $dayName }}</span>
                                                    <span class="day-date"><i class="fas fa-calendar-alt" style="margin-left:4px;font-size:0.7rem"></i> {{ $dateStr }}</span>
                                                    @if ($hw->record_time_formatted ?? $hw->record_time)
                                                        <span class="day-time"><i class="fas fa-clock" style="margin-left:4px;font-size:0.7rem"></i>
                                                            {{ $hw->record_time_formatted ?? $hw->record_time }}</span>
                                                    @endif
                                                    @if ($hw->created_at)
                                                        <div class="measurement-note" style="font-size:0.7rem;color:#888;margin-top:4px">
                                                            <i class="fas fa-edit" style="margin-left:4px;color:#aaa"></i> <span data-translate="recordedAt">تم التسجيل:</span> {{ $arDays[$hw->created_at->dayOfWeek] ?? '' }}
                                                            {{ $hw->created_at->format('d-m-Y') }} – {{ $hw->created_at->format('g:i') }}@if($hw->created_at->hour < 12)<span data-translate="timeAm"> صباحاً</span>@else<span data-translate="timePm"> مساءً</span>@endif
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="measurement-body">
                                                    <img src="https://cdn-icons-png.flaticon.com/128/3468/3468292.png" alt="cartoon" class="measurement-cartoon">
                                                    <div class="measurement-values">
                                                        <div class="measure-stat">
                                                            <div class="measure-stat-icon icon-height"><i class="fa-solid fa-arrow-up"></i></div>
                                                            <span class="measure-stat-val">{{ $hw->height ?? '-' }}</span>
                                                            <span class="measure-stat-unit" data-translate="unitCm">سم</span>
                                                        </div>
                                                        <div class="measure-stat">
                                                            <div class="measure-stat-icon icon-width"><i class="fa-solid fa-dumbbell"></i></div>
                                                            <span class="measure-stat-val">{{ $hw->weight ?? '-' }}</span>
                                                            <span class="measure-stat-unit" data-translate="unitKg">كج</span>
                                                        </div>
                                                        @if ($stage->age_in_years)
                                                            <div class="d-flex gap-2 wrap">
                                                                <div class="measure-stat">
                                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> {{ $stage->age_in_years }} <span data-translate="unitYears">سنوات</span></span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if ($imgUrl)
                                                        <div class="measurement-image-wrap"
                                                            style="cursor:pointer;margin-top:10px;border-radius:10px;overflow:hidden;min-height:180px;background:#f5f5f5;">
                                                            <img src="{{ $imgUrl }}" alt="measurement" class="measurement-photo"
                                                                style="width:100%;min-height:180px;object-fit:cover;border-radius:10px;display:block;transition:transform 0.2s;" loading="lazy">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center py-4" data-translate="noMeasurements">لا توجد قياسات مسجلة بعد.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <!-- Certificates Tab -->
                        <div class="tab-panel" id="tab-certificates">
                            <div class="certificates-section">
                                <!-- Header -->
                                <div class="certs-header">
                                    <div class="certs-header-info">
                                        <div class="certs-header-icon">
                                            <img src="https://cdn-icons-png.flaticon.com/128/3468/3468294.png" alt="certificate" class="cartoon-icon">
                                        </div>
                                        <div>
                                            <h3 class="certs-section-title" data-translate="certsTitle">الشهادات والإنجازات</h3>
                                            <p class="mt-3 certs-section-sub" data-translate="certsSubtitle">وثّق إنجازات وشهادات طفلك</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Certificates Grid -->
                                @php $arDays = ['الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت']; @endphp
                                <div class="certificates-grid" id="certificatesGrid">
                                    @forelse($stage->achievements as $ach)
                                        @php
                                            $dayName = $ach->record_date ? $arDays[$ach->record_date->dayOfWeek] : '';
                                            $dateStr = $ach->record_date ? $ach->record_date->format('Y-m-d') : '';
                                            $thumbUrl = $ach->certificateImageDocument ? route('profile.document.embed', [$stage, $ach->certificateImageDocument]) : null;
                                            $photos = $ach->mediaItems->map(fn($m) => $m->userDocument ? route('profile.document.embed', [$stage, $m->userDocument]) : null)->filter()->values()->all();
                                            if ($ach->certificateImageDocument) {
                                                array_unshift($photos, route('profile.document.embed', [$stage, $ach->certificateImageDocument]));
                                            }
                                            $badgeClass = in_array($ach->type ?? '', ['honor', 'appreciation']) ? 'cert-badge-honor' : 'cert-badge-grad';
                                        @endphp
                                        <div class="cert-card-wrap scroll-reveal" data-cert-title="{{ $ach->title }}" data-cert-place="{{ $ach->place ?? '' }}"
                                            data-cert-details="{{ $ach->place ? $ach->place . ' - ' : '' }}{{ $ach->title }}" data-cert-day="{{ $dayName }}"
                                            data-cert-date="{{ $dateStr }}" data-cert-time="{{ $ach->record_time_formatted ?? ($ach->record_time ?? '') }}"
                                            data-cert-badge="{{ $ach->type_label ?? 'إنجاز' }}" data-cert-photos='@json($photos)' data-cert-video="">
                                            <div class="cert-card">
                                                <div class="cert-thumbnail">
                                                    @if ($thumbUrl)
                                                        <img src="{{ $thumbUrl }}" alt="{{ $ach->title }}" class="cert-main-img">
                                                    @else
                                                        <div
                                                            style="background:linear-gradient(135deg,#f5f5f5,#e0e0e0);height:180px;display:flex;align-items:center;justify-content:center;border-radius:12px;">
                                                            <i class="fas fa-certificate" style="font-size:3rem;color:#bbb"></i></div>
                                                    @endif
                                                    <span class="cert-badge {{ $badgeClass }}"><i class="fas fa-medal"></i> {{ $ach->type_label ?? 'إنجاز' }}</span>
                                                </div>
                                                <div class="cert-card-body">
                                                    @if ($stage->age_in_years)
                                                        <div class="mb-3">
                                                            <span class="event-meta-tag"><i class="fas fa-child"></i> {{ $stage->age_in_years }} <span data-translate="unitYears">سنوات</span></span>
                                                        </div>
                                                    @endif
                                                    <h4 class="cert-title"><i class="fas fa-graduation-cap" style="color:#7c4dff;margin-left:6px"></i> <span data-content-ar="{{ $ach->title_ar ?? '' }}" data-content-en="{{ $ach->title_en ?? '' }}">{{ $ach->title }}</span></h4>
                                                    @if ($ach->place)
                                                        <p class="cert-place"><i class="fas fa-map-marker-alt"></i> <span data-content-ar="{{ $ach->place_ar ?? '' }}" data-content-en="{{ $ach->place_en ?? '' }}">{{ $ach->place }}</span></p>
                                                    @endif
                                                    <div class="cert-date-info">
                                                        <span class="cert-day-name">{{ $dayName }}</span>
                                                        @if ($dateStr)
                                                            <span class="cert-date"><i class="fas fa-calendar-alt"></i> {{ $dateStr }}</span>
                                                        @endif
                                                        @if ($ach->record_time_formatted ?? $ach->record_time)
                                                            <span class="cert-time"><i class="fas fa-clock"></i> {{ $ach->record_time_formatted ?? $ach->record_time }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="post-media-btns">
                                                        <button class="media-btn media-btn-blue cert-btn-details" data-translate-title="btnDetails"><i class="fas fa-th"></i></button>
                                                        @if (!empty($photos))
                                                            <button class="media-btn media-btn-green cert-btn-photos" data-translate-title="btnPhotos"><i class="fas fa-image"></i></button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center py-4 w-100" data-translate="noCertificates">لا توجد شهادات أو إنجازات مسجلة بعد.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <!-- Education Tab -->
                        <div class="tab-panel" id="tab-education">
                            <div class="education-section">
                                <!-- Header -->
                                <div class="edu-header">
                                    <div class="edu-header-info">
                                        <div class="edu-header-icon">
                                            <img src="https://cdn-icons-png.flaticon.com/128/3468/3468254.png" alt="education" class="cartoon-icon">
                                        </div>
                                        <div>
                                            {{-- <h3 class="edu-section-title">المراحل التعليمية</h3> --}}
                                            <p class="edu-section-sub mt-1" data-translate="eduSectionSub">بطاقة كل سنة دراسية</p>
                                        </div>
                                    </div>
                                </div>

                                @include('profile_pages.components.education-years', ['educationYears' => $educationYears ?? [], 'stage' => $stage])
                            </div>
                        </div>
                        <!-- Info Tab -->
                        <div class="tab-panel" id="tab-info">
                            @php
                                $arDays = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                                $allEvents = collect();
                                foreach ($stage->visits as $v) {
                                    $allEvents->push(
                                        (object) [
                                            'type' => 'visit',
                                            'title' => $v->title ?? 'زيارة',
                                            'title_ar' => $v->title_ar ?? '',
                                            'title_en' => $v->title_en ?? '',
                                            'record_date' => $v->record_date,
                                            'record_time' => $v->record_time_formatted ?? $v->record_time,
                                            'desc' => $v->other_info ?? '',
                                            'desc_ar' => $v->other_info_ar ?? '',
                                            'desc_en' => $v->other_info_en ?? '',
                                            'badge' => 'زيارة',
                                            'badge_class' => 'evt-milestone',
                                            'doc' => $v->mediaDocument,
                                            'photos' => $v->mediaDocument ? [route('profile.document.embed', [$stage, $v->mediaDocument])] : [],
                                            'video' => null,
                                        ],
                                    );
                                }
                                foreach ($stage->otherEvents as $e) {
                                    $allEvents->push(
                                        (object) [
                                            'type' => 'event',
                                            'title' => $e->title ?? 'حدث',
                                            'title_ar' => $e->title_ar ?? '',
                                            'title_en' => $e->title_en ?? '',
                                            'record_date' => $e->record_date,
                                            'record_time' => $e->record_time_formatted ?? $e->record_time,
                                            'desc' => $e->other_info ?? '',
                                            'desc_ar' => $e->other_info_ar ?? '',
                                            'desc_en' => $e->other_info_en ?? '',
                                            'badge' => 'حدث',
                                            'badge_class' => 'evt-education',
                                            'doc' => $e->mediaDocument,
                                            'photos' => $e->mediaDocument ? [route('profile.document.embed', [$stage, $e->mediaDocument])] : [],
                                            'video' => null,
                                        ],
                                    );
                                }
                                foreach ($stage->achievements as $a) {
                                    $photos = $a->mediaItems->map(fn($m) => $m->userDocument ? route('profile.document.embed', [$stage, $m->userDocument]) : null)->filter()->values()->all();
                                    if ($a->certificateImageDocument) {
                                        array_unshift($photos, route('profile.document.embed', [$stage, $a->certificateImageDocument]));
                                    }
                                    $allEvents->push(
                                        (object) [
                                            'type' => 'achievement',
                                            'title' => $a->title ?? 'إنجاز',
                                            'title_ar' => $a->title_ar ?? '',
                                            'title_en' => $a->title_en ?? '',
                                            'record_date' => $a->record_date,
                                            'record_time' => $a->record_time_formatted ?? $a->record_time,
                                            'desc' => $a->place ?? '',
                                            'desc_ar' => $a->place_ar ?? '',
                                            'desc_en' => $a->place_en ?? '',
                                            'badge' => $a->type_label ?? 'إنجاز',
                                            'badge_class' => 'evt-milestone',
                                            'doc' => $a->certificateImageDocument,
                                            'photos' => $photos,
                                            'video' => null,
                                        ],
                                    );
                                }
                                $allEvents = $allEvents->sortByDesc('record_date');
                                $eventYears = $allEvents->pluck('record_date')->filter()->map(fn($d) => $d->format('Y'))->unique()->sort()->values();
                            @endphp
                            <div class="events-section">
                                <!-- Header -->
                                <div class="events-header">
                                    <div class="events-header-info">
                                        <div class="events-header-icon">
                                            <img src="https://cdn-icons-png.flaticon.com/128/3468/3468377.png" alt="events" class="img-fluid cartoon-icon">
                                        </div>
                                        <div>
                                            <h3 class="events-section-title" data-translate="eventsTitle">الأحداث</h3>
                                            <p class="events-section-sub" data-translate="eventsSubtitle">جميع الأحداث والمناسبات المهمة</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Year Filter -->
                                <div class="mt-4 events-filter">
                                    <button class="events-filter-btn active" data-year="all"><i class="fas fa-layer-group"></i> <span data-translate="filterAll">الكل</span></button>
                                    @foreach ($eventYears as $yr)
                                        <button class="events-filter-btn" data-year="{{ $yr }}">{{ (int)$yr - 1 }} - {{ $yr }}</button>
                                    @endforeach
                                </div>

                                <!-- Events List -->
                                <div class="events-list" id="eventsList">

                                    @forelse($allEvents as $evt)
                                        @php
                                            $dayName = $evt->record_date ? $arDays[$evt->record_date->dayOfWeek] : '';
                                            $dateStr = $evt->record_date ? $evt->record_date->format('Y-m-d') : '';
                                            $thumbUrl = !empty($evt->photos) ? $evt->photos[0] : null;
                                        @endphp
                                        <div class="event-card scroll-reveal" data-event-year="{{ $evt->record_date?->format('Y') ?? '' }}" data-evt-title="{{ $evt->title }}"
                                            data-evt-type="{{ $evt->badge }}" data-evt-badge="{{ $evt->badge }}" data-evt-badge-class="{{ $evt->badge_class }}"
                                            data-evt-stage="{{ $stage->age_in_years ? $stage->age_in_years . ' سنوات' : '' }}" data-evt-day="{{ $dayName }}"
                                            data-evt-date="{{ $dateStr }}" data-evt-time="{{ $evt->record_time ?? '' }}" data-evt-desc="{{ $evt->desc }}"
                                            data-evt-photos='@json($evt->photos ?? [])' data-evt-video="{{ $evt->video ?? '' }}">
                                            <div class="event-card-inner">
                                                <span class="event-badge {{ $evt->badge_class }}"><i class="fas fa-calendar-check"></i> {{ $evt->badge }}</span>
                                                <div class="event-card-thumbnail">
                                                    @if ($thumbUrl)
                                                        <img src="{{ $thumbUrl }}" alt="{{ $evt->title }}" loading="lazy">
                                                    @else
                                                        <div style="background:#f0f0f0;height:120px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-image"
                                                                style="font-size:2rem;color:#ccc"></i></div>
                                                    @endif
                                                </div>
                                                <div class="event-card-body">
                                                    <div class="event-meta-tags">
                                                        <span class="event-meta-tag"><i class="fas fa-bookmark"></i> {{ $evt->badge }}</span>
                                                        @if ($stage->age_in_years)
                                                            <span class="event-meta-tag"><i class="fas fa-child"></i> {{ $stage->age_in_years }} <span data-translate="unitYears">سنوات</span></span>
                                                        @endif
                                                    </div>
                                                    <h4 class="event-card-title" data-content-ar="{{ $evt->title_ar ?? $evt->title }}" data-content-en="{{ $evt->title_en ?? $evt->title }}">{{ $evt->title }}</h4>
                                                    <p class="event-card-desc" data-content-ar="{{ Str::limit($evt->desc_ar ?? '', 80) ?: ($evt->title_ar ?? $evt->title) }}" data-content-en="{{ Str::limit($evt->desc_en ?? '', 80) ?: ($evt->title_en ?? $evt->title) }}">{{ Str::limit($evt->desc, 80) ?: $evt->title }}</p>
                                                    <div class="event-date-row">
                                                        <span class="event-day-name">{{ $dayName }}</span>
                                                        @if ($dateStr)
                                                            <span><i class="fas fa-calendar-alt"></i> {{ $dateStr }}</span>
                                                        @endif
                                                        @if ($evt->record_time)
                                                            <span><i class="fas fa-clock"></i> {{ $evt->record_time }}</span>
                                                        @endif
                                                        @if ($stage->age_in_years)
                                                            <span class="event-meta-tag"><i class="fas fa-child"></i> {{ $stage->age_in_years }} <span data-translate="unitYears">سنوات</span></span>
                                                        @endif
                                                    </div>
                                                    <div class="post-media-btns">
                                                        <button class="media-btn media-btn-blue evt-btn-details" data-translate-title="btnDetails"><i class="fas fa-th"></i></button>
                                                        @if (!empty($evt->photos))
                                                            <button class="media-btn media-btn-green evt-btn-photos" data-translate-title="btnPhotos"><i class="fas fa-image"></i></button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="events-no-results">
                                            <i class="fas fa-search" style="font-size:2rem;color:var(--text-muted);opacity:0.4;"></i>
                                            <p data-translate="noEventsYet">لا توجد أحداث بعد</p>
                                        </div>
                                    @endforelse

                                    @if ($allEvents->isNotEmpty())
                                        <!-- No results message (shown by JS when filter has no matches) -->
                                        <div class="events-no-results" id="eventsNoResults" style="display:none;">
                                            <i class="fas fa-search" style="font-size:2rem;color:var(--text-muted);opacity:0.4;"></i>
                                            <p data-translate="noEventsInPeriod">لا توجد أحداث في هذه الفترة</p>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== RIGHT COLUMN (Sidebar) ===== -->
                <div class="col-lg-5">

                    <!-- Quick Info Card -->
                    @php
                        $latestHw = $stage->heightWeights->sortByDesc('record_date')->first();
                        $currentHeight = $latestHw?->height ?? $stage->height;
                        $latestAchievement = $stage->achievements->sortByDesc('record_date')->first();
                    @endphp
                    <div class="sidebar-card mb-4 scroll-reveal-right">
                        <div class="sidebar-header">
                            <h5><i class="fas fa-user"></i> <span data-translate="sidebarQuickInfo">نبذة سريعة</span></h5>
                        </div>
                        <div class="sidebar-body">
                            @if ($stage->birth_date)
                                <div class="info-row">
                                    <div class="info-icon"><i class="fas fa-calendar-day"></i></div>
                                    <div class="info-text">
                                        <span class="info-value">{{ $stage->birth_date->format('Y-m-d') }}@if ($stage->age_in_years !== null)
                                                ({{ $stage->age_in_years }} سنة)
                                            @endif
                                        </span>
                                        <span class="info-label" data-translate="labelBirthDate">تاريخ الميلاد</span>
                                    </div>
                                </div>
                            @endif
                            @if ($stage->birth_place)
                                <div class="info-row">
                                    <div class="info-icon info-icon-pink"><i class="fas fa-hospital"></i></div>
                                    <div class="info-text">
                                        <span class="info-value" data-content-ar="{{ $stage->birth_place_ar ?? '' }}" data-content-en="{{ $stage->birth_place_en ?? '' }}">{{ $stage->birth_place }}</span>
                                        <span class="info-label" data-translate="labelBirthPlace">مكان الولادة</span>
                                    </div>
                                </div>
                            @endif
                            @if ($stage->weight !== null)
                                <div class="info-row">
                                    <div class="info-icon info-icon-purple"><i class="fas fa-weight"></i></div>
                                    <div class="info-text">
                                        <span class="info-value">{{ $stage->weight }} <span data-translate="unitKg">كج</span></span>
                                        <span class="info-label" data-translate="labelWeight">الوزن عند الولادة</span>
                                    </div>
                                </div>
                            @endif
                            @if ($currentHeight !== null)
                                <div class="info-row">
                                    <div class="info-icon info-icon-blue"><i class="fas fa-ruler-vertical"></i></div>
                                    <div class="info-text">
                                        <span class="info-value">{{ $currentHeight }} <span data-translate="unitCm">سم</span></span>
                                        <span class="info-label" data-translate="labelCurrentHeight">الطول الحالي</span>
                                    </div>
                                </div>
                            @endif
                            @if ($latestAchievement?->school)
                                <div class="info-row">
                                    <div class="info-icon info-icon-green"><i class="fas fa-university"></i></div>
                                    <div class="info-text">
                                        <span class="info-value" data-content-ar="{{ $latestAchievement->school_ar ?? '' }}" data-content-en="{{ $latestAchievement->school_en ?? '' }}">{{ $latestAchievement->school }}</span>
                                        <span class="info-label" data-translate="labelEducation">التعليم</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Life Book Gallery Card -->
                    <div class="sidebar-card scroll-reveal-right">
                        <div class="sidebar-header">
                            <h5><i class="fas fa-book-open"></i> <span data-translate="lifeBookTitle">كتاب الحياة</span></h5>
                            <a href="#" class="view-all-link" id="openLifeBook"><span data-translate="viewLifeBook">عرض كتاب الحياة</span></a>
                        </div>
                        <div class="sidebar-body">
                            <div class="photo-grid" id="sidebarPhotoGrid">
                                <!-- Auto-populated by JS from all page images -->
                            </div>
                        </div>
                    </div>

                </div><!-- /col sidebar -->
            </div><!-- /row -->
        </div>
    </section>

    <!-- Bubble Container -->
    <div class="bubble-container" id="bubbleContainer"></div>

    <!-- Cert Video Modal -->
    <div class="cert-video-modal" id="certVideoModal">
        <div class="cert-video-overlay"></div>
        <div class="cert-video-container">
            <button class="cert-video-close" id="certVideoClose"><i class="fas fa-times"></i></button>
            <video id="certVideoPlayer" controls playsinline style="width:100%;border-radius:14px;max-height:80vh;"></video>
        </div>
    </div>

    <!-- Picture Frame Modal -->
    <div class="picture-frame-modal" id="pictureFrameModal">
        <div class="picture-frame-modal-overlay"></div>
        <div class="picture-frame-modal-container">
            <button class="picture-frame-close" id="pictureFrameClose"><i class="fas fa-times"></i></button>
            <!-- Carousel Nav -->
            <button class="frame-nav frame-nav-prev" id="framePrev"><i class="fas fa-chevron-right"></i></button>
            <button class="frame-nav frame-nav-next" id="frameNext"><i class="fas fa-chevron-left"></i></button>
            <div class="frame-counter" id="frameCounter" style="display:none;"><span id="frameCurrentIdx">1</span> / <span id="frameTotalIdx">1</span></div>
            <div class="picture-frame-display">
                <div class="picture-frame-inner">
                    <img src="" alt="" id="pictureFrameImg">
                </div>
                <div class="picture-frame-label" id="pictureFrameLabel"></div>
            </div>
            <!-- Info panel below the frame -->
            <div class="picture-frame-info" id="pictureFrameInfo" style="display:none;">
                <div class="frame-info-row" id="frameInfoTab"></div>
                <div class="frame-info-row" id="frameInfoDate"></div>
                <div class="frame-info-row" id="frameInfoStats"></div>
            </div>
        </div>
    </div>

    <!-- Photo Book Carousel Modal -->
    <div class="book-carousel-modal" id="bookCarouselModal">
        <div class="book-carousel-overlay"></div>
        <div class="book-carousel-container">
            <!-- Close -->
            <button class="book-carousel-close" id="bookCarouselClose"><i class="fas fa-times"></i></button>
            <!-- Counter -->
            <div class="book-carousel-counter">
                <span id="bookCurrentIdx">1</span> / <span id="bookTotalIdx">9</span>
            </div>
            <!-- Year Label -->
            <div class="book-year-label" id="bookYearLabel">2019</div>
            <!-- Navigation -->
            <button class="book-nav book-nav-prev" id="bookPrev"><i class="fas fa-chevron-right"></i></button>
            <button class="book-nav book-nav-next" id="bookNext"><i class="fas fa-chevron-left"></i></button>
            <!-- Book -->
            <div class="book-carousel-book">
                <div class="book-page book-page-left" id="bookPageLeft">
                    <img src="" alt="">
                </div>
                <div class="book-spine"></div>
                <div class="book-page book-page-right book-info-page" id="bookPageRight">
                    <div class="book-info-content" id="bookInfoContent">
                        <div class="book-info-source" id="bookInfoSource"><i class="fas fa-baby"></i> الولادة</div>
                        <h4 class="book-info-title" id="bookInfoTitle"></h4>
                        <div class="book-info-date" id="bookInfoDate"></div>
                        <div class="book-info-meta" id="bookInfoMeta"></div>
                    </div>
                </div>
                <!-- Flipping page (animated) -->
                <div class="book-page-flip" id="bookPageFlip">
                    <div class="book-flip-front"><img src="" alt=""></div>
                    <div class="book-flip-back"><img src="" alt=""></div>
                </div>
            </div>
            <!-- Thumbnails Carousel -->
            <div class="book-thumbs-carousel">
                <button class="book-thumbs-nav book-thumbs-prev" id="bookThumbsPrev"><i class="fas fa-chevron-right"></i></button>
                <div class="book-thumbs-viewport" id="bookThumbsViewport">
                    <div class="book-carousel-thumbs" id="bookThumbs"></div>
                </div>
                <button class="book-thumbs-nav book-thumbs-next" id="bookThumbsNext"><i class="fas fa-chevron-left"></i></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>window.stageTheme = @json($stage->theme); window.stageDefaultLang = @json($stage->default_language);</script>
    <script src="{{ asset('assets/js/translation.js') }}"></script>
    <script src="{{ asset('assets/js/upup_main.js') }}"></script>
    <script src="{{ asset('assets/js/scrollAnimations.js') }}"></script>

    <!-- Picture Frame Modal JS -->
    <script>
        (function() {
            const modal = document.getElementById('pictureFrameModal');
            const overlay = modal.querySelector('.picture-frame-modal-overlay');
            const closeBtn = document.getElementById('pictureFrameClose');
            const frameImg = document.getElementById('pictureFrameImg');
            const frameLabel = document.getElementById('pictureFrameLabel');
            const prevBtn = document.getElementById('framePrev');
            const nextBtn = document.getElementById('frameNext');
            const counterEl = document.getElementById('frameCounter');
            const counterCurr = document.getElementById('frameCurrentIdx');
            const counterTotal = document.getElementById('frameTotalIdx');

            let frameImages = [];
            let frameCurrentIdx = 0;

            function showFrameImage(idx) {
                frameCurrentIdx = idx;
                frameImg.src = frameImages[idx];
                if (counterCurr) counterCurr.textContent = idx + 1;
            }

            function openFrameModal(imageSrc, label) {
                // Single image mode
                frameImages = [imageSrc];
                frameCurrentIdx = 0;
                frameImg.src = imageSrc;
                frameLabel.textContent = label || '';
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
                counterEl.style.display = 'none';
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function openFrameCarousel(images, startIdx, label) {
                frameImages = images;
                frameCurrentIdx = startIdx || 0;
                frameImg.src = frameImages[frameCurrentIdx];
                frameLabel.textContent = label || '';
                if (counterCurr) counterCurr.textContent = frameCurrentIdx + 1;
                if (counterTotal) counterTotal.textContent = frameImages.length;
                if (images.length > 1) {
                    prevBtn.style.display = 'flex';
                    nextBtn.style.display = 'flex';
                    counterEl.style.display = 'block';
                } else {
                    prevBtn.style.display = 'none';
                    nextBtn.style.display = 'none';
                    counterEl.style.display = 'none';
                }
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function frameGoPrev() {
                if (frameImages.length < 2) return;
                var idx = (frameCurrentIdx - 1 + frameImages.length) % frameImages.length;
                showFrameImage(idx);
            }

            function frameGoNext() {
                if (frameImages.length < 2) return;
                var idx = (frameCurrentIdx + 1) % frameImages.length;
                showFrameImage(idx);
            }

            prevBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                frameGoPrev();
            });
            nextBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                frameGoNext();
            });

            function closeFrameModal() {
                modal.classList.remove('active');
                document.body.style.overflow = '';
                frameImages = [];
                // Hide info panel when closing
                var infoPanel = document.getElementById('pictureFrameInfo');
                if (infoPanel) infoPanel.style.display = 'none';
            }

            closeBtn.addEventListener('click', closeFrameModal);
            overlay.addEventListener('click', closeFrameModal);

            // Keyboard: escape, arrows
            document.addEventListener('keydown', function(e) {
                if (!modal.classList.contains('active')) return;
                if (e.key === 'Escape') closeFrameModal();
                if (e.key === 'ArrowLeft') frameGoNext();
                if (e.key === 'ArrowRight') frameGoPrev();
            });

            // Make cover gradient clickable
            const coverGradient = document.querySelector('.cover-gradient');
            if (coverGradient) {
                coverGradient.style.cursor = 'pointer';
                coverGradient.addEventListener('click', function() {
                    // Use a default cover image or fetch from profileData
                    const coverImg = 'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=1200&h=600&fit=crop';
                    openFrameModal(coverImg, document.documentElement.dir === 'rtl' ? 'صورة الغلاف' : 'Cover Photo');
                });
            }

            // Make avatar clickable
            const avatarCircle = document.querySelector('.avatar-circle');
            if (avatarCircle) {
                avatarCircle.style.cursor = 'pointer';
                avatarCircle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Check if there's an uploaded profile image, otherwise use default
                    const profileImg = '{{ $avatarUrl }}';
                    openFrameModal(profileImg, document.documentElement.dir === 'rtl' ? 'الصورة الشخصية' : 'Profile Picture');
                });
            }

            // Make measurement-image-wrap images clickable (open wood frame modal with info)
            document.addEventListener('click', function(e) {
                const measurementImg = e.target.closest('.measurement-image-wrap img, .height-section .measurement-entry-photo img, .height-section .measurement-photo img');
                if (measurementImg) {
                    e.stopPropagation();
                    const card = measurementImg.closest('.measurement-card');
                    const infoPanel = document.getElementById('pictureFrameInfo');
                    const infoTab = document.getElementById('frameInfoTab');
                    const infoDate = document.getElementById('frameInfoDate');
                    const infoStats = document.getElementById('frameInfoStats');
                    if (card && infoPanel) {
                        // Extract data from the card
                        const dayName = card.querySelector('.day-name')?.textContent || '';
                        const dayDate = card.querySelector('.day-date')?.textContent?.trim() || '';
                        const dayTime = card.querySelector('.day-time')?.textContent?.trim() || '';
                        const stats = card.querySelectorAll('.measure-stat');
                        let heightVal = '',
                            heightUnit = '',
                            weightVal = '',
                            weightUnit = '';
                        stats.forEach(s => {
                            const icon = s.querySelector('.measure-stat-icon');
                            const val = s.querySelector('.measure-stat-val')?.textContent || '';
                            const unit = s.querySelector('.measure-stat-unit')?.textContent || '';
                            if (icon && icon.classList.contains('icon-height')) {
                                heightVal = val;
                                heightUnit = unit;
                            } else {
                                weightVal = val;
                                weightUnit = unit;
                            }
                        });
                        const isAr = document.documentElement.dir === 'rtl';
                        infoTab.innerHTML = '<i class="fas fa-ruler-vertical"></i> ' + (isAr ? 'سجل القياسات' : 'Measurements');
                        infoDate.innerHTML = '<i class="fas fa-calendar-alt"></i> ' + dayName + ' ' + dayDate + (dayTime ? ' &nbsp;&bull;&nbsp; <i class="fas fa-clock"></i> ' + dayTime : '');
                        let statsHtml = '';
                        if (heightVal) statsHtml += '<span class="frame-stat"><i class="fas fa-arrows-alt-v"></i> ' + (isAr ? 'الطول: ' : 'Height: ') + heightVal + ' ' + heightUnit +
                        '</span>';
                        if (weightVal) statsHtml += '<span class="frame-stat"><i class="fas fa-weight"></i> ' + (isAr ? 'الوزن: ' : 'Weight: ') + weightVal + ' ' + weightUnit + '</span>';
                        infoStats.innerHTML = statsHtml;
                        infoPanel.style.display = 'block';
                    } else if (infoPanel) {
                        infoPanel.style.display = 'none';
                    }
                    openFrameModal(measurementImg.src, document.documentElement.dir === 'rtl' ? 'صورة القياس' : 'Measurement Photo');
                }
            });

            // Hover effect on measurement images
            document.addEventListener('mouseover', function(e) {
                const img = e.target.closest('.measurement-image-wrap img');
                if (img) img.style.transform = 'scale(1.03)';
            });
            document.addEventListener('mouseout', function(e) {
                const img = e.target.closest('.measurement-image-wrap img');
                if (img) img.style.transform = '';
            });

            // Cert buttons: details (fa-th), photos (fa-image), video (fa-video)
            document.addEventListener('click', function(e) {
                const wrap = e.target.closest('.cert-card-wrap');
                if (!wrap) return;

                // --- Details button (fa-th) ---
                if (e.target.closest('.cert-btn-details')) {
                    e.stopPropagation();
                    var title = wrap.dataset.certTitle || '';
                    var place = wrap.dataset.certPlace || '';
                    var details = wrap.dataset.certDetails || '';
                    var day = wrap.dataset.certDay || '';
                    var date = wrap.dataset.certDate || '';
                    var time = wrap.dataset.certTime || '';
                    var badge = wrap.dataset.certBadge || '';
                    var mainImg = wrap.querySelector('.cert-main-img');
                    var imgSrc = mainImg ? mainImg.src : '';
                    var infoPanel = document.getElementById('pictureFrameInfo');
                    var infoTab = document.getElementById('frameInfoTab');
                    var infoDate = document.getElementById('frameInfoDate');
                    var infoStats = document.getElementById('frameInfoStats');
                    if (infoPanel) {
                        var badgeIcon = badge === 'تخرج' ? 'fa-graduation-cap' : 'fa-medal';
                        var badgeColor = badge === 'تخرج' ? '#7c4dff' : '#ff9800';
                        infoTab.innerHTML = '<i class="fas fa-certificate"></i> ' + title +
                            ' <span style="margin-right:8px;padding:2px 10px;border-radius:12px;font-size:0.75rem;color:#fff;background:' + badgeColor + '"><i class="fas ' + badgeIcon +
                            '"></i> ' + badge + '</span>';
                        infoDate.innerHTML = '<i class="fas fa-calendar-alt"></i> ' + day + ' ' + date + (time ? ' &nbsp;\u2022&nbsp; <i class="fas fa-clock"></i> ' + time : '') +
                            '<br><i class="fas fa-map-marker-alt" style="margin-top:6px"></i> ' + place;
                        infoStats.innerHTML = '<span style="font-size:0.85rem;line-height:1.7;color:rgba(255,255,255,0.8);">' + details + '</span>';
                        infoPanel.style.display = 'block';
                    }
                    openFrameModal(imgSrc, title);
                    return;
                }

                // --- Photos button (fa-image) ---
                if (e.target.closest('.cert-btn-photos')) {
                    e.stopPropagation();
                    var photosStr = wrap.dataset.certPhotos || '[]';
                    try {
                        var photos = JSON.parse(photosStr);
                    } catch (ex) {
                        var photos = [];
                    }
                    if (photos.length > 0) {
                        var infoPanel2 = document.getElementById('pictureFrameInfo');
                        if (infoPanel2) infoPanel2.style.display = 'none';
                        openFrameCarousel(photos, 0, wrap.dataset.certTitle || '');
                    }
                    return;
                }

                // --- Video button (fa-video) ---
                if (e.target.closest('.cert-btn-video')) {
                    e.stopPropagation();
                    var videoUrl = wrap.dataset.certVideo || '';
                    if (!videoUrl) return;
                    var videoModal = document.getElementById('certVideoModal');
                    var videoEl = document.getElementById('certVideoPlayer');
                    if (videoModal && videoEl) {
                        videoEl.src = videoUrl;
                        videoModal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                        videoEl.play();
                    }
                    return;
                }
            });

            // Birth photos: open in frame carousel
            document.addEventListener('click', function(e) {
                const birthImg = e.target.closest('.birth-photos-grid img');
                if (!birthImg) return;
                e.stopPropagation();
                const grid = birthImg.closest('.birth-photos-grid');
                const allImgs = Array.from(grid.querySelectorAll('img')).map(function(i) {
                    return i.src;
                });
                const idx = allImgs.indexOf(birthImg.src);
                const isAr = document.documentElement.dir === 'rtl';
                openFrameCarousel(allImgs, idx >= 0 ? idx : 0, isAr ? 'صور الولادة' : 'Birth Photos');
            });

            // Make birth photos show pointer cursor
            document.querySelectorAll('.birth-photos-grid img').forEach(function(img) {
                img.style.cursor = 'pointer';
                img.style.transition = 'transform 0.2s';
                img.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.03)';
                });
                img.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });

            // Cert video modal close
            (function() {
                var vm = document.getElementById('certVideoModal');
                if (!vm) return;
                var vc = document.getElementById('certVideoClose');
                var vo = vm.querySelector('.cert-video-overlay');
                var vp = document.getElementById('certVideoPlayer');

                function closeVideo() {
                    vm.classList.remove('active');
                    document.body.style.overflow = '';
                    if (vp) {
                        vp.pause();
                        vp.src = '';
                    }
                }
                if (vc) vc.addEventListener('click', closeVideo);
                if (vo) vo.addEventListener('click', closeVideo);
                document.addEventListener('keydown', function(e) {
                    if (vm.classList.contains('active') && e.key === 'Escape') closeVideo();
                });
            })();

            // ===== Events Year Filter =====
            (function() {
                var filterBtns = document.querySelectorAll('.events-filter-btn');
                var eventCards = document.querySelectorAll('.event-card[data-event-year]');
                var noResults = document.getElementById('eventsNoResults');
                filterBtns.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        filterBtns.forEach(function(b) {
                            b.classList.remove('active');
                        });
                        btn.classList.add('active');
                        var year = btn.dataset.year;
                        var visible = 0;
                        eventCards.forEach(function(card) {
                            if (year === 'all' || card.dataset.eventYear === year) {
                                card.classList.remove('hidden-by-filter');
                                visible++;
                            } else {
                                card.classList.add('hidden-by-filter');
                            }
                        });
                        if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
                    });
                });
            })();

            // ===== Events Media Buttons =====
            document.addEventListener('click', function(e) {
                var evtCard = e.target.closest('.event-card');
                if (!evtCard) return;
                // Details
                if (e.target.closest('.evt-btn-details')) {
                    e.stopPropagation();
                    var title = evtCard.dataset.evtTitle || '';
                    var type = evtCard.dataset.evtType || '';
                    var badge = evtCard.dataset.evtBadge || '';
                    var stage = evtCard.dataset.evtStage || '';
                    var day = evtCard.dataset.evtDay || '';
                    var date = evtCard.dataset.evtDate || '';
                    var time = evtCard.dataset.evtTime || '';
                    var desc = evtCard.dataset.evtDesc || '';
                    var mainImg = '';
                    try {
                        var ph = JSON.parse(evtCard.dataset.evtPhotos || '[]');
                        mainImg = ph[0] || '';
                    } catch (x) {}
                    var ip = document.getElementById('pictureFrameInfo');
                    var iTab = document.getElementById('frameInfoTab');
                    var iDate = document.getElementById('frameInfoDate');
                    var iStats = document.getElementById('frameInfoStats');
                    if (ip) {
                        iTab.innerHTML = '<i class="fas fa-calendar-check"></i> ' + title +
                            ' <span style="margin-right:8px;padding:2px 10px;border-radius:12px;font-size:0.75rem;color:#fff;background:var(--primary)">' + badge + '</span>';
                        iDate.innerHTML = '<i class="fas fa-calendar-alt"></i> ' + day + ' ' + date + (time ? ' &nbsp;\u2022&nbsp; <i class="fas fa-clock"></i> ' + time : '') +
                            '<br><i class="fas fa-child" style="margin-top:6px"></i> المرحلة: <strong>' + stage + '</strong> &nbsp;•&nbsp; نوع الحدث: <strong>' + type + '</strong>';
                        iStats.innerHTML = '<div style="font-size:0.85rem;line-height:1.7;color:rgba(255,255,255,0.8);">' + desc + '</div>';
                        ip.style.display = 'block';
                    }
                    if (mainImg) openFrameModal(mainImg, title);
                    return;
                }
                // Photos
                if (e.target.closest('.evt-btn-photos')) {
                    e.stopPropagation();
                    try {
                        var photos = JSON.parse(evtCard.dataset.evtPhotos || '[]');
                    } catch (x2) {
                        var photos = [];
                    }
                    if (photos.length > 0) {
                        var ip2 = document.getElementById('pictureFrameInfo');
                        if (ip2) ip2.style.display = 'none';
                        openFrameCarousel(photos, 0, evtCard.dataset.evtTitle || '');
                    }
                    return;
                }
                // Video
                if (e.target.closest('.evt-btn-video')) {
                    e.stopPropagation();
                    var videoUrl = evtCard.dataset.evtVideo || '';
                    if (!videoUrl) return;
                    var vm = document.getElementById('certVideoModal');
                    var vp = document.getElementById('certVideoPlayer');
                    if (vm && vp) {
                        vp.src = videoUrl;
                        vm.classList.add('active');
                        document.body.style.overflow = 'hidden';
                        vp.play();
                    }
                    return;
                }
            });

            // ===== Home "View All Events" link =====
            (function() {
                var viewAllBtn = document.getElementById('homeViewAllEvents');
                if (viewAllBtn) {
                    viewAllBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        var allLinks = document.querySelectorAll('.tab-link');
                        var allPanels = document.querySelectorAll('.tab-panel');
                        allLinks.forEach(function(l) {
                            l.classList.remove('active');
                        });
                        allPanels.forEach(function(p) {
                            p.classList.remove('active');
                        });
                        var evtLink = document.querySelector('.tab-link[href="#tab-info"]');
                        var evtPanel = document.getElementById('tab-info');
                        if (evtLink) evtLink.classList.add('active');
                        if (evtPanel) {
                            evtPanel.classList.add('active');
                            evtPanel.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        }
                    });
                }
            })();

            // ===== Education Stage Tabs =====
            (function() {
                var tabs = document.querySelectorAll('.edu-stage-tab');
                var panels = {
                    early: document.getElementById('eduStageEarly'),
                    primary: document.getElementById('eduStagePrimary')
                };
                tabs.forEach(function(tab) {
                    tab.addEventListener('click', function() {
                        tabs.forEach(function(t) {
                            t.classList.remove('active');
                        });
                        tab.classList.add('active');
                        var stage = tab.dataset.stage;
                        Object.values(panels).forEach(function(p) {
                            if (p) p.classList.remove('active');
                        });
                        if (panels[stage]) panels[stage].classList.add('active');
                    });
                });
            })();

            // ===== Education Event Media Buttons =====
            document.addEventListener('click', function(e) {
                // Edu event-level buttons
                var eventItem = e.target.closest('.edu-event-item');
                if (eventItem) {
                    // Details button
                    if (e.target.closest('.edu-btn-details')) {
                        e.stopPropagation();
                        var yearCard = eventItem.closest('.edu-year-card');
                        var title = eventItem.dataset.eventTitle || '';
                        var type = eventItem.dataset.eventType || '';
                        var details = eventItem.dataset.eventDetails || '';
                        var date = eventItem.dataset.eventDate || '';
                        var yearTitle = yearCard ? yearCard.dataset.eduTitle || '' : '';
                        var school = yearCard ? yearCard.dataset.eduSchool || '' : '';
                        var grade = yearCard ? yearCard.dataset.eduGrade || '' : '';
                        var height = yearCard ? yearCard.dataset.eduHeight || '' : '';
                        var weight = yearCard ? yearCard.dataset.eduWeight || '' : '';
                        var mainImg = '';
                        try {
                            var photos = JSON.parse(eventItem.dataset.eventPhotos || '[]');
                            mainImg = photos[0] || '';
                        } catch (x) {}

                        var infoPanel = document.getElementById('pictureFrameInfo');
                        var infoTab = document.getElementById('frameInfoTab');
                        var infoDate = document.getElementById('frameInfoDate');
                        var infoStats = document.getElementById('frameInfoStats');
                        if (infoPanel) {
                            var typeColor = type === 'تكريم' ? '#ff9800' : type === 'تخرج' ? '#4caf50' : '#7c4dff';
                            infoTab.innerHTML = '<i class="fas fa-school"></i> ' + yearTitle +
                                ' <span style="margin-right:8px;padding:2px 10px;border-radius:12px;font-size:0.75rem;color:#fff;background:' + typeColor + '">' + type + '</span>';
                            var dateHtml = '<i class="fas fa-calendar-alt"></i> ' + date;
                            if (school) dateHtml += '<br><i class="fas fa-map-marker-alt" style="margin-top:6px"></i> ' + school;
                            if (grade) dateHtml += ' &nbsp;•&nbsp; التقدير: <strong>' + grade + '</strong>';
                            infoDate.innerHTML = dateHtml;
                            var statsHtml = '';
                            if (height) statsHtml += '<span class="frame-stat"><i class="fas fa-ruler-vertical"></i> ' + height + '</span>';
                            if (weight) statsHtml += '<span class="frame-stat"><i class="fas fa-weight"></i> ' + weight + '</span>';
                            statsHtml += '<div style="margin-top:8px;font-size:0.85rem;line-height:1.7;color:rgba(255,255,255,0.8);">' + title + '<br>' + details + '</div>';
                            infoStats.innerHTML = statsHtml;
                            infoPanel.style.display = 'block';
                        }
                        if (mainImg) openFrameModal(mainImg, title);
                        return;
                    }
                    // Photos button
                    if (e.target.closest('.edu-btn-photos')) {
                        e.stopPropagation();
                        try {
                            var photos2 = JSON.parse(eventItem.dataset.eventPhotos || '[]');
                        } catch (x2) {
                            var photos2 = [];
                        }
                        if (photos2.length > 0) {
                            var ip = document.getElementById('pictureFrameInfo');
                            if (ip) ip.style.display = 'none';
                            openFrameCarousel(photos2, 0, eventItem.dataset.eventTitle || '');
                        }
                        return;
                    }
                    // Video button
                    if (e.target.closest('.edu-btn-video')) {
                        e.stopPropagation();
                        var videoUrl = eventItem.dataset.eventVideo || '';
                        if (!videoUrl) return;
                        var vm2 = document.getElementById('certVideoModal');
                        var vp2 = document.getElementById('certVideoPlayer');
                        if (vm2 && vp2) {
                            vp2.src = videoUrl;
                            vm2.classList.add('active');
                            document.body.style.overflow = 'hidden';
                            vp2.play();
                        }
                        return;
                    }
                }
            });

            // Expose functions globally
            window.openPictureFrame = openFrameModal;
            window.openFrameCarousel = openFrameCarousel;
        })();
    </script>

    <!-- Book Carousel JS -->
    <script>
        (function() {
            const modal = document.getElementById('bookCarouselModal');
            const overlay = modal.querySelector('.book-carousel-overlay');
            const closeBtn = document.getElementById('bookCarouselClose');
            const prevBtn = document.getElementById('bookPrev');
            const nextBtn = document.getElementById('bookNext');
            const pageLeft = document.getElementById('bookPageLeft');
            const pageRight = document.getElementById('bookPageRight');
            const pageFlip = document.getElementById('bookPageFlip');
            const flipFront = pageFlip.querySelector('.book-flip-front');
            const flipBack = pageFlip.querySelector('.book-flip-back');
            const thumbsWrap = document.getElementById('bookThumbs');
            const counterCurr = document.getElementById('bookCurrentIdx');
            const counterTotal = document.getElementById('bookTotalIdx');
            const sidebarGrid = document.getElementById('sidebarPhotoGrid');
            const yearLabel = document.getElementById('bookYearLabel');
            const bookInfoSource = document.getElementById('bookInfoSource');
            const bookInfoTitle = document.getElementById('bookInfoTitle');
            const bookInfoDate = document.getElementById('bookInfoDate');
            const bookInfoMeta = document.getElementById('bookInfoMeta');
            const openLifeBookLink = document.getElementById('openLifeBook');
            const thumbsViewport = document.getElementById('bookThumbsViewport');
            const thumbsPrevBtn = document.getElementById('bookThumbsPrev');
            const thumbsNextBtn = document.getElementById('bookThumbsNext');
            const THUMBS_PER_PAGE = 10;
            let thumbPage = 0;

            /* Arabic month to sortable number */
            const monthMap = {
                '\u064a\u0646\u0627\u064a\u0631': '01',
                '\u0641\u0628\u0631\u0627\u064a\u0631': '02',
                '\u0645\u0627\u0631\u0633': '03',
                '\u0623\u0628\u0631\u064a\u0644': '04',
                '\u0645\u0627\u064a\u0648': '05',
                '\u064a\u0648\u0646\u064a\u0648': '06',
                '\u064a\u0648\u0644\u064a\u0648': '07',
                '\u0623\u063a\u0633\u0637\u0633': '08',
                '\u0633\u0628\u062a\u0645\u0628\u0631': '09',
                '\u0623\u0643\u062a\u0648\u0628\u0631': '10',
                '\u0646\u0648\u0641\u0645\u0628\u0631': '11',
                '\u062f\u064a\u0633\u0645\u0628\u0631': '12'
            };

            function parseArabicDate(str) {
                if (!str) return '9999-99-99';
                // Try "15 مارس 2020" format
                var m = str.match(/(\d{1,2})\s+(\S+)\s+(\d{4})/);
                if (m) {
                    var mm = monthMap[m[2]] || '01';
                    return m[3] + '-' + mm + '-' + m[1].padStart(2, '0');
                }
                // Try "28/12/2023" format (dd/mm/yyyy)
                var m2 = str.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);
                if (m2) return m2[3] + '-' + m2[2].padStart(2, '0') + '-' + m2[1].padStart(2, '0');
                // Try just year
                var m3 = str.match(/(\d{4})/);
                if (m3) return m3[1] + '-06-01';
                return '9999-99-99';
            }

            let entries = []; // {src, tab, tabIcon, tabColor, year, sortKey, title, dateDisplay, meta(html)}
            let currentIdx = 0;
            let isFlipping = false;

            /* ---- Collect ALL entries from every tab/section ---- */
            function collectAllEntries() {
                entries = [];

                /* -- Birth Tab -- */
                var birthDate = '15 \u0645\u0627\u0631\u0633 2019';
                var birthSortKey = parseArabicDate(birthDate);
                var birthYear = '2019';
                var birthMeta = '';
                var birthInfoItems = document.querySelectorAll('.birth-info-item');
                birthInfoItems.forEach(function(item) {
                    var label = item.querySelector('.birth-info-label');
                    var value = item.querySelector('.birth-info-value');
                    if (label && value) birthMeta += '<div class="meta-row"><i class="fas fa-circle" style="font-size:5px;color:#baa894;margin-top:7px"></i><span class="meta-label">' + label
                        .textContent + ': </span><span class="meta-value">' + value.textContent + '</span></div>';
                });

                // Footprint image
                var footprintImg = document.querySelector('.birth-footprint-card img');
                if (footprintImg && footprintImg.src) {
                    entries.push({
                        src: footprintImg.src,
                        tab: '\u0627\u0644\u0648\u0644\u0627\u062f\u0629',
                        tabIcon: 'fas fa-baby',
                        tabColor: '#e91e63',
                        year: birthYear,
                        sortKey: birthSortKey,
                        title: '\u0628\u0635\u0645\u0629 \u0627\u0644\u0642\u062f\u0645',
                        dateDisplay: birthDate,
                        meta: birthMeta
                    });
                }
                // Birth photos
                document.querySelectorAll('.birth-photos-grid img').forEach(function(img, i) {
                    if (!img.src) return;
                    entries.push({
                        src: img.src,
                        tab: '\u0627\u0644\u0648\u0644\u0627\u062f\u0629',
                        tabIcon: 'fas fa-baby',
                        tabColor: '#e91e63',
                        year: birthYear,
                        sortKey: birthSortKey + '-' + String(i).padStart(2, '0'),
                        title: '\u0635\u0648\u0631 \u0627\u0644\u0648\u0644\u0627\u062f\u0629 (' + (i + 1) + ')',
                        dateDisplay: birthDate,
                        meta: birthMeta
                    });
                });

                /* -- Height Tab -- */
                document.querySelectorAll('.measurement-item').forEach(function(item) {
                    var dayName = (item.querySelector('.day-name') || {}).textContent || '';
                    var dayDateEl = item.querySelector('.day-date');
                    var dayDate = dayDateEl ? dayDateEl.textContent.replace(/^[\\s\\S]*?\\d/, function(m) {
                        return m;
                    }) : '';
                    // Clean the date text (remove icon text)
                    dayDate = dayDate.replace(/[\u200f\u200e]/g, '').trim();
                    var dayTimeEl = item.querySelector('.day-time');
                    var dayTime = dayTimeEl ? dayTimeEl.textContent.replace(/[\u200f\u200e]/g, '').trim() : '';
                    var sortKey = parseArabicDate(dayDate);
                    var yearMatch = dayDate.match(/(\d{4})/);
                    var year = yearMatch ? yearMatch[1] : '2020';

                    var hVal = '',
                        wVal = '';
                    item.querySelectorAll('.measure-stat').forEach(function(st) {
                        var v = (st.querySelector('.measure-stat-val') || {}).textContent || '';
                        var u = (st.querySelector('.measure-stat-unit') || {}).textContent || '';
                        if (u.includes('\u0633\u0645')) hVal = v + ' ' + u;
                        if (u.includes('\u0643\u062c')) wVal = v + ' ' + u;
                    });
                    var meta = '<div class="meta-row"><i class="fas fa-ruler-vertical"></i><span class="meta-label">\u0627\u0644\u0637\u0648\u0644: </span><span class="meta-value">' + hVal +
                        '</span></div>';
                    meta += '<div class="meta-row"><i class="fas fa-weight"></i><span class="meta-label">\u0627\u0644\u0648\u0632\u0646: </span><span class="meta-value">' + wVal +
                        '</span></div>';

                    var img = item.querySelector('.measurement-photo');
                    if (img && img.src) {
                        entries.push({
                            src: img.src,
                            tab: '\u0627\u0644\u0642\u064a\u0627\u0633\u0627\u062a',
                            tabIcon: 'fas fa-ruler-vertical',
                            tabColor: '#2196f3',
                            year: year,
                            sortKey: sortKey,
                            title: '\u0642\u064a\u0627\u0633 ' + dayDate,
                            dateDisplay: dayName + ' ' + dayDate,
                            meta: meta
                        });
                    }
                });

                /* -- Certificates Tab -- */
                document.querySelectorAll('.cert-card-wrap').forEach(function(card) {
                    var d = card.dataset;
                    var certDate = d.certDate || '';
                    var sortKey = parseArabicDate(certDate);
                    var yearMatch = certDate.match(/(\d{4})/);
                    var year = yearMatch ? yearMatch[1] : '2024';
                    var meta = '<div class="meta-row"><i class="fas fa-map-marker-alt"></i><span class="meta-label">\u0627\u0644\u0645\u0643\u0627\u0646: </span><span class="meta-value">' + (d
                        .certPlace || '') + '</span></div>';
                    meta += '<div class="meta-row"><i class="fas fa-award"></i><span class="meta-label">\u0627\u0644\u0646\u0648\u0639: </span><span class="meta-value">' + (d.certBadge ||
                        '') + '</span></div>';
                    if (d.certDetails) meta += '<div class="meta-row" style="margin-top:6px"><i class="fas fa-align-right"></i><span>' + d.certDetails + '</span></div>';

                    var mainImg = card.querySelector('.cert-main-img');
                    if (mainImg && mainImg.src) {
                        entries.push({
                            src: mainImg.src,
                            tab: '\u0627\u0644\u0634\u0647\u0627\u062f\u0627\u062a',
                            tabIcon: 'fas fa-certificate',
                            tabColor: '#ff9800',
                            year: year,
                            sortKey: sortKey,
                            title: d.certTitle || '',
                            dateDisplay: (d.certDay || '') + ' ' + certDate,
                            meta: meta
                        });
                    }
                    // Additional cert photos from JSON
                    try {
                        var photos = JSON.parse(d.certPhotos || '[]');
                        photos.forEach(function(src, i) {
                            if (src === (mainImg && mainImg.src)) return; // skip main
                            entries.push({
                                src: src,
                                tab: '\u0627\u0644\u0634\u0647\u0627\u062f\u0627\u062a',
                                tabIcon: 'fas fa-certificate',
                                tabColor: '#ff9800',
                                year: year,
                                sortKey: sortKey + '-' + String(i).padStart(2, '0'),
                                title: (d.certTitle || '') + ' (' + (i + 2) + ')',
                                dateDisplay: (d.certDay || '') + ' ' + certDate,
                                meta: meta
                            });
                        });
                    } catch (e) {}
                });

                /* -- Education Tab -- */
                document.querySelectorAll('.edu-event-item').forEach(function(evtItem) {
                    var d = evtItem.dataset;
                    var yearCard = evtItem.closest('.edu-year-card');
                    var yd = yearCard ? yearCard.dataset : {};
                    var evtDate = d.eventDate || '';
                    var sortKey = parseArabicDate(evtDate);
                    var yearMatch = evtDate.match(/(\d{4})/);
                    var year = yearMatch ? yearMatch[1] : '2024';
                    var meta = '<div class="meta-row"><i class="fas fa-school"></i><span class="meta-label">\u0627\u0644\u0645\u062f\u0631\u0633\u0629: </span><span class="meta-value">' + (yd
                        .eduSchool || '') + '</span></div>';
                    meta +=
                        '<div class="meta-row"><i class="fas fa-calendar"></i><span class="meta-label">\u0627\u0644\u0633\u0646\u0629 \u0627\u0644\u062f\u0631\u0627\u0633\u064a\u0629: </span><span class="meta-value">' +
                        (yd.eduDate || '') + '</span></div>';
                    meta += '<div class="meta-row"><i class="fas fa-star"></i><span class="meta-label">\u0627\u0644\u062a\u0642\u062f\u064a\u0631: </span><span class="meta-value">' + (yd
                        .eduGrade || '') + '</span></div>';
                    if (d.eventDetails) meta += '<div class="meta-row" style="margin-top:6px"><i class="fas fa-align-right"></i><span>' + d.eventDetails + '</span></div>';

                    try {
                        var photos = JSON.parse(d.eventPhotos || '[]');
                        photos.forEach(function(src, i) {
                            entries.push({
                                src: src,
                                tab: '\u0627\u0644\u062a\u0639\u0644\u064a\u0645',
                                tabIcon: 'fas fa-graduation-cap',
                                tabColor: '#4caf50',
                                year: year,
                                sortKey: sortKey + '-' + String(i).padStart(2, '0'),
                                title: d.eventTitle || '',
                                dateDisplay: evtDate,
                                meta: meta
                            });
                        });
                    } catch (e) {}
                });

                /* -- Events Tab -- */
                document.querySelectorAll('.event-card[data-event-year]').forEach(function(card) {
                    var d = card.dataset;
                    var evtDate = d.evtDate || '';
                    var sortKey = parseArabicDate(evtDate);
                    var year = d.eventYear || '';
                    var meta =
                        '<div class="meta-row"><i class="fas fa-bookmark"></i><span class="meta-label">\u0646\u0648\u0639 \u0627\u0644\u062d\u062f\u062b: </span><span class="meta-value">' + (d
                            .evtType || '') + '</span></div>';
                    meta += '<div class="meta-row"><i class="fas fa-child"></i><span class="meta-label">\u0627\u0644\u0645\u0631\u062d\u0644\u0629: </span><span class="meta-value">' + (d
                        .evtStage || '') + '</span></div>';
                    if (d.evtBadge) meta +=
                        '<div class="meta-row"><i class="fas fa-tag"></i><span class="meta-label">\u0627\u0644\u062a\u0635\u0646\u064a\u0641: </span><span class="meta-value">' + d.evtBadge +
                        '</span></div>';
                    if (d.evtDesc) meta += '<div class="meta-row" style="margin-top:6px"><i class="fas fa-align-right"></i><span>' + d.evtDesc + '</span></div>';

                    // Main thumbnail
                    var mainImg = card.querySelector('.event-card-thumbnail img');
                    if (mainImg && mainImg.src) {
                        entries.push({
                            src: mainImg.src,
                            tab: '\u0627\u0644\u0623\u062d\u062f\u0627\u062b',
                            tabIcon: 'fas fa-calendar-check',
                            tabColor: '#9c27b0',
                            year: year,
                            sortKey: sortKey,
                            title: d.evtTitle || '',
                            dateDisplay: (d.evtDay || '') + ' ' + evtDate,
                            meta: meta
                        });
                    }
                    // Additional photos from JSON
                    try {
                        var photos = JSON.parse(d.evtPhotos || '[]');
                        photos.forEach(function(src, i) {
                            if (mainImg && src.split('?')[0] === mainImg.src.split('?')[0]) return;
                            entries.push({
                                src: src,
                                tab: '\u0627\u0644\u0623\u062d\u062f\u0627\u062b',
                                tabIcon: 'fas fa-calendar-check',
                                tabColor: '#9c27b0',
                                year: year,
                                sortKey: sortKey + '-' + String(i).padStart(2, '0'),
                                title: (d.evtTitle || '') + ' (' + (i + 2) + ')',
                                dateDisplay: (d.evtDay || '') + ' ' + evtDate,
                                meta: meta
                            });
                        });
                    } catch (e) {}
                });

                // Sort chronologically
                entries.sort(function(a, b) {
                    return a.sortKey.localeCompare(b.sortKey);
                });
                return entries;
            }

            /* ---- Populate sidebar photo grid ---- */
            function populateSidebar() {
                if (!sidebarGrid) return;
                collectAllEntries();
                sidebarGrid.innerHTML = '';
                // Show up to 9 entries spread across time
                var step = Math.max(1, Math.floor(entries.length / 9));
                var shown = [];
                for (var i = 0; i < entries.length && shown.length < 9; i += step) {
                    shown.push(entries[i]);
                }
                shown.forEach(function(entry, i) {
                    var div = document.createElement('div');
                    div.className = 'photo-item';
                    div.innerHTML = '<img src="' + entry.src + '" alt="">';
                    div.style.cursor = 'pointer';
                    div.addEventListener('click', function() {
                        var idx = entries.indexOf(entry);
                        openCarousel(idx >= 0 ? idx : 0);
                    });
                    sidebarGrid.appendChild(div);
                });
            }

            /* ---- Build thumbnail strip ---- */
            function buildThumbs() {
                thumbsWrap.innerHTML = '';
                entries.forEach(function(entry, i) {
                    var t = document.createElement('div');
                    t.className = 'book-thumb' + (i === currentIdx ? ' active' : '');
                    t.innerHTML = '<img src="' + entry.src + '" alt="">';
                    t.addEventListener('click', function() {
                        goTo(i);
                    });
                    thumbsWrap.appendChild(t);
                });
                scrollThumbsToIdx(currentIdx);
            }

            function scrollThumbsToIdx(idx) {
                // Each thumb is 54px + 8px gap = 62px
                var thumbSize = 62;
                var vpWidth = thumbsViewport ? thumbsViewport.offsetWidth : 620;
                var visibleCount = Math.floor(vpWidth / thumbSize) || THUMBS_PER_PAGE;
                thumbPage = Math.floor(idx / visibleCount);
                var offset = thumbPage * visibleCount * thumbSize;
                thumbsWrap.style.transform = 'translateX(' + offset + 'px)';
            }

            if (thumbsPrevBtn) {
                thumbsPrevBtn.addEventListener('click', function() {
                    var thumbSize = 62;
                    var vpWidth = thumbsViewport ? thumbsViewport.offsetWidth : 620;
                    var visibleCount = Math.floor(vpWidth / thumbSize) || THUMBS_PER_PAGE;
                    var maxPage = Math.ceil(entries.length / visibleCount) - 1;
                    thumbPage = Math.min(maxPage, thumbPage + 1);
                    thumbsWrap.style.transform = 'translateX(' + (thumbPage * visibleCount * thumbSize) + 'px)';
                });
            }
            if (thumbsNextBtn) {
                thumbsNextBtn.addEventListener('click', function() {
                    var thumbSize = 62;
                    var vpWidth = thumbsViewport ? thumbsViewport.offsetWidth : 620;
                    var visibleCount = Math.floor(vpWidth / thumbSize) || THUMBS_PER_PAGE;
                    thumbPage = Math.max(0, thumbPage - 1);
                    thumbsWrap.style.transform = 'translateX(' + (thumbPage * visibleCount * thumbSize) + 'px)';
                });
            }

            /* ---- Update book pages ---- */
            function updatePages() {
                var entry = entries[currentIdx];
                if (!entry) return;

                // Left page: image
                pageLeft.querySelector('img').src = entry.src;
                pageLeft.classList.remove('empty-page');

                // Right page: info
                bookInfoSource.innerHTML = '<i class="' + entry.tabIcon + '"></i> ' + entry.tab;
                bookInfoSource.style.background = entry.tabColor;
                bookInfoTitle.textContent = entry.title;
                bookInfoDate.innerHTML = '<i class="fas fa-calendar-alt"></i> ' + entry.dateDisplay;
                bookInfoMeta.innerHTML = entry.meta;

                // Year label
                yearLabel.textContent = entry.year;

                // Counter
                counterCurr.textContent = currentIdx + 1;
                counterTotal.textContent = entries.length;

                // Thumbs active + auto-scroll to active thumb
                thumbsWrap.querySelectorAll('.book-thumb').forEach(function(t, i) {
                    t.classList.toggle('active', i === currentIdx);
                });
                scrollThumbsToIdx(currentIdx);
            }

            /* ---- Flip animation ---- */
            function flipTo(newIdx, direction) {
                if (isFlipping || newIdx < 0 || newIdx >= entries.length || newIdx === currentIdx) return;
                isFlipping = true;

                var oldImg = entries[currentIdx] ? entries[currentIdx].src : '';
                var newImg = entries[newIdx] ? entries[newIdx].src : '';

                if (direction === 'next') {
                    pageFlip.className = 'book-page-flip flip-from-right';
                    pageFlip.style.display = 'block';
                    flipFront.querySelector('img').src = oldImg;
                    flipBack.querySelector('img').src = newImg;
                    flipFront.style.borderRadius = '0 8px 8px 0';
                    flipBack.style.borderRadius = '8px 0 0 8px';
                    requestAnimationFrame(function() {
                        pageFlip.classList.add('flipping');
                    });
                } else {
                    pageFlip.className = 'book-page-flip flip-from-left';
                    pageFlip.style.display = 'block';
                    flipFront.querySelector('img').src = oldImg;
                    flipBack.querySelector('img').src = newImg;
                    flipFront.style.borderRadius = '8px 0 0 8px';
                    flipBack.style.borderRadius = '0 8px 8px 0';
                    requestAnimationFrame(function() {
                        pageFlip.classList.add('flipping');
                    });
                }

                setTimeout(function() {
                    currentIdx = newIdx;
                    updatePages();
                    pageFlip.style.display = 'none';
                    pageFlip.className = 'book-page-flip';
                    isFlipping = false;
                }, 620);
            }

            function goNext() {
                if (currentIdx + 1 < entries.length) flipTo(currentIdx + 1, 'next');
            }

            function goPrev() {
                if (currentIdx - 1 >= 0) flipTo(currentIdx - 1, 'prev');
            }

            function goTo(idx) {
                if (idx === currentIdx || isFlipping) return;
                flipTo(idx, idx > currentIdx ? 'next' : 'prev');
            }

            /* ---- Open carousel ---- */
            function openCarousel(startIdx) {
                if (entries.length === 0) collectAllEntries();
                if (entries.length === 0) return;
                currentIdx = startIdx || 0;
                buildThumbs();
                updatePages();
                pageFlip.style.display = 'none';
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            /* ---- Close ---- */
            function closeCarousel() {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }

            // Modal controls
            closeBtn.addEventListener('click', closeCarousel);
            overlay.addEventListener('click', closeCarousel);
            nextBtn.addEventListener('click', goNext);
            prevBtn.addEventListener('click', goPrev);

            // Keyboard
            document.addEventListener('keydown', function(e) {
                if (!modal.classList.contains('active')) return;
                if (e.key === 'Escape') closeCarousel();
                if (e.key === 'ArrowLeft') goNext();
                if (e.key === 'ArrowRight') goPrev();
            });

            // "عرض كتاب الحياة" link
            if (openLifeBookLink) {
                openLifeBookLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    collectAllEntries();
                    openCarousel(0);
                });
            }

            /* ---- Initialize ---- */
            populateSidebar();

            // Re-bind when tabs change
            document.querySelectorAll('[data-tab]').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    setTimeout(function() {
                        populateSidebar();
                    }, 100);
                });
            });
        })();
    </script>

    <!-- Education Modal Open/Close -->
    <script>
        (function() {
            const modal = document.getElementById('eduModal');
            const openBtn = document.getElementById('addEduBtn');
            const closeBtn = document.getElementById('closeEduModal');
            if (!modal || !openBtn) return;

            function openModal() {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }

            openBtn.addEventListener('click', openModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            modal.querySelector('.modal-overlay')?.addEventListener('click', closeModal);

            const cancelBtn = modal.querySelector('.btn-cancel');
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

            // Delete education event
            document.querySelectorAll('.btn-delete-edu').forEach(btn => {
                btn.addEventListener('click', function() {
                    const card = this.closest('.edu-event-card');
                    if (card) {
                        card.style.transform = 'scale(0.9)';
                        card.style.opacity = '0';
                        setTimeout(() => card.remove(), 300);
                    }
                });
            });
        })();
    </script>

</body>

</html>
