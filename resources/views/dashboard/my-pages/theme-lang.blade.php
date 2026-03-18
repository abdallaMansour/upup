@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
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

        <div class="theme-lang-page">
            <div class="theme-lang-header mb-4">
                <h4 class="mb-1">{{ __('my_pages.theme_language') }} - {{ $stage->name }}</h4>
                <p class="text-body-secondary mb-0 small">{{ __('my_pages.theme_lang_hint') }}</p>
            </div>

            <form method="POST" action="{{ route('dashboard.my-pages.theme-lang.update', $stage) }}" id="themeLangForm">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bx bx-palette me-2"></i>{{ __('my_pages.theme_label') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="theme-grid" id="themeGrid">
                            {{-- خيار "لا محدد" --}}
                            <label class="theme-card {{ empty($stage->theme) ? 'active' : '' }}" data-theme="">
                                <input type="radio" name="theme" value="" {{ empty($stage->theme) ? 'checked' : '' }} class="d-none">
                                <div class="theme-card-inner">
                                    <div class="theme-card-preview" style="background: linear-gradient(135deg, #e4e6ef 0%, #f5f5f9 100%);">
                                        <div class="theme-preview-placeholder">
                                            <i class="bx bx-minus"></i>
                                        </div>
                                    </div>
                                    <div class="theme-card-label">{{ __('my_pages.theme_not_set') }}</div>
                                </div>
                            </label>
                            @foreach ($themes as $t)
                                <label class="theme-card {{ ($stage->theme ?? '') === $t['id'] ? 'active' : '' }}" data-theme="{{ $t['id'] }}">
                                    <input type="radio" name="theme" value="{{ $t['id'] }}" {{ ($stage->theme ?? '') === $t['id'] ? 'checked' : '' }} class="d-none">
                                    <div class="theme-card-inner">
                                        <div class="theme-card-preview" style="{{ $t['style'] }}">
                                            <div class="theme-preview-content">
                                                <div class="theme-preview-avatar">
                                                    <div class="theme-preview-dot" style="{{ $t['style'] }}"></div>
                                                </div>
                                                <div class="theme-preview-lines">
                                                    <span class="theme-line long" style="width: 70px;"></span>
                                                    <span class="theme-line short"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="theme-card-label">{{ $t['title'] }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bx bx-globe me-2"></i>{{ __('my_pages.default_language_label') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <div class="form-check form-check-lg">
                                <input class="form-check-input" type="radio" name="default_language" id="langAr" value="ar" {{ ($stage->default_language ?? 'ar') === 'ar' ? 'checked' : '' }}>
                                <label class="form-check-label" for="langAr">{{ __('my_pages.lang_ar') }}</label>
                            </div>
                            <div class="form-check form-check-lg">
                                <input class="form-check-input" type="radio" name="default_language" id="langEn" value="en" {{ ($stage->default_language ?? '') === 'en' ? 'checked' : '' }}>
                                <label class="form-check-label" for="langEn">{{ __('my_pages.lang_en') }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <a href="{{ route('dashboard.my-pages.index') }}" class="btn btn-label-secondary">
                        <i class="bx bx-arrow-back me-1"></i> {{ __('common.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check me-1"></i> {{ __('common.submit') }}
                    </button>
                </div>
            </form>

            <div class="card mt-4">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-link-alt text-primary" style="font-size: 1.5rem;"></i>
                        <div>
                            <span class="fw-semibold">{{ __('my_pages.preview_link') }}</span>
                            <span class="text-body-secondary small d-block">{{ route('profile.show', $stage) }}</span>
                        </div>
                    </div>
                    <a href="{{ route('profile.show', $stage) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="bx bx-show me-1"></i> {{ __('common.view') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-css')
    <style>
        .theme-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
        }

        .theme-card {
            cursor: pointer;
            margin: 0;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        }

        .theme-card-inner {
            background: var(--bs-card-bg, #fff);
            border: 2px solid var(--bs-border-color, #e4e6ef);
            border-radius: 16px;
            padding: 0 0 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .theme-card:hover .theme-card-inner {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(0, 0, 0, .1);
        }

        .theme-card.active .theme-card-inner {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--bs-primary) 18%, transparent);
        }

        .theme-card-preview {
            height: 100px;
            border-radius: 14px 14px 0 0;
            margin: 12px 12px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .theme-preview-placeholder {
            color: rgba(255, 255, 255, 0.5);
            font-size: 2rem;
        }

        .theme-preview-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
        }

        .theme-preview-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .theme-preview-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.9);
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
        }

        .theme-preview-lines {
            display: flex;
            flex-direction: column;
            gap: 4px;
            width: 100%;
            padding: 0 12px;
        }

        .theme-line {
            height: 6px;
            border-radius: 3px;
            background: rgba(255, 255, 255, 0.4);
        }

        .theme-line.long {
            width: 80%;
            margin: 0 auto;
        }

        .theme-line.short {
            width: 50%;
            margin: 0 auto;
        }

        .theme-card-label {
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--bs-body-color);
            margin-top: 10px;
            padding: 0 8px;
        }

        .theme-card.active::after {
            content: '✓';
            position: absolute;
            top: 20px;
            right: 20px;
            width: 24px;
            height: 24px;
            background: var(--bs-primary);
            color: #fff;
            border-radius: 50%;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            z-index: 2;
        }

        .theme-card {
            position: relative;
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
