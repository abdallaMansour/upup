<!doctype html>

<html lang="{{ app()->getLocale() }}" class="layout-navbar-fixed layout-menu-fixed layout-compact" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-skin="default"
    data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ __('dashboard.title') }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/svg/icons/upup_icon.svg') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->
    @yield('page-css')
    @stack('page-css')
    <style>
        .add-card-link:hover {
            border-color: var(--bs-primary) !important;
            background-color: rgba(var(--bs-primary-rgb), 0.04);
        }
    </style>

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    {{-- <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script> --}}

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    @php
        $isUserDashboard = auth('web')->check();
    @endphp
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar {{ $isUserDashboard ? 'layout-without-menu' : '' }}">
        <div class="layout-container">
            <!-- Menu (للأدمن فقط) -->
            @unless ($isUserDashboard)
                <aside id="layout-menu" class="layout-menu menu-vertical menu">
                    <div class="app-brand demo my-5">
                        <a href="{{ route('website.landing-page') }}" class="app-brand-link">
                            <span class="app-brand-logo demo">
                                <span class="text-primary">
                                    <img width="100" src="{{ asset('assets/svg/icons/upup_logo_dark.svg') }}" alt="Upup" class="img-fluid">
                                </span>
                            </span>
                        </a>

                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                            <i class="icon-base bx bx-chevron-left"></i>
                        </a>
                    </div>

                    <div class="menu-inner-shadow" style="background: #00000000;"></div>

                    <ul class="menu-inner py-1">

                        <li class="menu-item">
                            <a href="{{ route('dashboard.index') }}" class="menu-link">
                                <i class="menu-icon icon-base bx bx-home-smile"></i>
                                <div data-i18n="Dashboard">{{ __('dashboard.menu.dashboard') }}</div>
                            </a>
                        </li>


                        <!-- Apps & Pages -->
                        <li class="menu-header small">
                            <span class="menu-header-text" data-i18n="Settings">{{ __('dashboard.menu.settings') }}</span>
                        </li>
                        @auth('admin')
                            @if (auth('admin')->user()->canAccess('users.view'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.users.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-user"></i>
                                        <div data-i18n="Users">{{ __('dashboard.menu.users') }}</div>
                                    </a>
                                </li>
                            @endif
                        @endauth
                        @if (auth('web')->check() || (auth('admin')->check() && auth('admin')->user()->hasPermission('packages.view')))
                            <li class="menu-item">
                                <a href="{{ route('dashboard.packages.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-package"></i>
                                    <div data-i18n="Packages">{{ __('dashboard.menu.packages') }}</div>
                                </a>
                            </li>
                        @endif
                        @auth('admin')
                            @if (auth('admin')->user()->canAccess('faq.view') || auth('admin')->user()->canAccess('faq.manage'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.faq.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-help-circle"></i>
                                        <div data-i18n="FAQ">{{ __('dashboard.menu.faq') }}</div>
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->canAccess('features.view') || auth('admin')->user()->canAccess('features.manage'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.features.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-star"></i>
                                        <div data-i18n="Features">{{ __('dashboard.menu.features') }}</div>
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->canAccess('media-department.manage'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.media-department.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-news"></i>
                                        <div data-i18n="Media Department">{{ __('dashboard.menu.media_department') }}</div>
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->canAccess('site-settings.manage'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.privacy-policy.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-shield-quarter"></i>
                                        <div data-i18n="Privacy Policy">{{ __('dashboard.menu.privacy_policy') }}</div>
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->canAccess('site-settings.manage'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.terms-and-conditions.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-file-blank"></i>
                                        <div data-i18n="Terms and Conditions">{{ __('dashboard.menu.terms_and_conditions') }}</div>
                                    </a>
                                </li>
                            @endif

                            {{-- Subscriptions --}}
                            @if (auth('admin')->user()->canAccess('subscriptions.view') || auth('admin')->user()->canAccess('subscriptions.manage'))
                                <li class="menu-header small">
                                    <span class="menu-header-text" data-i18n="Subscriptions">{{ __('dashboard.menu.subscriptions') }}</span>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.subscriptions.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-credit-card"></i>
                                        <div data-i18n="Subscriptions">{{ __('dashboard.menu.subscriptions') }}</div>
                                    </a>
                                </li>
                            @endif
                        @endauth

                        {{-- إدارة الوثائق (للمستخدمين فقط) --}}
                        @if (auth('web')->check())
                            <li class="menu-header small">
                                <span class="menu-header-text" data-i18n="Documents">{{ __('dashboard.menu.documents') }}</span>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('dashboard.documents.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-file-blank"></i>
                                    <div data-i18n="My Documents">{{ __('dashboard.menu.my_documents') }}</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('dashboard.documents.storage-connections') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-cloud"></i>
                                    <div data-i18n="Storage Connections">{{ __('dashboard.menu.storage_connections') }}</div>
                                </a>
                            </li>
                        @endif

                        {{-- صفحات المستخدمين (للمستخدمين فقط) --}}
                        @if (auth('web')->check())
                            <li class="menu-header small">
                                <span class="menu-header-text">{{ __('dashboard.menu.user_pages') }}</span>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('dashboard.my-pages.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base bx bx-child"></i>
                                    <div>{{ __('dashboard.menu.my_pages') }}</div>
                                </a>
                            </li>
                        @endif

                        {{-- Support Tickets (users + admins) --}}
                        <li class="menu-header small">
                            <span class="menu-header-text" data-i18n="Support">{{ __('dashboard.menu.support') }}</span>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('dashboard.support-tickets.index') }}" class="menu-link">
                                <i class="menu-icon icon-base bx bx-support"></i>
                                <div data-i18n="Support Tickets">{{ __('dashboard.menu.support_tickets') }}</div>
                            </a>
                        </li>
                        @auth('admin')
                            @if (auth('admin')->user()->canAccess('technical-support.view') || auth('admin')->user()->canAccess('technical-support.manage'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.technical-support.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-envelope"></i>
                                        <div data-i18n="Messages">{{ __('dashboard.menu.messages') }}</div>
                                    </a>
                                </li>
                            @endif
                        @endauth


                        @if (auth('admin')->check() && auth('admin')->user()->canAccessAdminManagement())
                            <li class="menu-header small">
                                <span class="menu-header-text">{{ __('dashboard.menu.admin_management') }}</span>
                            </li>
                            @if (auth('admin')->user()->hasRole('super_admin') || auth('admin')->user()->roles->isEmpty() || auth('admin')->user()->hasPermission('admins.view'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.admins.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-user-circle"></i>
                                        <div data-i18n="Admins">{{ __('dashboard.menu.admins') }}</div>
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->hasRole('super_admin') || auth('admin')->user()->roles->isEmpty() || auth('admin')->user()->hasPermission('roles.view'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.roles.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-id-card"></i>
                                        <div data-i18n="Roles">{{ __('dashboard.menu.roles') }}</div>
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->hasRole('super_admin') || auth('admin')->user()->roles->isEmpty() || auth('admin')->user()->hasPermission('permissions.view'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.permissions.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-lock-alt"></i>
                                        <div data-i18n="Permissions">{{ __('dashboard.menu.permissions') }}</div>
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->canAccess('storage-platforms.manage'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.storage-platforms.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-cloud"></i>
                                        <div data-i18n="Storage Platforms">{{ __('dashboard.menu.storage_platforms') }}</div>
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->canAccess('age-stages.manage'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.age-stages.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-user"></i>
                                        <div>{{ __('dashboard.menu.age_stages') }}</div>
                                    </a>
                                </li>
                            @endif
                            @if (auth('admin')->user()->canAccess('education-stages.manage'))
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.education-stages.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-book-reader"></i>
                                        <div>{{ __('dashboard.menu.education_stages') }}</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('dashboard.education-grades.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base bx bx-book"></i>
                                        <div>{{ __('dashboard.menu.grades') }}</div>
                                    </a>
                                </li>
                            @endif
                        @endif

                    </ul>
                </aside>

                <div class="menu-mobile-toggler d-xl-none rounded-1">
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                        <i class="bx bx-menu icon-base"></i>
                        <i class="bx bx-chevron-right icon-base"></i>
                    </a>
                </div>
            @endunless
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                    @unless ($isUserDashboard)
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                                <i class="icon-base bx bx-menu icon-md"></i>
                            </a>
                        </div>
                    @endunless

                    @if ($isUserDashboard)
                        <a href="{{ route('dashboard.index') }}" class="navbar-brand me-4">
                            <img width="50" src="{{ asset('assets/svg/icons/upup_logo_dark.svg') }}" alt="Upup" class="img-fluid">
                        </a>
                    @endif

                    {{-- @if ($isUserDashboard && !request()->routeIs('dashboard.index'))
                        <div class="navbar-nav align-items-center me-auto">
                            <a href="{{ route('dashboard.index') }}" class="btn btn-label-secondary btn-sm">
                                <i class="icon-base bx bx-arrow-back me-1"></i> الرجوع للشاشة الرئيسية
                            </a>
                        </div>
                    @endif --}}

                    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item navbar-search-wrapper mb-0">
                                <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
                                    <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
                                </a>
                            </div>
                        </div>

                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                            <!-- Style Switcher -->
                            <li class="nav-item dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-sun icon-md theme-icon-active"></i>
                                    <span class="d-none ms-2" id="nav-theme-text">{{ __('dashboard.theme.toggle') }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                                            <span><i class="icon-base bx bx-sun icon-md me-3" data-icon="sun"></i>{{ __('dashboard.theme.light') }}</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
                                            <span><i class="icon-base bx bx-moon icon-md me-3" data-icon="moon"></i>{{ __('dashboard.theme.dark') }}</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system" aria-pressed="false">
                                            <span><i class="icon-base bx bx-desktop icon-md me-3" data-icon="desktop"></i>{{ __('dashboard.theme.system') }}</span>
                                        </button>
                                    </li>
                                </ul>
                            </li>
                            <!-- / Style Switcher-->

                            <!-- Language Switcher -->
                            <li class="nav-item dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" id="nav-locale" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-globe icon-md"></i>
                                    <span class="d-none ms-2">{{ __('dashboard.language.' . app()->getLocale()) }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-locale">
                                    <li>
                                        <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
                                            href="{{ route('locale.switch', 'ar') }}?intended={{ urlencode(request()->path() ? '/' . request()->path() : '/') }}">
                                            {{ __('dashboard.language.ar') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                                            href="{{ route('locale.switch', 'en') }}?intended={{ urlencode(request()->path() ? '/' . request()->path() : '/') }}">
                                            {{ __('dashboard.language.en') }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- / Language Switcher -->

                            <!-- Quick links  -->
                            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="icon-base bx bx-grid-alt icon-md"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end p-0">
                                    <div class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h6 class="mb-0 me-auto">{{ __('dashboard.shortcuts.title') }}</h6>
                                        </div>
                                    </div>
                                    <div class="dropdown-shortcuts-list scrollable-container">
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-child icon-26px text-heading"></i>
                                                </span>
                                                <a href="{{ route('profile.example.child') }}" class="stretched-link" target="_blank">{{ __('dashboard.shortcuts.childhood') }}</a>
                                                <small>{{ __('dashboard.shortcuts.childhood_desc') }}</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-run icon-26px text-heading"></i>
                                                </span>
                                                <a href="{{ route('profile.example.teenager') }}" class="stretched-link" target="_blank">{{ __('dashboard.shortcuts.teenager') }}</a>
                                                <small>{{ __('dashboard.shortcuts.teenager_desc') }}</small>
                                            </div>
                                        </div>
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                                    <i class="icon-base bx bx-user icon-26px text-heading"></i>
                                                </span>
                                                <a href="{{ route('profile.example.adults') }}" class="stretched-link" target="_blank">{{ __('dashboard.shortcuts.adults') }}</a>
                                                <small>{{ __('dashboard.shortcuts.adults_desc') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Quick links -->

                            <!-- Notification -->
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <span class="position-relative">
                                        <i class="icon-base bx bx-bell icon-md"></i>
                                        <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end p-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h6 class="mb-0 me-auto">Notification</h6>
                                            <div class="d-flex align-items-center h6 mb-0">
                                                <span class="badge bg-label-primary me-2">8 New</span>
                                                <a href="javascript:void(0)" class="dropdown-notifications-all p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"><i
                                                        class="icon-base bx bx-envelope-open text-heading"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Congratulation Lettie 🎉</h6>
                                                        <small class="mb-1 d-block text-body">Won the monthly best seller gold badge</small>
                                                        <small class="text-body-secondary">1h ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Charles Franklin</h6>
                                                        <small class="mb-1 d-block text-body">Accepted your connection</small>
                                                        <small class="text-body-secondary">12hr ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('assets/img/avatars/2.png') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">New Message ✉️</h6>
                                                        <small class="mb-1 d-block text-body">You have new message from Natalie</small>
                                                        <small class="text-body-secondary">1h ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-success"><i class="icon-base bx bx-cart"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Whoo! You have new order 🛒</h6>
                                                        <small class="mb-1 d-block text-body">ACME Inc. made new order $1,154</small>
                                                        <small class="text-body-secondary">1 day ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('assets/img/avatars/9.png') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Application has been approved 🚀</h6>
                                                        <small class="mb-1 d-block text-body">Your ABC project application has been approved.</small>
                                                        <small class="text-body-secondary">2 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-success"><i class="icon-base bx bx-pie-chart-alt"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Monthly report is generated</h6>
                                                        <small class="mb-1 d-block text-body">July monthly financial report is generated </small>
                                                        <small class="text-body-secondary">3 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('assets/img/avatars/5.png') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">Send connection request</h6>
                                                        <small class="mb-1 d-block text-body">Peter sent you connection request</small>
                                                        <small class="text-body-secondary">4 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <img src="{{ asset('assets/img/avatars/6.png') }}" alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">New message from Jane</h6>
                                                        <small class="mb-1 d-block text-body">Your have new message from Jane</small>
                                                        <small class="text-body-secondary">5 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-warning"><i class="icon-base bx bx-error"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="small mb-0">CPU is running high</h6>
                                                        <small class="mb-1 d-block text-body">CPU Utilization Percent is currently at 88.63%,</small>
                                                        <small class="text-body-secondary">5 days ago</small>
                                                    </div>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="border-top">
                                        <div class="d-grid p-4">
                                            <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                                                <small class="align-middle">View all notifications</small>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!--/ Notification -->
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                {{-- <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ auth('admin')->user()?->name ?? (auth('web')->user()?->name ?? __('dashboard.user.default_name')) }}</h6>
                                                    <small class="text-body-secondary">{{ auth('admin')->check() ? __('dashboard.user.admin_role') : __('dashboard.user.default_name') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-profile-user.html">
                                            <i class="icon-base bx bx-user icon-md me-3"></i><span>{{ __('dashboard.user.my_profile') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <i class="icon-base bx bx-cog icon-md me-3"></i><span>{{ __('dashboard.menu.settings') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-billing.html">
                                            <span class="d-flex align-items-center align-middle">
                                                <i class="flex-shrink-0 icon-base bx bx-credit-card icon-md me-3"></i><span class="flex-grow-1 align-middle">{{ __('dashboard.user.billing_plan') }}</span>
                                                <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-pricing.html">
                                            <i class="icon-base bx bx-dollar icon-md me-3"></i><span>{{ __('dashboard.user.pricing') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="pages-faq.html">
                                            <i class="icon-base bx bx-help-circle icon-md me-3"></i><span>الأسئلة الشائعة</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        @auth('admin')
                                            <form method="POST" action="{{ route('dashboard.logout') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item w-100 text-start border-0 bg-transparent">
                                                    <i class="icon-base bx bx-power-off icon-md me-3"></i><span>{{ __('dashboard.user.logout') }}</span>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item w-100 text-start border-0 bg-transparent">
                                                    <i class="icon-base bx bx-power-off icon-md me-3"></i><span>{{ __('dashboard.user.logout') }}</span>
                                                </button>
                                            </form>
                                        @endauth
                                    </li>
                                </ul> --}}

                                @auth('admin')
                                    <form method="POST" action="{{ route('dashboard.logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item w-100 text-start border-0 bg-transparent">
                                            <i class="icon-base bx bx-log-out icon-md"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item w-100 text-start border-0 bg-transparent">
                                            <i class="icon-base bx bx-log-out icon-md"></i>
                                        </button>
                                    </form>
                                @endauth
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    @yield('content')

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with ❤️ by <a href="https://evorq.com" target="_blank" class="footer-link">EVORQ Technologies</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        @unless ($isUserDashboard)
            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        @endunless

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>

    {{-- <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script> --}}

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->

    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    @yield('page-js')
</body>

</html>
