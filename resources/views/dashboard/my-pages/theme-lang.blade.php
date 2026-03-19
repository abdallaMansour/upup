@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y theme-lang-wrapper">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('my_pages.title'), 'url' => route('dashboard.my-pages.index')],
                ['label' => __('my_pages.theme_language') . ' - ' . $stage->name],
            ],
        ])

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Header مثل color-theme-selector --}}
        <div class="theme-lang-header">
            <span class="theme-lang-logo">{{ __('my_pages.theme_language') }}</span>
            <span class="theme-lang-hint">{{ __('my_pages.theme_lang_hint') }}</span>
        </div>

        <form method="POST" action="{{ route('dashboard.my-pages.theme-lang.update', $stage) }}" id="themeLangForm">
            @csrf
            @method('PUT')

            {{-- Section: لون الصفحة --}}
            <h2 class="section-title">{{ __('my_pages.theme_label') }}</h2>
            <div class="theme-grid" id="themeGrid">
                {{-- خيار "لا محدد" --}}
                <label class="theme-card {{ empty($stage->theme) ? 'active' : '' }}" data-theme="">
                    <input type="radio" name="theme" value="" {{ empty($stage->theme) ? 'checked' : '' }} class="d-none">
                    <div class="mini-profile">
                        <div class="mini-avatar" style="background:#e4e6ef;">
                            <div class="mini-dot" style="background:#9a9ab0;"></div>
                        </div>
                        <div class="mini-lines">
                            <div class="mini-line long"></div>
                            <div class="mini-line short"></div>
                        </div>
                        <div class="mini-badges">
                            <span class="mini-badge follow" style="background:#e8ecff;color:#9a9ab0;">—</span>
                            <span class="mini-badge products" style="background:#e4e6ef;color:#6c757d;">{{ __('my_pages.theme_not_set') }}</span>
                        </div>
                    </div>
                    <div class="theme-label">{{ __('my_pages.theme_not_set') }}</div>
                </label>
                @foreach ($themes as $t)
                    <label class="theme-card {{ ($stage->theme ?? '') === $t['id'] ? 'active' : '' }}" data-theme="{{ $t['id'] }}">
                        <input type="radio" name="theme" value="{{ $t['id'] }}" {{ ($stage->theme ?? '') === $t['id'] ? 'checked' : '' }} class="d-none">
                        <div class="mini-profile">
                            <div class="mini-avatar" style="background:#e4e6ef;">
                                <div class="mini-dot" style="background:{{ $t['dot'] ?? $t['badgeProducts'] }};"></div>
                            </div>
                            <div class="mini-lines">
                                <div class="mini-line long"></div>
                                <div class="mini-line short"></div>
                            </div>
                            <div class="mini-badges">
                                <span class="mini-badge follow"
                                    style="background:{{ $t['badgeFollow'] ?? '#e8ecff' }};color:{{ $t['badgeFollowText'] ?? $t['badgeProducts'] }};">{{ $stage->age_in_years !== null ? __('my_pages.age_badge', ['age' => $stage->age_in_years]) : __('my_pages.age_badge_empty') }}</span>
                                <span class="mini-badge products" style="background:{{ $t['badgeProducts'] }};">{{ __('my_pages.life_stage_' . ($stage->life_stage ?? 'child')) }}</span>
                            </div>
                        </div>
                        <div class="theme-label">{{ __('my_pages.theme.' . $t['id']) }}</div>
                    </label>
                @endforeach
            </div>

            <hr class="divider" />

            {{-- Section: اللغة الافتراضية --}}
            <h2 class="section-title">{{ __('my_pages.default_language_label') }}</h2>
            <div class="lang-options">
                <label class="lang-option">
                    <input type="radio" name="default_language" value="ar" {{ ($stage->default_language ?? 'ar') === 'ar' ? 'checked' : '' }}>
                    <span class="lang-option-label">{{ __('my_pages.lang_ar') }}</span>
                </label>
                <label class="lang-option">
                    <input type="radio" name="default_language" value="en" {{ ($stage->default_language ?? '') === 'en' ? 'checked' : '' }}>
                    <span class="lang-option-label">{{ __('my_pages.lang_en') }}</span>
                </label>
            </div>

            <hr class="divider" />

            {{-- معاينة الرابط الشخصي --}}
            <div class="personal-link-box">
                <span class="personal-link-label">
                    <i class="bx bx-link-alt"></i>
                    {{ __('my_pages.preview_link') }}
                </span>
                <span class="personal-link-url">{{ route('profile.show', $stage) }}</span>
                <a href="{{ route('profile.show', $stage) }}" class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                    <i class="bx bx-show me-1"></i> {{ __('common.view') }}
                </a>
            </div>

            {{-- أزرار --}}
            <div class="theme-lang-actions">
                <a href="{{ route('dashboard.my-pages.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('common.cancel') }}
                </a>
                <button type="submit" class="btn-save">
                    <i class="bx bx-check me-1"></i> {{ __('common.submit') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@section('page-css')
    <style>
        /* متغيرات من color-theme-selector */
        .theme-lang-wrapper {
            --primary: #4361ee;
            --primary-light: #e8ecff;
            --bg: #f5f6fa;
            --card-bg: #ffffff;
            --text: #1a1a2e;
            --text-muted: #9a9ab0;
            --border: #e4e6ef;
            --selected-border: #4361ee;
        }

        /* Header */
        .theme-lang-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 16px 0;
            margin-bottom: 24px;
        }

        .theme-lang-logo {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .theme-lang-hint {
            font-size: 13px;
            color: var(--text-muted);
            background: var(--primary-light);
            padding: 6px 14px;
            border-radius: 20px;
        }

        /* Section title */
        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 28px;
        }

        /* Theme Grid */
        .theme-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .theme-card {
            background: var(--card-bg);
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 20px 16px 14px;
            cursor: pointer;
            transition: transform .2s, border-color .2s, box-shadow .2s;
            position: relative;
            overflow: hidden;
        }

        .theme-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(0, 0, 0, .1);
        }

        .theme-card.active {
            border-color: var(--selected-border);
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--selected-border) 18%, transparent);
        }

        .mini-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }

        .mini-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--border);
            position: relative;
        }

        .mini-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            left: 0;
            border: 2px solid var(--card-bg);
        }

        .mini-lines {
            display: flex;
            flex-direction: column;
            gap: 5px;
            width: 100%;
        }

        .mini-line {
            height: 8px;
            border-radius: 4px;
            background: var(--border);
        }

        .mini-line.short {
            width: 55%;
            margin: 0 auto;
        }

        .mini-line.long {
            width: 80%;
            margin: 0 auto;
        }

        .mini-badges {
            display: flex;
            gap: 6px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .mini-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .mini-badge.products {
            color: #fff !important;
        }

        .theme-label {
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            margin-top: 10px;
        }

        .theme-card.active::after {
            content: '✓';
            position: absolute;
            top: 10px;
            left: 10px;
            width: 22px;
            height: 22px;
            background: var(--selected-border);
            color: #fff;
            border-radius: 50%;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 22px;
            text-align: center;
        }

        [dir="rtl"] .theme-card.active::after {
            left: auto;
            right: 10px;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 0 0 40px;
        }

        /* Language options */
        .lang-options {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .lang-option {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
        }

        .lang-option input {
            cursor: pointer;
        }

        /* Personal link box */
        .personal-link-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            background: var(--card-bg);
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
            margin-bottom: 36px;
        }

        .personal-link-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }

        .personal-link-label i {
            color: var(--primary);
        }

        .personal-link-url {
            font-size: 13px;
            color: var(--text-muted);
            background: var(--bg);
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid var(--border);
            direction: ltr;
            flex: 1;
            min-width: 200px;
            max-width: 100%;
        }

        /* Actions */
        .theme-lang-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
        }

        /* Save button - مثل color-theme-selector */
        .btn-save {
            display: inline-flex;
            align-items: center;
            background: var(--primary);
            color: #fff !important;
            border: none;
            border-radius: 12px;
            padding: 14px 48px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .2s, transform .15s;
            box-shadow: 0 4px 18px color-mix(in srgb, var(--primary) 40%, transparent);
        }

        .btn-save:hover {
            opacity: .88;
            transform: translateY(-1px);
            color: #fff !important;
        }

        .btn-save:active {
            transform: translateY(0);
        }
    </style>
@endsection

@section('page-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.theme-card').forEach(function(card) {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.theme-card').forEach(function(c) {
                        c.classList.remove('active');
                    });
                    this.classList.add('active');
                    this.querySelector('input[type="radio"]').checked = true;
                });
            });
        });
    </script>
@endsection
