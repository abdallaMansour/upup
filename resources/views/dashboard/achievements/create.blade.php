@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => ($stage ?? null)
            ? [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('dashboard.menu.my_pages'), 'url' => route('dashboard.my-pages.index')],
                ['label' => __('my_pages.documents') . ' - ' . $stage->name, 'url' => route('dashboard.my-pages.documents', $stage)],
                ['label' => __('documents.achievements'), 'url' => route('dashboard.achievements.index', ['stage' => $stage->id])],
                ['label' => __('dashboard.breadcrumb.add')],
            ]
            : [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('documents.achievements'), 'url' => route('dashboard.achievements.index')],
                ['label' => __('dashboard.breadcrumb.add')],
            ]
    ])
    <h4 class="mb-4">{{ __('documents.achievement_add_title') }}</h4>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!($primaryConnection ?? null))
        <div class="alert alert-warning">
            <i class="bx bx-info-circle me-2"></i>
            {!! __('documents.storage_required_media', ['link' => route('dashboard.documents.storage-connections')]) !!}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.achievements.store', ($stage ?? null) ? ['stage' => $stage->id] : []) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="record_date" class="form-label">{{ __('common.date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('record_date') is-invalid @enderror" id="record_date" name="record_date" value="{{ old('record_date', date('Y-m-d')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="record_time" class="form-label">{{ __('common.time') }}</label>
                        <input type="time" class="form-control @error('record_time') is-invalid @enderror" id="record_time" name="record_time" value="{{ old('record_time') }}">
                        @error('record_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="type" class="form-label">{{ __('documents.achievement_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            @foreach (\App\Models\UserAchievement::TYPES as $value => $label)
                                <option value="{{ $value }}" @selected(old('type') === $value)>{{ __('documents.achievement_types.' . $value) }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="title" class="form-label">{{ __('common.title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="place" class="form-label">{{ __('documents.achievement_place') }}</label>
                        <input type="text" class="form-control @error('place') is-invalid @enderror" id="place" name="place" value="{{ old('place') }}">
                        @error('place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="certificate_image" class="form-label">{{ __('documents.achievement_certificate_image') }}</label>
                        <input type="file" class="form-control @error('certificate_image') is-invalid @enderror" id="certificate_image" name="certificate_image" accept="image/*">
                        <small class="text-muted">{{ __('common.max_10mb') }}</small>
                        @error('certificate_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="photos" class="form-label">{{ __('documents.achievement_photos') }}</label>
                        <input type="file" class="form-control @error('photos.*') is-invalid @enderror" id="photos" name="photos[]" accept="image/*" multiple>
                        <small class="text-muted">{{ __('documents.achievement_max_10mb_per_image') }}</small>
                        @error('photos.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="videos" class="form-label">{{ __('documents.achievement_videos') }}</label>
                        <input type="file" class="form-control @error('videos.*') is-invalid @enderror" id="videos" name="videos[]" accept="video/*" multiple>
                        <small class="text-muted">{{ __('documents.achievement_max_50mb_per_video') }}</small>
                        @error('videos.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-check">
                            <input type="checkbox" name="show_in_education" value="1" class="form-check-input" {{ old('show_in_education') ? 'checked' : '' }}>
                            <span class="form-check-label">{{ __('life_stages.show_in_education') }}</span>
                        </label>
                        <small class="text-body-secondary d-block mt-1">{{ __('life_stages.achievement_education_hint') }}</small>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> {{ __('common.save') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
