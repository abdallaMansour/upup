@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
            ['label' => __('dashboard.breadcrumb.my_pages'), 'url' => route('dashboard.my-pages.index')],
            ['label' => __('my_pages.documents') . ' - ' . $stage->name],
        ]
    ])
    <h4 class="mb-4">{{ __('my_pages.documents') }} - {{ $stage->name }}</h4>

    <p class="text-body-secondary mb-4">{{ __('documents.intro') }}</p>

    <div class="row g-4">
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.height-weight.index', ['stage' => $stage->id]) }}" class="card h-100 border shadow-sm text-decoration-none text-body">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-warning" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-ruler icon-lg text-warning"></i>
                    </span>
                    <h6 class="card-title mb-1">{{ __('documents.height_weight') }}</h6>
                    <small class="text-body-secondary">{{ __('documents.track_growth') }}</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.achievements.index', ['stage' => $stage->id]) }}" class="card h-100 border shadow-sm text-decoration-none text-body">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-primary" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-trophy icon-lg text-primary"></i>
                    </span>
                    <h6 class="card-title mb-1">{{ __('documents.achievements') }}</h6>
                    <small class="text-body-secondary">{{ __('documents.my_achievements') }}</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.voices.index', ['stage' => $stage->id]) }}" class="card h-100 border shadow-sm text-decoration-none text-body">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-secondary" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-music icon-lg text-secondary"></i>
                    </span>
                    <h6 class="card-title mb-1">{{ __('documents.voices.title') }}</h6>
                    <small class="text-body-secondary">{{ __('documents.voice_recordings') }}</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.drawings.index', ['stage' => $stage->id]) }}" class="card h-100 border shadow-sm text-decoration-none text-body">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-danger" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-palette icon-lg text-danger"></i>
                    </span>
                    <h6 class="card-title mb-1">{{ __('documents.drawings.title') }}</h6>
                    <small class="text-body-secondary">{{ __('documents.my_drawings') }}</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.visits.index', ['stage' => $stage->id]) }}" class="card h-100 border shadow-sm text-decoration-none text-body">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-info" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-map-alt icon-lg text-info"></i>
                    </span>
                    <h6 class="card-title mb-1">{{ __('documents.visits') }}</h6>
                    <small class="text-body-secondary">{{ __('documents.visit_log') }}</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.injuries.index', ['stage' => $stage->id]) }}" class="card h-100 border shadow-sm text-decoration-none text-body">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-danger" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-first-aid icon-lg text-danger"></i>
                    </span>
                    <h6 class="card-title mb-1">{{ __('documents.injuries') }}</h6>
                    <small class="text-body-secondary">{{ __('documents.injury_log') }}</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.other-events.index', ['stage' => $stage->id]) }}" class="card h-100 border shadow-sm text-decoration-none text-body">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-success" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-calendar-event icon-lg text-success"></i>
                    </span>
                    <h6 class="card-title mb-1">{{ __('documents.other_events.title') }}</h6>
                    <small class="text-body-secondary">{{ __('documents.occasions_memories') }}</small>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
