@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
            ['label' => __('dashboard.breadcrumb.my_pages')],
        ]
    ])
    <h4 class="mb-4">{{ __('my_pages.title') }}</h4>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
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

    <div class="row g-4">
        @foreach ($stages as $stage)
                @php
                    $coverUrl = $stage->coverImageDocument?->embed_url ?? $stage->coverImageDocument?->view_url ?? $stage->firstPhotoDocument?->embed_url ?? $stage->firstPhotoDocument?->view_url;
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        @if ($coverUrl)
                            <img src="{{ $coverUrl }}" class="card-img-top" alt="{{ $stage->name }}" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-label-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="bx bx-child bx-lg text-secondary"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $stage->name }}</h5>
                            <p class="card-text text-body-secondary small mb-3">
                                <i class="bx bx-calendar me-1"></i> {{ $stage->created_at->format('Y-m-d') }}
                            </p>
                            <a href="{{ route('dashboard.my-pages.documents', $stage) }}" class="btn btn-sm btn-outline-info w-100 mb-3">
                                <i class="bx bx-file-blank"></i> {{ __('my_pages.documents') }}
                            </a>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('profile.show', $stage) }}" class="btn btn-sm btn-outline-primary" title="{{ __('common.view') }}" target="_blank">
                                    <i class="bx bx-show"></i> {{ __('common.view') }}
                                </a>
                                <a href="{{ route('dashboard.my-pages.edit', $stage) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bx bx-cog"></i> {{ __('my_pages.settings') }}
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-success" title="{{ __('my_pages.theme_language') }}" data-bs-toggle="modal" data-bs-target="#themeLangModal{{ $stage->id }}">
                                    <i class="bx bx-palette"></i> {{ __('my_pages.theme_language') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" title="{{ __('my_pages.temporary_permission') }}" data-bs-toggle="modal" data-bs-target="#permissionModal{{ $stage->id }}">
                                    <i class="bx bx-lock-alt"></i> {{ __('my_pages.temporary_permission') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-stage" data-stage-id="{{ $stage->id }}" data-stage-name="{{ $stage->name }}" data-delete-url="{{ route('dashboard.my-pages.destroy', $stage) }}">
                                    <i class="bx bx-trash"></i> {{ __('common.delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $lifeStage = $stage->life_stage;
                    $childThemes = [
                        ['id' => 'playfulRed', 'style' => 'background:linear-gradient(135deg, #e74c3c 0%, #ff9800 50%, #ffb74d 100%);', 'title' => 'Playful Red'],
                        ['id' => 'oceanBlue', 'style' => 'background:linear-gradient(135deg, #1e88e5 0%, #42a5f5 50%, #90caf9 100%);', 'title' => 'Ocean Blue'],
                        ['id' => 'forestGreen', 'style' => 'background:linear-gradient(135deg, #43a047 0%, #66bb6a 50%, #a5d6a7 100%);', 'title' => 'Forest Green'],
                        ['id' => 'sunsetOrange', 'style' => 'background:linear-gradient(135deg, #ff6f00 0%, #ff9800 50%, #ffcc80 100%);', 'title' => 'Sunset Orange'],
                        ['id' => 'purpleDreams', 'style' => 'background:linear-gradient(135deg, #7b1fa2 0%, #ab47bc 50%, #ce93d8 100%);', 'title' => 'Purple Dreams'],
                        ['id' => 'candyPink', 'style' => 'background:linear-gradient(135deg, #ec407a 0%, #f48fb1 50%, #f8bbd0 100%);', 'title' => 'Candy Pink'],
                        ['id' => 'skyBlue', 'style' => 'background:linear-gradient(135deg, #039be5 0%, #4fc3f7 50%, #b3e5fc 100%);', 'title' => 'Sky Blue'],
                        ['id' => 'sunshineYellow', 'style' => 'background:linear-gradient(135deg, #fbc02d 0%, #fdd835 50%, #fff59d 100%);', 'title' => 'Sunshine Yellow'],
                        ['id' => 'berryPurple', 'style' => 'background:linear-gradient(135deg, #8e24aa 0%, #ba68c8 50%, #e1bee7 100%);', 'title' => 'Berry Purple'],
                        ['id' => 'mintFresh', 'style' => 'background:linear-gradient(135deg, #26a69a 0%, #4db6ac 50%, #b2dfdb 100%);', 'title' => 'Mint Fresh'],
                    ];
                    $teenThemes = [
                        ['id' => 'neon', 'style' => 'background:linear-gradient(135deg, #A855F7 0%, #06B6D4 50%, #F472B6 100%);', 'title' => 'Neon'],
                        ['id' => 'electric', 'style' => 'background:linear-gradient(135deg, #3B82F6 0%, #60A5FA 50%, #22C55E 100%);', 'title' => 'Electric'],
                        ['id' => 'creative', 'style' => 'background:linear-gradient(135deg, #F97316 0%, #A855F7 50%, #EC4899 100%);', 'title' => 'Creative'],
                        ['id' => 'cosmic', 'style' => 'background:linear-gradient(135deg, #4C1D95 0%, #7C3AED 50%, #22D3EE 100%);', 'title' => 'Cosmic'],
                    ];
                    $adultThemes = [
                        ['id' => 'royalGold', 'style' => 'background:linear-gradient(135deg, #D4AF37 0%, #111827 100%);', 'title' => 'Royal Gold'],
                        ['id' => 'platinumSilver', 'style' => 'background:linear-gradient(135deg, #C0C0C0 0%, #0F172A 100%);', 'title' => 'Platinum Silver'],
                        ['id' => 'roseGold', 'style' => 'background:linear-gradient(135deg, #B76E79 0%, #1A0A0F 100%);', 'title' => 'Rose Gold'],
                        ['id' => 'indigoNight', 'style' => 'background:linear-gradient(135deg, #6366F1 0%, #020617 100%);', 'title' => 'Indigo Night'],
                    ];
                    $themes = match ($lifeStage) {
                        'child' => $childThemes,
                        'teenager' => $teenThemes,
                        'adult' => $adultThemes,
                        default => $childThemes,
                    };
                @endphp
                {{-- Modal الثيم واللغة --}}
                <div class="modal fade" id="themeLangModal{{ $stage->id }}" tabindex="-1" aria-labelledby="themeLangModalLabel{{ $stage->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('dashboard.my-pages.theme-lang.update', $stage) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="themeLangModalLabel{{ $stage->id }}">{{ __('my_pages.theme_language') }} - {{ $stage->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-4">
                                        <label class="form-label">{{ __('my_pages.theme_label') }}</label>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <div class="form-check form-check-inline me-2">
                                                <input class="form-check-input theme-none-radio" type="radio" name="theme" id="themeNone{{ $stage->id }}" value="" {{ empty($stage->theme) ? 'checked' : '' }}>
                                                <label class="form-check-label small text-muted" for="themeNone{{ $stage->id }}">{{ __('my_pages.theme_not_set') }}</label>
                                            </div>
                                            @foreach ($themes as $t)
                                                <label class="mb-0 cursor-pointer theme-dot-label" title="{{ $t['title'] }}">
                                                    <input type="radio" name="theme" value="{{ $t['id'] }}" {{ ($stage->theme ?? '') === $t['id'] ? 'checked' : '' }} class="d-none theme-radio">
                                                    <span class="d-inline-block rounded-circle border border-2 theme-dot {{ ($stage->theme ?? '') === $t['id'] ? 'theme-dot-selected' : 'border-secondary' }}" style="width:32px;height:32px;{{ $t['style'] }};cursor:pointer" data-theme="{{ $t['id'] }}"></span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label">{{ __('my_pages.default_language_label') }}</label>
                                        <div class="d-flex gap-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="default_language" id="langAr{{ $stage->id }}" value="ar" {{ ($stage->default_language ?? 'ar') === 'ar' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="langAr{{ $stage->id }}">{{ __('my_pages.lang_ar') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="default_language" id="langEn{{ $stage->id }}" value="en" {{ ($stage->default_language ?? '') === 'en' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="langEn{{ $stage->id }}">{{ __('my_pages.lang_en') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ __('common.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal صلاحيه مؤقته --}}
                <div class="modal fade" id="permissionModal{{ $stage->id }}" tabindex="-1" aria-labelledby="permissionModalLabel{{ $stage->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('dashboard.my-pages.permissions.store', $stage) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="permissionModalLabel{{ $stage->id }}">{{ __('my_pages.modal.title_prefix') }} {{ $stage->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
                                </div>
                                <div class="modal-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $err)
                                                    <li>{{ $err }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        <label for="grantee_name{{ $stage->id }}" class="form-label">{{ __('my_pages.grantee_name') }}</label>
                                        <input type="text" class="form-control" id="grantee_name{{ $stage->id }}" name="grantee_name" value="{{ old('grantee_name') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="grantee_email{{ $stage->id }}" class="form-label">{{ __('common.email') }}</label>
                                        <input type="email" class="form-control" id="grantee_email{{ $stage->id }}" name="grantee_email" value="{{ old('grantee_email') }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="expires_at{{ $stage->id }}" class="form-label">{{ __('my_pages.expires_at') }}</label>
                                            <input type="date" class="form-control" id="expires_at{{ $stage->id }}" name="expires_at" value="{{ old('expires_at') }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="expires_time{{ $stage->id }}" class="form-label">{{ __('my_pages.expires_time') }}</label>
                                            <input type="time" class="form-control" id="expires_time{{ $stage->id }}" name="expires_time" value="{{ old('expires_time', '23:59') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ __('common.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        @endforeach
        @include('dashboard.partials.add-card', [
            'url' => route('dashboard.my-pages.create'),
            'label' => __('my_pages.add_stage'),
            'icon' => 'bx-plus',
        ])
    </div>
</div>

@if ($stages->isNotEmpty())
<form id="delete-stage-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="name_confirmation" id="delete-name-confirmation">
</form>
@endif
@endsection

@section('page-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
.theme-dot-selected {
    border-width: 3px !important;
    border-color: var(--bs-primary) !important;
    box-shadow: 0 0 0 2px rgba(var(--bs-primary-rgb), 0.3);
    transform: scale(1.15);
    transition: all 0.2s ease;
}
.theme-dot-label .theme-dot {
    transition: all 0.2s ease;
}
.theme-dot-label:hover .theme-dot {
    transform: scale(1.08);
}
.theme-dot-label:hover .theme-dot-selected {
    transform: scale(1.2);
}
</style>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Theme dot selection visual feedback
    function clearThemeDotsSelection(modal) {
        var container = modal ? modal.closest('.modal') : document;
        (container || document).querySelectorAll('.theme-dot-label .theme-dot').forEach(function(d) {
            d.classList.remove('theme-dot-selected');
            d.classList.add('border-secondary');
        });
    }
    document.querySelectorAll('.theme-none-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var modal = this.closest('.modal');
            if (modal) {
                modal.querySelectorAll('.theme-dot-label .theme-dot').forEach(function(d) {
                    d.classList.remove('theme-dot-selected');
                    d.classList.add('border-secondary');
                });
            }
        });
    });
    document.querySelectorAll('.theme-dot-label').forEach(function(label) {
        var radio = label.querySelector('.theme-radio');
        var dot = label.querySelector('.theme-dot');
        if (!radio || !dot) return;
        radio.addEventListener('change', function() {
            var modal = label.closest('.modal');
            var allDots = modal ? modal.querySelectorAll('.theme-dot-label .theme-dot') : document.querySelectorAll('.theme-dot-label .theme-dot');
            allDots.forEach(function(d) {
                d.classList.remove('theme-dot-selected');
                d.classList.add('border-secondary');
            });
            dot.classList.remove('border-secondary');
            dot.classList.add('theme-dot-selected');
        });
    });

    document.querySelectorAll('.btn-delete-stage').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const stageName = this.dataset.stageName;
            const deleteUrl = this.dataset.deleteUrl;

            Swal.fire({
                title: '{{ __('my_pages.confirm_delete_title') }}',
                html: '{{ __('my_pages.confirm_delete_message') }}<br><strong>' + stageName + '</strong>',
                input: 'text',
                inputLabel: '{{ __('my_pages.confirm_delete_input_label') }}',
                inputPlaceholder: stageName,
                showCancelButton: true,
                confirmButtonText: '{{ __('common.delete') }}',
                cancelButtonText: '{{ __('common.cancel') }}',
                confirmButtonColor: '#d33',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false,
                preConfirm: (value) => {
                    if (value !== stageName) {
                        Swal.showValidationMessage('{{ __('my_pages.name_mismatch') }}');
                        return false;
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-stage-form');
                    form.action = deleteUrl;
                    form.querySelector('#delete-name-confirmation').value = result.value;
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
