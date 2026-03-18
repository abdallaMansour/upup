<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="pageTitle">مرحلة البلوغ</title>
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts - Alexandria & Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/adults.css') }}">
</head>

<body>

    <!-- ====== TOP NAVBAR ====== -->
    <nav class="top-navbar">
        <div class="container-fluid px-3">
            <div class="navbar-inner">
                <div class="nav-right">
                    <button class="lang-btn" id="langToggleBtn" onclick="toggleLanguage()" data-switch-to-ar="ع" data-switch-to-en="EN">EN</button>
                </div>
                <div class="nav-left">
                    <span class="expiry-badge" data-content-ar="تاريخ الانتهاء: {{ now()->addYear() }}" data-content-en="Expires: {{ now()->addYear() }}">{{ __('profile.expires_at') }}:
                        {{ now()->addYear() }}</span>
                    <a href="#" class="btn-logout">
                        <i class="fas fa-sign-out-alt me-1"></i><span data-content-ar="تسجيل الخروج" data-content-en="Logout">{{ __('profile.logout') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>


    <!-- ====== COVER / PROFILE HEADER ====== -->
    <section class="profile-cover">
        <!-- Gradient Background -->
        <div class="cover-gradient">
            <!-- Rainbow arc decoration -->
        </div>

        <!-- Adults Cover Decoration -->
        <div class="cover-adult-deco">
            <span class="deco-gold-crown"><i class="fas fa-crown"></i></span>
            <span class="deco-gold-gem"><i class="fas fa-gem"></i></span>
            <span class="deco-gold-star deco-gold-star-1"><i class="fas fa-star"></i></span>
            <span class="deco-gold-star deco-gold-star-2"><i class="fas fa-star"></i></span>
            <span class="deco-gold-star deco-gold-star-3"><i class="fas fa-star"></i></span>
            <div class="deco-gold-ring deco-gold-ring-1"></div>
            <div class="deco-gold-ring deco-gold-ring-2"></div>
            <span class="deco-gold-feather"><i class="fas fa-feather-alt"></i></span>
        </div>

        <!-- Profile Info overlay at bottom-right of cover -->
        <div class="cover-profile-info">
            <div class="cover-profile-text">
                <p class="cover-bio">انا اسمي </p>
                <h1 class="cover-name" data-translate="profileName">محمد عبدالله</h1>
                <p class="cover-bio"> و عمري 23 سنة </p>
                <div class="cover-badges" id="coverBadges">
                    <span class="cover-badge">مرحلة البلوغ</span>
                </div>
                <p class="cover-bio">النضج والحكمة في رحلة الحياة</p>
            </div>
            <div class="cover-avatar">
                <div class="avatar-circle">
                    <i class="fas fa-user avatar-icon"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== STATS BAR ====== -->
    <section class="stats-bar">
        <div class="container">
            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-num">24</span>
                    <span class="stat-lbl" data-translate="statPosts">المنشورات</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">156</span>
                    <span class="stat-lbl" data-translate="statPoints">النقاط</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">12</span>
                    <span class="stat-lbl" data-translate="statCategories">الفئات</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">4</span>
                    <span class="stat-lbl" data-translate="statStages">مراحل</span>
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
                <li><a href="#tab-birth" class="tab-link" data-translate="tabBirth"><i class="fas fa-heart"></i> الولادة<div class="tab-star tab-star-1"><svg xmlns="http://www.w3.org/2000/svg"
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
                            <div class="home-events-section">
                                <div class="home-events-header">
                                    <h3><i class="fas fa-calendar-check"></i> آخر الأحداث</h3>
                                    <a href="#tab-info" class="home-events-viewall" id="homeViewAllEvents"><i class="fas fa-arrow-left"></i> عرض الكل</a>
                                </div>
                                <div class="events-list">

                                    <!-- Home Event 1 (Event 5) -->
                                    <div class="event-card scroll-reveal" data-event-year="2024" data-evt-title="أول يوم في الروضة" data-evt-type="تعليم" data-evt-badge="تعليم"
                                        data-evt-badge-class="evt-education" data-evt-stage="5 سنوات" data-evt-day="الأحد" data-evt-date="10-5-1999" data-evt-time="7:30 صباحاً"
                                        data-evt-desc="أول يوم لمحمد في الروضة الدولية. كان متحمساً ومتوتراً قليلاً لكنه سرعان ما تأقلم مع زملائه الجدد. بداية رائعة لمسيرته التعليمية."
                                        data-evt-photos='["https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1577896851231-70ef18881754?w=800&h=500&fit=crop"]'
                                        data-evt-video="">
                                        <div class="event-card-inner">
                                            <span class="event-badge evt-education"><i class="fas fa-school"></i> تعليم</span>
                                            <div class="event-card-thumbnail">
                                                <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&h=300&fit=crop" alt="">
                                            </div>
                                            <div class="event-card-body">
                                                <div class="event-meta-tags">
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> تعليم</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                </div>
                                                <h4 class="event-card-title">أول يوم في الروضة</h4>
                                                <p class="event-card-desc">أول يوم لمحمد في الروضة. بداية رائعة لمسيرته التعليمية مع أصدقاء جدد.</p>
                                                <div class="event-date-row">
                                                    <span class="event-day-name">الأحد</span>
                                                    <span><i class="fas fa-calendar-alt"></i> 2003-3-15</span>
                                                    <span><i class="fas fa-clock"></i> 7:30 صباحاً</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <div class="post-media-btns">
                                                    <button class="media-btn media-btn-blue evt-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                    <button class="media-btn media-btn-green evt-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Home Event 2 (Event 6) -->
                                    <div class="event-card scroll-reveal" data-event-year="2025" data-evt-title="حفل تخرج الروضة" data-evt-type="تخرج" data-evt-badge="إنجاز"
                                        data-evt-badge-class="evt-milestone" data-evt-stage="6 سنوات" data-evt-day="الخميس" data-evt-date="10-5-1999" data-evt-time="6:00 مساءً"
                                        data-evt-desc="تخرج محمد من الروضة بتفوق وامتياز. حفل رائع بحضور الأهل والأصدقاء تضمن عروض مسرحية وأناشيد. لحظات فخر واعتزاز."
                                        data-evt-photos='["https://images.unsplash.com/photo-1627556704290-2b1f5853ff78?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=800&h=500&fit=crop"]'
                                        data-evt-video="https://www.w3schools.com/html/mov_bbb.mp4">
                                        <div class="event-card-inner">
                                            <span class="event-badge evt-milestone"><i class="fas fa-trophy"></i> إنجاز</span>
                                            <div class="event-card-thumbnail">
                                                <img src="https://images.unsplash.com/photo-1627556704290-2b1f5853ff78?w=600&h=300&fit=crop" alt="">
                                            </div>
                                            <div class="event-card-body">
                                                <div class="event-meta-tags">
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> تخرج</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 6 سنوات</span>
                                                </div>
                                                <h4 class="event-card-title">حفل تخرج الروضة</h4>
                                                <p class="event-card-desc">تخرج بتفوق وامتياز. حفل رائع بحضور الأهل والأصدقاء.</p>
                                                <div class="event-date-row">
                                                    <span class="event-day-name">الخميس</span>
                                                    <span><i class="fas fa-calendar-alt"></i> 2003-3-15</span>
                                                    <span><i class="fas fa-clock"></i> 6:00 مساءً</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <div class="post-media-btns">
                                                    <button class="media-btn media-btn-blue evt-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                    <button class="media-btn media-btn-green evt-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                    <button class="media-btn media-btn-dark evt-btn-video" title="فيديو"><i class="fas fa-video"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Home Event 3 (Event 7) -->
                                    <div class="event-card scroll-reveal" data-event-year="2026" data-evt-title="أول يوم في المدرسة الابتدائية" data-evt-type="تعليم" data-evt-badge="بداية جديدة"
                                        data-evt-badge-class="evt-education" data-evt-stage="6 سنوات" data-evt-day="الأحد" data-evt-date="10-5-1999" data-evt-time="6:45 صباحاً"
                                        data-evt-desc="أول يوم لمحمد في المرحلة الابتدائية بمدرسة النخبة الأهلية. ارتدى الزي المدرسي بفخر وكان متحمساً للقاء معلمه الجديد وزملائه."
                                        data-evt-photos='["https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800&h=500&fit=crop"]'
                                        data-evt-video="">
                                        <div class="event-card-inner">
                                            <span class="event-badge evt-education"><i class="fas fa-school"></i> بداية جديدة</span>
                                            <div class="event-card-thumbnail">
                                                <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&h=300&fit=crop" alt="">
                                            </div>
                                            <div class="event-card-body">
                                                <div class="event-meta-tags">
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> تعليم</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 6 سنوات</span>
                                                </div>
                                                <h4 class="event-card-title">أول يوم في المدرسة الابتدائية</h4>
                                                <p class="event-card-desc">أول يوم في الابتدائية. ارتدى الزي المدرسي بفخر ولقاء زملاء جدد.</p>
                                                <div class="event-date-row">
                                                    <span class="event-day-name">الأحد</span>
                                                    <span><i class="fas fa-calendar-alt"></i> 2003-3-15</span>
                                                    <span><i class="fas fa-clock"></i> 6:45 صباحاً</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <div class="post-media-btns">
                                                    <button class="media-btn media-btn-blue evt-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                    <button class="media-btn media-btn-green evt-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                <div class="birth-card birth-footprint-card scroll-reveal">
                                    <div class="birth-card-header">
                                        <i class="fas fa-shoe-prints"></i>
                                        <span data-translate="footprintTitle">بصمة القدم</span>
                                    </div>
                                    <div class="footprint-container" id="footprintContainer">
                                        <img src="https://images.unsplash.com/photo-1560328055-e938bb2ed50a?w=500&h=280&fit=crop" alt="بصمة القدم"
                                            style="width:100%;height:220px;object-fit:cover;border-radius:10px;display:block;">
                                    </div>
                                </div>

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
                                                <span class="birth-info-value" id="birthNameVal">محمد عبدالله العمري</span>
                                            </div>
                                        </div>
                                        <div class="birth-info-item">
                                            <div class="birth-info-icon birth-icon-pink"><i class="fas fa-calendar-day"></i></div>
                                            <div class="birth-info-detail">
                                                <span class="birth-info-label" data-translate="birthDateLabel">تاريخ الولادة</span>
                                                <span class="birth-info-value" id="birthDateVal">2019-7-15 (عشرون سنة)</span>
                                            </div>
                                        </div>
                                        <div class="birth-info-item">
                                            <div class="birth-info-icon birth-icon-purple"><i class="fas fa-clock"></i></div>
                                            <div class="birth-info-detail">
                                                <span class="birth-info-label" data-translate="birthTimeLabel">وقت الولادة</span>
                                                <span class="birth-info-value" id="birthTimeVal">3:45 صباحاً</span>
                                            </div>
                                        </div>
                                        <div class="birth-info-item">
                                            <div class="birth-info-icon birth-icon-blue"><i class="fas fa-ruler-vertical"></i></div>
                                            <div class="birth-info-detail">
                                                <span class="birth-info-label" data-translate="birthHeightLabel">الطول عند الولادة</span>
                                                <span class="birth-info-value" id="birthHeightVal">50 سم</span>
                                            </div>
                                        </div>
                                        <div class="birth-info-item">
                                            <div class="birth-info-icon birth-icon-green"><i class="fas fa-weight"></i></div>
                                            <div class="birth-info-detail">
                                                <span class="birth-info-label" data-translate="birthWeightLabel">الوزن عند الولادة</span>
                                                <span class="birth-info-value" id="birthWeightVal">3.2 كج</span>
                                            </div>
                                        </div>
                                        <div class="birth-info-item">
                                            <div class="birth-info-icon birth-icon-orange"><i class="fas fa-hospital"></i></div>
                                            <div class="birth-info-detail">
                                                <span class="birth-info-label" data-translate="birthPlaceLabel">مكان الولادة</span>
                                                <span class="birth-info-value" id="birthPlaceVal">مستشفى الملك فهد، الرياض</span>
                                            </div>
                                        </div>
                                        <div class="birth-info-item">
                                            <div class="birth-info-icon birth-icon-pink"><i class="fas fa-tint"></i></div>
                                            <div class="birth-info-detail">
                                                <span class="birth-info-label">فصيلة الدم</span>
                                                <span class="birth-info-value">O+</span>
                                            </div>
                                        </div>
                                        <div class="birth-info-item">
                                            <div class="birth-info-icon birth-icon-blue"><i class="fas fa-male"></i></div>
                                            <div class="birth-info-detail">
                                                <span class="birth-info-label">اسم الأب</span>
                                                <span class="birth-info-value">عبدالله محمد العمري</span>
                                            </div>
                                        </div>
                                        <div class="birth-info-item">
                                            <div class="birth-info-icon birth-icon-purple"><i class="fas fa-female"></i></div>
                                            <div class="birth-info-detail">
                                                <span class="birth-info-label">اسم الأم</span>
                                                <span class="birth-info-value">فاطمة أحمد الزهراني</span>
                                            </div>
                                        </div>
                                        <div class="birth-info-item">
                                            <div class="birth-info-icon birth-icon-green"><i class="fas fa-user-md"></i></div>
                                            <div class="birth-info-detail">
                                                <span class="birth-info-label">الطبيب</span>
                                                <span class="birth-info-value">د. خالد محمد العمر</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Birth Photos -->
                                <div class="birth-card birth-photos-card scroll-reveal">
                                    <div class="birth-card-header">
                                        <i class="fas fa-camera"></i>
                                        <span data-translate="birthPhotosTitle">صور الولادة</span>
                                    </div>
                                    <div class="birth-photos-grid" id="birthPhotosGrid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                                        <div style="border-radius:10px;overflow:hidden;height:200px;grid-column:1/-1;"><img
                                                src="https://images.unsplash.com/photo-1519689680058-324335c77eba?w=800&h=360&fit=crop" alt="صورة المولود"
                                                style="width:100%;height:100%;object-fit:cover;"></div>
                                        <div style="border-radius:10px;overflow:hidden;aspect-ratio:1;"><img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=300&h=300&fit=crop"
                                                alt="" style="width:100%;height:100%;object-fit:cover;"></div>
                                        <div style="border-radius:10px;overflow:hidden;aspect-ratio:1;"><img src="https://images.unsplash.com/photo-1515488764276-beab7607c1e6?w=300&h=300&fit=crop"
                                                alt="" style="width:100%;height:100%;object-fit:cover;"></div>
                                        <div style="border-radius:10px;overflow:hidden;aspect-ratio:1;"><img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=300&h=300&fit=crop"
                                                alt="" style="width:100%;height:100%;object-fit:cover;"></div>
                                        <div style="border-radius:10px;overflow:hidden;aspect-ratio:1;"><img src="https://images.unsplash.com/photo-1476703993599-0035a21b17a9?w=300&h=300&fit=crop"
                                                alt="" style="width:100%;height:100%;object-fit:cover;"></div>
                                        <div style="border-radius:10px;overflow:hidden;aspect-ratio:1;"><img src="https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=300&h=300&fit=crop"
                                                alt="" style="width:100%;height:100%;object-fit:cover;"></div>
                                        <div style="border-radius:10px;overflow:hidden;aspect-ratio:1;"><img src="https://images.unsplash.com/photo-1560781590-9e4d15d12a99?w=300&h=300&fit=crop"
                                                alt="" style="width:100%;height:100%;object-fit:cover;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Height Tab -->
                        <div class="tab-panel" id="tab-height">
                            <div class="height-section" data-image-group="height">
                                <!-- Header -->
                                <div class="height-header">
                                    <div class="height-header-info">
                                        <div class="height-header-icon">
                                            <i class="fas fa-ruler-vertical" style="font-size:1.5rem;color:#D4AF37"></i>
                                        </div>
                                        <div>
                                            <h3 class="height-section-title" data-translate="heightTitle">سجل القياسات</h3>
                                            <p class="height-section-sub">تتبع النمو بمرور الوقت</p>

                                        </div>
                                    </div>
                                </div>

                                <!-- Summary Cards Row -->
                                <div class="height-summary scroll-reveal">
                                    <div class="summary-card summary-card-pink">
                                        <i class="fas fa-arrows-alt-v" style="font-size:1.8rem;color:#D4AF37"></i>
                                        <div class="summary-info">
                                            <span class="summary-label" data-translate="latestHeight">آخر طول</span>
                                            <span class="summary-value" id="latestHeightVal">110 سم</span>
                                        </div>
                                    </div>
                                    <div class="summary-card summary-card-blue">
                                        <i class="fas fa-weight" style="font-size:1.8rem;color:#D4AF37"></i>
                                        <div class="summary-info">
                                            <span class="summary-label" data-translate="latestWidth">آخر وزن</span>
                                            <span class="summary-value" id="latestWeightVal">19 كج</span>
                                        </div>
                                    </div>
                                    <div class="summary-card summary-card-green">
                                        <i class="fas fa-chart-line" style="font-size:1.8rem;color:#D4AF37"></i>
                                        <div class="summary-info">
                                            <span class="summary-label" data-translate="totalMeasurements">عدد القياسات</span>
                                            <span class="summary-value" id="totalMeasurementsVal">3</span>
                                        </div>

                                    </div>
                                </div>

                                <!-- Timeline of Measurements -->
                                <div class="measurements-timeline" id="measurementsTimeline">

                                    <!-- Measurement 1 -->
                                    <div class="measurement-item scroll-reveal">
                                        <div class="measurement-card">
                                            <div class="measurement-day">
                                                <span class="day-name">الثلاثاء</span>
                                                <span class="day-date"><i class="fas fa-calendar-alt" style="margin-left:4px;font-size:0.7rem"></i> 2025-3-15</span>
                                                <span class="day-time"><i class="fas fa-clock" style="margin-left:4px;font-size:0.7rem"></i> 10:30 صباحاً</span>
                                                <div class="measurement-note" style="font-size:0.7rem;color:#888;margin-top:4px">
                                                    <i class="fas fa-edit" style="margin-left:4px;color:#aaa"></i> تم التسجيل: الثلاثاء 15-3-2025 – 11:00 صباحاً
                                                </div>
                                            </div>
                                            <div class="measurement-body">
                                                <i class="fas fa-arrows-alt-v" style="font-size:1.5rem;color:#D4AF37;margin-left:8px"></i>
                                                <div class="measurement-values">
                                                    <div class="measure-stat">
                                                        <div class="measure-stat-icon icon-height"><i class="fa-solid fa-arrow-up"></i></div>
                                                        <span class="measure-stat-val">86</span>
                                                        <span class="measure-stat-unit">سم</span>
                                                    </div>
                                                    <div class="measure-stat">
                                                        <div class="measure-stat-icon icon-width"><i class="fa-solid fa-dumbbell"></i></div>
                                                        <span class="measure-stat-val">12</span>
                                                        <span class="measure-stat-unit">كج</span>
                                                    </div>
                                                    <div class="d=flex gap-2 wrap">
                                                        <div class="measure-stat">
                                                            <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                        </div>
                                                        <div class="measure-stat mt-3">
                                                            <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="measurement-image-wrap" style="cursor:pointer;">
                                                    <img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=400&h=250&fit=crop" alt="measurement" class="measurement-photo"
                                                        style="width:100%;border-radius:10px;margin-top:10px;transition:transform 0.2s;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Measurement 2 -->
                                    <div class="measurement-item scroll-reveal-left">
                                        <div class="measurement-card">
                                            <div class="measurement-day">
                                                <span class="day-name">الخميس</span>
                                                <span class="day-date"><i class="fas fa-calendar-alt" style="margin-left:4px;font-size:0.7rem"></i> 2025-3-15</span>
                                                <span class="day-time"><i class="fas fa-clock" style="margin-left:4px;font-size:0.7rem"></i> 9:00 صباحاً</span>
                                                <div class="measurement-note" style="font-size:0.7rem;color:#888;margin-top:4px">
                                                    <i class="fas fa-edit" style="margin-left:4px;color:#aaa"></i> تم التسجيل: الثلاثاء 15-3-2025 – 11:00 صباحاً
                                                </div>
                                            </div>
                                            <div class="measurement-body">
                                                <i class="fas fa-weight" style="font-size:1.5rem;color:#D4AF37;margin-left:8px"></i>
                                                <div class="measurement-values">
                                                    <div class="measure-stat">
                                                        <div class="measure-stat-icon icon-height"><i class="fas fa-ruler-vertical"></i></div>
                                                        <span class="measure-stat-val">98</span>
                                                        <span class="measure-stat-unit">سم</span>
                                                    </div>
                                                    <div class="measure-stat">
                                                        <div class="measure-stat-icon icon-width"><i class="fas fa-weight"></i></div>
                                                        <span class="measure-stat-val">15.5</span>
                                                        <span class="measure-stat-unit">كج</span>
                                                    </div>
                                                    <div class="d=flex gap-2 wrap">
                                                        <div class="measure-stat">
                                                            <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                        </div>
                                                        <div class="measure-stat mt-3">
                                                            <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="measurement-image-wrap" style="cursor:pointer;">
                                                    <img src="https://images.unsplash.com/photo-1515488764276-beab7607c1e6?w=400&h=250&fit=crop" alt="measurement" class="measurement-photo"
                                                        style="width:100%;border-radius:10px;margin-top:10px;transition:transform 0.2s;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Measurement 3 -->
                                    <div class="measurement-item scroll-reveal-right">
                                        <div class="measurement-card">
                                            <div class="measurement-day">
                                                <span class="day-name">الجمعة</span>
                                                <span class="day-date"><i class="fas fa-calendar-alt" style="margin-left:4px;font-size:0.7rem"></i> 2025-3-15</span>
                                                <span class="day-time"><i class="fas fa-clock" style="margin-left:4px;font-size:0.7rem"></i> 4:00 مساءً</span>
                                                <div class="measurement-note" style="font-size:0.7rem;color:#888;margin-top:4px">
                                                    <i class="fas fa-edit" style="margin-left:4px;color:#aaa"></i> تم التسجيل: الثلاثاء 15-3-2025 – 11:00 صباحاً
                                                </div>
                                            </div>
                                            <div class="measurement-body">
                                                <i class="fas fa-ruler-vertical" style="font-size:1.5rem;color:#D4AF37;margin-left:8px"></i>
                                                <div class="measurement-values">
                                                    <div class="measure-stat">
                                                        <div class="measure-stat-icon icon-height"><i class="fas fa-ruler-vertical"></i></div>
                                                        <span class="measure-stat-val">110</span>
                                                        <span class="measure-stat-unit">سم</span>
                                                    </div>
                                                    <div class="measure-stat">
                                                        <div class="measure-stat-icon icon-width"><i class="fas fa-weight"></i></div>
                                                        <span class="measure-stat-val">19</span>
                                                        <span class="measure-stat-unit">كج</span>
                                                    </div>
                                                    <div class="d=flex gap-2 wrap">
                                                        <div class="measure-stat">
                                                            <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                        </div>
                                                        <div class="measure-stat mt-3">
                                                            <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="measurement-image-wrap" style="cursor:pointer;">
                                                    <img src="https://images.unsplash.com/photo-1476703993599-0035a21b17a9?w=400&h=250&fit=crop" alt="measurement" class="measurement-photo"
                                                        style="width:100%;border-radius:10px;margin-top:10px;transition:transform 0.2s;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                            <i class="fas fa-award" style="font-size:1.5rem;color:#D4AF37"></i>
                                        </div>
                                        <div>
                                            <h3 class="certs-section-title" data-translate="certsTitle">الشهادات والإنجازات</h3>
                                            <p class="mt-3 certs-section-sub">وثّق إنجازاتك وشهاداتك</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Certificates Grid -->
                                <div class="certificates-grid" id="certificatesGrid">

                                    <!-- Certificate 2: تخرج -->
                                    <div class="cert-card-wrap scroll-reveal" data-cert-title="شهادة التخرج من الروضة" data-cert-place="روضة الأزهار - الرياض"
                                        data-cert-details="تخرج محمد من مرحلة رياض الأطفال بتفوق وامتياز. أقيم حفل تخرج مميز تضمن عروضاً مسرحية وأناشيد جميلة بحضور جميع أولياء الأمور. لحظات فخر لا تُنسى."
                                        data-cert-day="الخميس" data-cert-date="10-5-2007" data-cert-time="6:00 مساءً" data-cert-badge="تخرج"
                                        data-cert-photos='["https://images.unsplash.com/photo-1627556704290-2b1f5853ff78?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1577896851231-70ef18881754?w=800&h=500&fit=crop"]'
                                        data-cert-video="https://www.w3schools.com/html/mov_bbb.mp4">
                                        <div class="cert-card">

                                            <div class="cert-thumbnail">
                                                <img src="https://images.unsplash.com/photo-1627556704290-2b1f5853ff78?w=600&h=300&fit=crop" alt="شهادة التخرج" class="cert-main-img">
                                                <span class="cert-badge cert-badge-grad"><i class="fas fa-graduation-cap"></i> تخرج</span>
                                            </div>
                                            <div class="cert-card-body">
                                                <div class="mb-3">
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <h4 class="cert-title"><i class="fas fa-graduation-cap" style="color:#7c4dff;margin-left:6px"></i> شهادة التخرج من الروضة</h4>
                                                <p class="cert-place"><i class="fas fa-map-marker-alt"></i> روضة الأزهار - الرياض</p>
                                                <div class="cert-date-info">
                                                    <span class="cert-day-name">الخميس</span>
                                                    <span class="cert-date"><i class="fas fa-calendar-alt"></i> 2024-4-21 </span>
                                                    <span class="cert-time"><i class="fas fa-clock"></i> 6:00 مساءً</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <div class="post-media-btns">
                                                    <button class="media-btn media-btn-blue cert-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                    <button class="media-btn media-btn-green cert-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                    <button class="media-btn media-btn-dark cert-btn-video" title="فيديو"><i class="fas fa-video"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Certificate 3: تكريم -->
                                    <div class="cert-card-wrap scroll-reveal" data-cert-title="شهادة حفظ القرآن" data-cert-place="حلقات مسجد الراجحي - الرياض"
                                        data-cert-details="أتمّ محمد حفظ 10 أجزاء من القرآن الكريم بتقدير ممتاز. تم تكريمه من إمام المسجد بحضور والديه. إنجاز عظيم يستحق التوثيق والاحتفاء."
                                        data-cert-day="الجمعة" data-cert-date="10-5-2007" data-cert-time="بعد صلاة الجمعة" data-cert-badge="تكريم"
                                        data-cert-photos='["https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1585036156171-384164a8c6c4?w=800&h=500&fit=crop"]'
                                        data-cert-video="">
                                        <div class="cert-card">
                                            <div class="cert-thumbnail">
                                                <img src="https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=800&h=500&fit=crop" alt="شهادة حفظ القرآن" class="cert-main-img">
                                                <span class="cert-badge cert-badge-honor"><i class="fas fa-medal"></i> تكريم</span>
                                            </div>
                                            <div class="cert-card-body">
                                                <div class="mb-3">
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <h4 class="cert-title"><i class="fas fa-quran" style="color:#00897b;margin-left:6px"></i> شهادة حفظ القرآن</h4>
                                                <p class="cert-place"><i class="fas fa-map-marker-alt"></i> حلقات مسجد الراجحي - الرياض</p>
                                                <div class="cert-date-info">
                                                    <span class="cert-day-name">الجمعة</span>
                                                    <span class="cert-date"><i class="fas fa-calendar-alt"></i> 2024-4-21 </span>
                                                    <span class="cert-time"><i class="fas fa-clock"></i> بعد صلاة الجمعة</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <div class="post-media-btns">
                                                    <button class="media-btn media-btn-blue cert-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                    <button class="media-btn media-btn-green cert-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                            <i class="fas fa-graduation-cap" style="font-size:1.5rem;color:#D4AF37"></i>
                                        </div>
                                        <div>
                                            <h3 class="edu-section-title">المراحل التعليمية</h3>
                                            <p class="edu-section-sub mt-1">بطاقة كل سنة دراسية</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stage Tabs -->
                                <div class="edu-stage-tabs">
                                    <button class="edu-stage-tab active" data-stage="early"><i class="fas fa-university"></i> الجامعة</button>
                                    <button class="edu-stage-tab" data-stage="primary"><i class="fas fa-briefcase"></i> العمل</button>
                                </div>

                                <!-- ===== Stage: الطفولة المبكرة ===== -->
                                <div class="edu-stage-panel active" id="eduStageEarly">
                                    <!-- Stage Header Card -->
                                    <div class="edu-stage-header-card">
                                        <img src="https://cdn-icons-png.flaticon.com/128/2913/2913133.png" alt="" class="edu-stage-icon">
                                        <div>
                                            <h4 class="edu-stage-name">المرحلة الجامعية</h4>
                                            <p class="edu-stage-school">الروضة الدولية بالرياض</p>
                                            <span class="edu-stage-years"><i class="fas fa-calendar"></i> 2023 - 2025</span>
                                        </div>
                                    </div>

                                    <!-- Year 1: روضة - السنة الأولى -->
                                    <div class="edu-year-card scroll-reveal" data-edu-title="روضة - السنة الأولى" data-edu-school="الروضة الدولية بالرياض" data-edu-date="2023 - 2024"
                                        data-edu-grade="ممتاز" data-edu-age="4 سنوات" data-edu-height="103 سم" data-edu-weight="17.8 كج"
                                        data-edu-photos='["https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1577896851231-70ef18881754?w=800&h=500&fit=crop"]'
                                        data-edu-video="">
                                        <div class="edu-year-header" onclick="this.closest('.edu-year-card').classList.toggle('expanded')">
                                            <div class="edu-year-right">
                                                <span class="edu-timeline-dot"></span>
                                                <div>
                                                    <h5 class="edu-year-title">روضة – السنة الأولى</h5>
                                                    <span class="edu-year-meta"><i class="fas fa-child"></i> 4 سنوات &nbsp; <i class="fas fa-calendar"></i> 2023 - 2024</span>
                                                </div>
                                            </div>
                                            <div class="edu-year-left">
                                                <span class="edu-grade-badge grade-excellent">ممتاز<small>التقدير</small></span>
                                                <i class="fas fa-chevron-down edu-expand-icon"></i>
                                            </div>
                                        </div>
                                        <div class="edu-year-body">
                                            <div class="edu-year-stats">
                                                <span class="edu-stat"><i class="fas fa-ruler-vertical"></i> الطول: <strong>103 سم</strong></span>
                                                <span class="edu-stat"><i class="fas fa-weight"></i> الوزن: <strong>17.8 كج</strong></span>
                                            </div>
                                            <!-- Events inside this year -->
                                            <div class="edu-year-events">
                                                <div class="edu-event-item" data-event-type="تكريم" data-event-title="طفل الشهر"
                                                    data-event-details="اختاره المعلمون لأسلوبه الرائع في التعامل مع زملائه وحرصه على المشاركة في الأنشطة الصفية." data-event-date="28-3-2052"
                                                    data-event-photos='["https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=500&fit=crop"]'>
                                                    <span class="edu-event-dot" style="background:#ff9800;"></span>
                                                    <div class="edu-event-content">
                                                        <div class="edu-event-top">
                                                            <strong>طفل الشهر</strong>
                                                            <span class="edu-event-badge-mini" style="background:#fff3e0;color:#e65100;">تكريم</span>
                                                        </div>
                                                        <p>اختاره المعلمون لأسلوبه الرائع</p>
                                                        <span class="edu-event-date-small"><i class="fas fa-calendar-alt"></i>2003-3-25</span>
                                                    </div>
                                                    <div class="post-media-btns">
                                                        <button class="media-btn media-btn-blue edu-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                        <button class="media-btn media-btn-green edu-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                    </div>
                                                </div>

                                                <div class="edu-event-item" data-event-type="حفلة" data-event-title="حفل عيد الميلاد الرابع"
                                                    data-event-details="احتفل أصدقاؤه بعيد ميلاده في الروضة. فرحة كبيرة وهدايا جميلة من الأصدقاء والمعلمات." data-event-date="15/03/2024"
                                                    data-event-photos='["https://images.unsplash.com/photo-1530023367847-a683933f4172?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=800&h=500&fit=crop"]'>
                                                    <span class="edu-event-dot" style="background:#7c4dff;"></span>
                                                    <div class="edu-event-content">
                                                        <div class="edu-event-top">
                                                            <strong>حفل عيد الميلاد الرابع</strong>
                                                            <span class="edu-event-badge-mini" style="background:#ede7f6;color:#4a148c;">حفلة</span>
                                                        </div>
                                                        <p>احتفل أصدقاؤه بعيد ميلاده</p>
                                                        <span class="edu-event-date-small"><i class="fas fa-calendar-alt"></i>2003-3-25</span>
                                                    </div>
                                                    <div class="post-media-btns">
                                                        <button class="media-btn media-btn-blue edu-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                        <button class="media-btn media-btn-green edu-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Year 2: روضة - السنة الثانية -->
                                    <div class="edu-year-card scroll-reveal" data-edu-title="روضة - السنة الثانية" data-edu-school="الروضة الدولية بالرياض" data-edu-date="2024 - 2025"
                                        data-edu-grade="ممتاز" data-edu-age="5 سنوات" data-edu-height="110 سم" data-edu-weight="19.5 كج"
                                        data-edu-photos='["https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1577896851231-70ef18881754?w=800&h=500&fit=crop"]'
                                        data-edu-video="">
                                        <div class="edu-year-header" onclick="this.closest('.edu-year-card').classList.toggle('expanded')">
                                            <div class="edu-year-right">
                                                <span class="edu-timeline-dot"></span>
                                                <div>
                                                    <h5 class="edu-year-title">روضة – السنة الثانية</h5>
                                                    <span class="edu-year-meta"><i class="fas fa-child"></i> 5 سنوات &nbsp; <i class="fas fa-calendar"></i> 2024 - 2025</span>
                                                </div>
                                            </div>
                                            <div class="edu-year-left">
                                                <span class="edu-grade-badge grade-excellent">ممتاز<small>التقدير</small></span>
                                                <i class="fas fa-chevron-down edu-expand-icon"></i>
                                            </div>
                                        </div>
                                        <div class="edu-year-body">
                                            <div class="edu-year-stats">
                                                <span class="edu-stat"><i class="fas fa-ruler-vertical"></i> الطول: <strong>110 سم</strong></span>
                                                <span class="edu-stat"><i class="fas fa-weight"></i> الوزن: <strong>19.5 كج</strong></span>
                                            </div>
                                            <div class="edu-year-events">
                                                <div class="edu-event-item" data-event-type="تخرج" data-event-title="حفل تخرج الروضة"
                                                    data-event-details="تخرج محمد من مرحلة رياض الأطفال بتفوق وامتياز. أقيم حفل تخرج مميز تضمن عروضاً مسرحية وأناشيد جميلة بحضور أولياء الأمور."
                                                    data-event-date="20/06/2025"
                                                    data-event-photos='["https://images.unsplash.com/photo-1627556704290-2b1f5853ff78?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=800&h=500&fit=crop"]'
                                                    data-event-video="https://www.w3schools.com/html/mov_bbb.mp4">
                                                    <span class="edu-event-dot" style="background:#4caf50;"></span>
                                                    <div class="edu-event-content">
                                                        <div class="edu-event-top">
                                                            <strong>حفل تخرج الروضة</strong>
                                                            <span class="edu-event-badge-mini" style="background:#e8f5e9;color:#1b5e20;">تخرج</span>
                                                        </div>
                                                        <p>تخرج بتفوق وامتياز من الروضة</p>
                                                        <span class="edu-event-date-small"><i class="fas fa-calendar-alt"></i>2003-3-25</span>
                                                    </div>
                                                    <div class="post-media-btns">
                                                        <button class="media-btn media-btn-blue edu-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                        <button class="media-btn media-btn-green edu-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                        <button class="media-btn media-btn-dark edu-btn-video" title="فيديو"><i class="fas fa-video"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===== Stage: الابتدائية ===== -->
                                <div class="edu-stage-panel" id="eduStagePrimary">
                                    <div class="edu-stage-header-card">
                                        <img src="https://cdn-icons-png.flaticon.com/128/2602/2602414.png" alt="" class="edu-stage-icon">
                                        <div>
                                            <h4 class="edu-stage-name">الابتدائية</h4>
                                            <p class="edu-stage-school">مدرسة النخبة الأهلية - الرياض</p>
                                            <span class="edu-stage-years"><i class="fas fa-calendar"></i> 2025 - 2031</span>
                                        </div>
                                    </div>

                                    <!-- أولى ابتدائي -->
                                    <div class="edu-year-card scroll-reveal" data-edu-title="أولى ابتدائي" data-edu-school="مدرسة النخبة الأهلية - الرياض" data-edu-date="2025 - 2026"
                                        data-edu-grade="ممتاز" data-edu-age="6 سنوات" data-edu-height="115 سم" data-edu-weight="21 كج"
                                        data-edu-photos='["https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800&h=500&fit=crop"]'
                                        data-edu-video="">
                                        <div class="edu-year-header" onclick="this.closest('.edu-year-card').classList.toggle('expanded')">
                                            <div class="edu-year-right">
                                                <span class="edu-timeline-dot"></span>
                                                <div>
                                                    <h5 class="edu-year-title">أولى ابتدائي</h5>
                                                    <span class="edu-year-meta"><i class="fas fa-child"></i> 6 سنوات &nbsp; <i class="fas fa-calendar"></i> 2025 - 2026</span>
                                                </div>
                                            </div>
                                            <div class="edu-year-left">
                                                <span class="edu-grade-badge grade-excellent">ممتاز<small>التقدير</small></span>
                                                <i class="fas fa-chevron-down edu-expand-icon"></i>
                                            </div>
                                        </div>
                                        <div class="edu-year-body">
                                            <div class="edu-year-stats">
                                                <span class="edu-stat"><i class="fas fa-ruler-vertical"></i> الطول: <strong>115 سم</strong></span>
                                                <span class="edu-stat"><i class="fas fa-weight"></i> الوزن: <strong>21 كج</strong></span>
                                            </div>
                                            <div class="edu-year-events">
                                                <div class="edu-event-item" data-event-type="تكريم" data-event-title="الطالب المثالي - الفصل الأول"
                                                    data-event-details="تم تكريم محمد كأفضل طالب في الصف الأول الابتدائي للفصل الدراسي الأول. حصل على شهادة تقدير ودرع التميز."
                                                    data-event-date="15/01/2026"
                                                    data-event-photos='["https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1588072432836-e10032774350?w=800&h=500&fit=crop"]'>
                                                    <span class="edu-event-dot" style="background:#ff9800;"></span>
                                                    <div class="edu-event-content">
                                                        <div class="edu-event-top">
                                                            <strong>الطالب المثالي - الفصل الأول</strong>
                                                            <span class="edu-event-badge-mini" style="background:#fff3e0;color:#e65100;">تكريم</span>
                                                        </div>
                                                        <p>تكريم كأفضل طالب في الصف</p>
                                                        <span class="edu-event-date-small"><i class="fas fa-calendar-alt"></i>2003-3-25</span>
                                                    </div>
                                                    <div class="post-media-btns">
                                                        <button class="media-btn media-btn-blue edu-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                        <button class="media-btn media-btn-green edu-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ثاني ابتدائي (placeholder) -->
                                    <div class="edu-year-card scroll-reveal" data-edu-title="ثاني ابتدائي" data-edu-school="مدرسة النخبة الأهلية - الرياض" data-edu-date="2026 - 2027"
                                        data-edu-grade="" data-edu-age="7 سنوات" data-edu-height="" data-edu-weight="" data-edu-photos='[]' data-edu-video="">
                                        <div class="edu-year-header" onclick="this.closest('.edu-year-card').classList.toggle('expanded')">
                                            <div class="edu-year-right">
                                                <span class="edu-timeline-dot dot-upcoming"></span>
                                                <div>
                                                    <h5 class="edu-year-title">ثاني ابتدائي</h5>
                                                    <span class="edu-year-meta"><i class="fas fa-child"></i> 7 سنوات &nbsp; <i class="fas fa-calendar"></i> 2026 - 2027</span>
                                                </div>
                                            </div>
                                            <div class="edu-year-left">
                                                <i class="fas fa-chevron-down edu-expand-icon"></i>
                                            </div>
                                        </div>
                                        <div class="edu-year-body">
                                            <p style="text-align:center;color:var(--text-muted);padding:20px 0;"><i class="fas fa-hourglass-half" style="margin-left:6px;"></i> لم تبدأ بعد</p>
                                        </div>
                                    </div>

                                    <!-- ثالث - سادس ابتدائي (placeholders) -->
                                    <div class="edu-year-card scroll-reveal" data-edu-title="ثالث ابتدائي" data-edu-date="2027 - 2028" data-edu-age="8 سنوات" data-edu-grade=""
                                        data-edu-photos='[]' data-edu-video="">
                                        <div class="edu-year-header" onclick="this.closest('.edu-year-card').classList.toggle('expanded')">
                                            <div class="edu-year-right">
                                                <span class="edu-timeline-dot dot-upcoming"></span>
                                                <div>
                                                    <h5 class="edu-year-title">ثالث ابتدائي</h5><span class="edu-year-meta"><i class="fas fa-child"></i> 8 سنوات &nbsp; <i
                                                            class="fas fa-calendar"></i> 2027 - 2028</span>
                                                </div>
                                            </div>
                                            <div class="edu-year-left"><i class="fas fa-chevron-down edu-expand-icon"></i></div>
                                        </div>
                                        <div class="edu-year-body">
                                            <p style="text-align:center;color:var(--text-muted);padding:20px 0;"><i class="fas fa-hourglass-half" style="margin-left:6px;"></i> لم تبدأ بعد</p>
                                        </div>
                                    </div>
                                    <div class="edu-year-card scroll-reveal" data-edu-title="رابع ابتدائي" data-edu-date="2028 - 2029" data-edu-age="9 سنوات" data-edu-grade=""
                                        data-edu-photos='[]' data-edu-video="">
                                        <div class="edu-year-header" onclick="this.closest('.edu-year-card').classList.toggle('expanded')">
                                            <div class="edu-year-right">
                                                <span class="edu-timeline-dot dot-upcoming"></span>
                                                <div>
                                                    <h5 class="edu-year-title">رابع ابتدائي</h5><span class="edu-year-meta"><i class="fas fa-child"></i> 9 سنوات &nbsp; <i
                                                            class="fas fa-calendar"></i> 2028 - 2029</span>
                                                </div>
                                            </div>
                                            <div class="edu-year-left"><i class="fas fa-chevron-down edu-expand-icon"></i></div>
                                        </div>
                                        <div class="edu-year-body">
                                            <p style="text-align:center;color:var(--text-muted);padding:20px 0;"><i class="fas fa-hourglass-half" style="margin-left:6px;"></i> لم تبدأ بعد</p>
                                        </div>
                                    </div>
                                    <div class="edu-year-card scroll-reveal" data-edu-title="خامس ابتدائي" data-edu-date="2029 - 2030" data-edu-age="10 سنوات" data-edu-grade=""
                                        data-edu-photos='[]' data-edu-video="">
                                        <div class="edu-year-header" onclick="this.closest('.edu-year-card').classList.toggle('expanded')">
                                            <div class="edu-year-right">
                                                <span class="edu-timeline-dot dot-upcoming"></span>
                                                <div>
                                                    <h5 class="edu-year-title">خامس ابتدائي</h5><span class="edu-year-meta"><i class="fas fa-child"></i> 10 سنوات &nbsp; <i
                                                            class="fas fa-calendar"></i> 2029 - 2030</span>
                                                </div>
                                            </div>
                                            <div class="edu-year-left"><i class="fas fa-chevron-down edu-expand-icon"></i></div>
                                        </div>
                                        <div class="edu-year-body">
                                            <p style="text-align:center;color:var(--text-muted);padding:20px 0;"><i class="fas fa-hourglass-half" style="margin-left:6px;"></i> لم تبدأ بعد</p>
                                        </div>
                                    </div>
                                    <div class="edu-year-card scroll-reveal" data-edu-title="سادس ابتدائي" data-edu-date="2030 - 2031" data-edu-age="11 سنة" data-edu-grade=""
                                        data-edu-photos='[]' data-edu-video="">
                                        <div class="edu-year-header" onclick="this.closest('.edu-year-card').classList.toggle('expanded')">
                                            <div class="edu-year-right">
                                                <span class="edu-timeline-dot dot-upcoming"></span>
                                                <div>
                                                    <h5 class="edu-year-title">سادس ابتدائي</h5><span class="edu-year-meta"><i class="fas fa-child"></i> 11 سنة &nbsp; <i
                                                            class="fas fa-calendar"></i> 2030 - 2031</span>
                                                </div>
                                            </div>
                                            <div class="edu-year-left"><i class="fas fa-chevron-down edu-expand-icon"></i></div>
                                        </div>
                                        <div class="edu-year-body">
                                            <p style="text-align:center;color:var(--text-muted);padding:20px 0;"><i class="fas fa-hourglass-half" style="margin-left:6px;"></i> لم تبدأ بعد</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Info Tab -->
                        <div class="tab-panel" id="tab-info">
                            <div class="events-section">
                                <!-- Header -->
                                <div class="events-header">
                                    <div class="events-header-info">
                                        <div class="events-header-icon">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div>
                                            <h3 class="events-section-title">الأحداث</h3>
                                            <p class="events-section-sub">جميع الأحداث والمناسبات المهمة</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Year Filter -->
                                <div class="mt-4 events-filter">
                                    <button class="events-filter-btn active" data-year="all"><i class="fas fa-layer-group"></i> الكل</button>
                                    <button class="events-filter-btn" data-year="2020">2019 - 2020</button>
                                    <button class="events-filter-btn" data-year="2021">2020 - 2021</button>
                                    <button class="events-filter-btn" data-year="2022">2021 - 2022</button>
                                    <button class="events-filter-btn" data-year="2023">2022 - 2023</button>
                                    <button class="events-filter-btn" data-year="2024">2023 - 2024</button>
                                    <button class="events-filter-btn" data-year="2025">2024 - 2025</button>
                                    <button class="events-filter-btn" data-year="2026">2025 - 2026</button>
                                </div>

                                <!-- Events List -->
                                <div class="events-list" id="eventsList">

                                    <!-- Event 1 -->
                                    <div class="event-card scroll-reveal" data-event-year="2020" data-evt-title="أول خطوات محمد" data-evt-type="إنجاز" data-evt-badge="إنجاز شخصي"
                                        data-evt-badge-class="evt-milestone" data-evt-stage="الطفولة المبكرة" data-evt-day="الأحد" data-evt-date="15 مارس 2020" data-evt-time="3:30 مساءً"
                                        data-evt-desc="خطا محمد أولى خطواته بمفرده اليوم! كانت لحظة مذهلة ومؤثرة للعائلة بأكملها. بدأ بالمشي متمسكاً بالأثاث ثم تجرأ وخطا 5 خطوات بمفرده نحو والدته."
                                        data-evt-photos='["https://images.unsplash.com/photo-1519340333755-56e9c1d3611c?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1515488764276-beab7607c1e6?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=800&h=500&fit=crop"]'
                                        data-evt-video="">
                                        <div class="event-card-inner">
                                            <span class="event-badge evt-milestone"><i class="fas fa-star"></i> إنجاز شخصي</span>
                                            <div class="event-card-thumbnail">
                                                <img src="https://images.unsplash.com/photo-1519340333755-56e9c1d3611c?w=600&h=300&fit=crop" alt="">
                                            </div>
                                            <div class="event-card-body">
                                                <div class="event-meta-tags">
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> إنجاز</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> الطفولة المبكرة</span>
                                                </div>
                                                <h4 class="event-card-title">أول خطوات محمد</h4>
                                                <p class="event-card-desc">خطا محمد أولى خطواته بمفرده اليوم! كانت لحظة مذهلة ومؤثرة للعائلة بأكملها.</p>
                                                <div class="event-date-row">
                                                    <span class="event-day-name">الأحد</span>
                                                    <span><i class="fas fa-calendar-alt"></i> 2020-8-4</span>
                                                    <span><i class="fas fa-clock"></i> 3:30 مساءً</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <div class="post-media-btns">
                                                    <button class="media-btn media-btn-blue evt-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                    <button class="media-btn media-btn-green evt-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Event 2 -->
                                    <div class="event-card scroll-reveal" data-event-year="2020" data-evt-title="التطعيمات الأساسية الكاملة" data-evt-type="طبي" data-evt-badge="صحة"
                                        data-evt-badge-class="evt-medical" data-evt-stage="سنة واحدة" data-evt-day="الثلاثاء" data-evt-date="5-3-2020" data-evt-time="10:00 صباحاً"
                                        data-evt-desc="أكمل محمد جميع التطعيمات الأساسية المقررة لعمر السنة الأولى. الطبيب أكد أن نموه طبيعي وصحته ممتازة. وزنه 10.5 كجم وطوله 76 سم."
                                        data-evt-photos='["https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1584515933487-779824d29309?w=800&h=500&fit=crop"]'
                                        data-evt-video="">
                                        <div class="event-card-inner">
                                            <span class="event-badge evt-medical"><i class="fas fa-heartbeat"></i> صحة</span>
                                            <div class="event-card-thumbnail">
                                                <img src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=600&h=300&fit=crop" alt="">
                                            </div>
                                            <div class="event-card-body">
                                                <div class="event-meta-tags">
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> طبي</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> سنة واحدة</span>
                                                </div>
                                                <h4 class="event-card-title">التطعيمات الأساسية الكاملة</h4>
                                                <p class="event-card-desc">أكمل محمد جميع التطعيمات الأساسية. الطبيب أكد أن نموه طبيعي وصحته ممتازة.</p>
                                                <div class="event-date-row">
                                                    <span class="event-day-name">الثلاثاء</span>
                                                    <span><i class="fas fa-calendar-alt"></i> 2020-8-4</span>
                                                    <span><i class="fas fa-clock"></i> 10:00 صباحاً</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <div class="post-media-btns">
                                                    <button class="media-btn media-btn-blue evt-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                    <button class="media-btn media-btn-green evt-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Event 3 -->
                                    <div class="event-card scroll-reveal" data-event-year="2022" data-evt-title="حفل عيد الميلاد الثالث" data-evt-type="احتفال" data-evt-badge="اجتماعي"
                                        data-evt-badge-class="evt-social" data-evt-stage="3 سنوات" data-evt-day="السبت" data-evt-date="5-3-2302" data-evt-time="5:00 مساءً"
                                        data-evt-desc="احتفال كبير بعيد ميلاد محمد الثالث مع العائلة والأصدقاء. حضر 25 طفلاً من روضة الأطفال. كعكة جميلة على شكل سيارة. محمد سعيد جداً!"
                                        data-evt-photos='["https://images.unsplash.com/photo-1530023367847-a683933f4172?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1513151233558-d860c5398176?w=800&h=500&fit=crop"]'
                                        data-evt-video="https://www.w3schools.com/html/mov_bbb.mp4">
                                        <div class="event-card-inner">
                                            <span class="event-badge evt-social"><i class="fas fa-users"></i> اجتماعي</span>
                                            <div class="event-card-thumbnail">
                                                <img src="https://images.unsplash.com/photo-1530023367847-a683933f4172?w=600&h=300&fit=crop" alt="">
                                            </div>
                                            <div class="event-card-body">
                                                <div class="event-meta-tags">
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> احتفال</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 3 سنوات</span>
                                                </div>
                                                <h4 class="event-card-title">حفل عيد الميلاد الثالث</h4>
                                                <p class="event-card-desc">احتفال كبير مع العائلة والأصدقاء. كعكة جميلة وألعاب ومسابقات وهدايا.</p>
                                                <div class="event-date-row">
                                                    <span class="event-day-name">السبت</span>
                                                    <span><i class="fas fa-calendar-alt"></i> 2020-8-4</span>
                                                    <span><i class="fas fa-clock"></i> 5:00 مساءً</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <div class="post-media-btns">
                                                    <button class="media-btn media-btn-blue evt-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                    <button class="media-btn media-btn-green evt-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                    <button class="media-btn media-btn-dark evt-btn-video" title="فيديو"><i class="fas fa-video"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Event 7 -->
                                    <div class="event-card scroll-reveal" data-event-year="2026" data-evt-title="أول يوم في المدرسة الابتدائية" data-evt-type="تعليم"
                                        data-evt-badge="بداية جديدة" data-evt-badge-class="evt-education" data-evt-stage="6 سنوات" data-evt-day="الأحد" data-evt-date="7-9-2020"
                                        data-evt-time="6:45 صباحاً"
                                        data-evt-desc="أول يوم لمحمد في المرحلة الابتدائية بمدرسة النخبة الأهلية. ارتدى الزي المدرسي بفخر وكان متحمساً للقاء معلمه الجديد وزملائه."
                                        data-evt-photos='["https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800&h=500&fit=crop","https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800&h=500&fit=crop"]'
                                        data-evt-video="">
                                        <div class="event-card-inner">
                                            <span class="event-badge evt-education"><i class="fas fa-school"></i> بداية جديدة</span>
                                            <div class="event-card-thumbnail">
                                                <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&h=300&fit=crop" alt="">
                                            </div>
                                            <div class="event-card-body">
                                                <div class="event-meta-tags">
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> تعليم</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 6 سنوات</span>
                                                </div>
                                                <h4 class="event-card-title">أول يوم في المدرسة الابتدائية</h4>
                                                <p class="event-card-desc">أول يوم في الابتدائية. ارتدى الزي المدرسي بفخر ولقاء زملاء جدد.</p>
                                                <div class="event-date-row">
                                                    <span class="event-day-name">الأحد</span>
                                                    <span><i class="fas fa-calendar-alt"></i> 7 سبتمبر 2025</span>
                                                    <span><i class="fas fa-clock"></i> 6:45 صباحاً</span>
                                                    <span class="event-meta-tag"><i class="fas fa-child"></i> 5 سنوات</span>
                                                    <span class="event-meta-tag"><i class="fas fa-bookmark"></i> المرحلة الابتدائية</span>
                                                </div>
                                                <div class="post-media-btns">
                                                    <button class="media-btn media-btn-blue evt-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                                    <button class="media-btn media-btn-green evt-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- No results message -->
                                    <div class="events-no-results" id="eventsNoResults" style="display:none;">
                                        <i class="fas fa-search" style="font-size:2rem;color:var(--text-muted);opacity:0.4;"></i>
                                        <p>لا توجد أحداث في هذه الفترة</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== RIGHT COLUMN (Sidebar) ===== -->
                <div class="col-lg-5">

                    <!-- Quick Info Card -->
                    <div class="sidebar-card mb-4 scroll-reveal-right">
                        <div class="sidebar-header">
                            <h5><i class="fas fa-user"></i> <span data-translate="sidebarQuickInfo">نبذة سريعة</span></h5>
                        </div>
                        <div class="sidebar-body">
                            <div class="info-row">
                                <div class="info-icon"><i class="fas fa-calendar-day"></i></div>
                                <div class="info-text">
                                    <span class="info-value">2003-3-15</span>
                                    <span class="info-label" data-translate="labelBirthDate">تاريخ الميلاد</span>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon info-icon-pink"><i class="fas fa-hospital"></i></div>
                                <div class="info-text">
                                    <span class="info-value" data-translate="valBirthPlace">مستشفى الملك فهد الرياض</span>
                                    <span class="info-label" data-translate="labelBirthPlace">مكان الولادة</span>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon info-icon-purple"><i class="fas fa-weight"></i></div>
                                <div class="info-text">
                                    <span class="info-value" data-translate="valWeight">3.2 كج</span>
                                    <span class="info-label" data-translate="labelWeight">الوزن عند الولادة</span>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon info-icon-blue"><i class="fas fa-ruler-vertical"></i></div>
                                <div class="info-text">
                                    <span class="info-value" data-translate="valHeight">174 سم</span>
                                    <span class="info-label" data-translate="labelCurrentHeight">الطول الحالي</span>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon info-icon-green"><i class="fas fa-university"></i></div>
                                <div class="info-text">
                                    <span class="info-value" data-translate="valEducation">جامعة الملك سعود</span>
                                    <span class="info-label" data-translate="labelEducation">التعليم</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Life Book Gallery Card -->
                    <div class="sidebar-card scroll-reveal-right">
                        <div class="sidebar-header">
                            <h5><i class="fas fa-book-open"></i> <span>كتاب الحياة</span></h5>
                            <a href="#" class="view-all-link" id="openLifeBook">عرض كتاب الحياة</a>
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
                    const profileImg = 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=800&fit=crop';
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

    <!-- Adult Theme Switcher & Disable Kids Systems -->
    <script>
        /* Disable kid bubble system */
        (function() {
            if (typeof bubblesActive !== 'undefined') bubblesActive = false;
            if (typeof bubbleInterval !== 'undefined') clearInterval(bubbleInterval);
            var bc = document.getElementById('bubbleContainer');
            if (bc) bc.remove();
        })();

        /* Adult theme switcher */
        function changeAdultTheme(dot) {
            var theme = dot.getAttribute('data-theme');
            document.body.className = theme;
            document.querySelectorAll('.color-dot').forEach(function(d) {
                d.classList.remove('active-dot');
            });
            dot.classList.add('active-dot');
            localStorage.setItem('adultTheme', theme);
        }
        (function() {
            var saved = localStorage.getItem('adultTheme');
            if (saved) {
                var dot = document.querySelector('[data-theme="' + saved + '"]');
                if (dot) changeAdultTheme(dot);
            }
        })();
    </script>

    <!-- Cursor Light Effect -->
    <script>
        (function() {
            var light = document.createElement('div');
            light.id = 'cursorLight';
            document.body.appendChild(light);

            var style = document.createElement('style');
            style.textContent =
                '#cursorLight{position:fixed;top:0;left:0;width:350px;height:350px;border-radius:50%;pointer-events:none;z-index:9998;opacity:0;transition:opacity 0.3s;background:radial-gradient(circle,rgba(212,175,55,0.18) 0%,rgba(212,175,55,0.09) 35%,transparent 70%);transform:translate(-50%,-50%);mix-blend-mode:overlay;}';
            document.head.appendChild(style);

            function getThemeColor() {
                var cls = document.body.className;
                if (cls === 'platinumSilver') return 'rgba(192,192,192,';
                if (cls === 'roseGold') return 'rgba(183,110,121,';
                if (cls === 'indigoNight') return 'rgba(99,102,241,';
                return 'rgba(212,175,55,';
            }

            function updateLightColor() {
                var c = getThemeColor();
                light.style.background = 'radial-gradient(circle,' + c + '0.08) 0%,' + c + '0.04) 30%,transparent 70%)';
            }

            var observer = new MutationObserver(updateLightColor);
            observer.observe(document.body, {
                attributes: true,
                attributeFilter: ['class']
            });

            document.addEventListener('mousemove', function(e) {
                light.style.left = e.clientX + 'px';
                light.style.top = e.clientY + 'px';
                light.style.opacity = '1';
            });

            document.addEventListener('mouseleave', function() {
                light.style.opacity = '0';
            });
        })();
    </script>

</body>

</html>
