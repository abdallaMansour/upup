@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => ($stage ?? null)
            ? [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('dashboard.menu.my_pages'), 'url' => route('dashboard.my-pages.index')],
                ['label' => __('my_pages.documents') . ' - ' . $stage->name, 'url' => route('dashboard.my-pages.documents', $stage)],
                ['label' => __('documents.other_events.title'), 'url' => route('dashboard.other-events.index', ['stage' => $stage->id])],
                ['label' => __('dashboard.breadcrumb.add')],
            ]
            : [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('documents.other_events.title'), 'url' => route('dashboard.other-events.index')],
                ['label' => __('dashboard.breadcrumb.add')],
            ]
    ])
    <h4 class="mb-4">{{ __('documents.other_events.add_title') }}</h4>

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
            <form action="{{ route('dashboard.other-events.store', ($stage ?? null) ? ['stage' => $stage->id] : []) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="record_date" class="form-label">{{ __('common.date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('record_date') is-invalid @enderror" id="record_date" name="record_date" value="{{ old('record_date', date('Y-m-d')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="record_time" class="form-label">{{ __('common.time') }}</label>
                        <input type="time" class="form-control @error('record_time') is-invalid @enderror" id="record_time" name="record_time" value="{{ old('record_time') }}">
                        @error('record_time')
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
                        <label for="other_info" class="form-label">{{ __('common.other_info') }}</label>
                        <textarea class="form-control @error('other_info') is-invalid @enderror" id="other_info" name="other_info" rows="3">{{ old('other_info') }}</textarea>
                        @error('other_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="media" class="form-label">{{ __('documents.drawings.image_video') }}</label>
                        <input type="file" class="form-control @error('media') is-invalid @enderror" id="media" name="media" accept="image/*,video/*">
                        <small class="text-muted">{{ __('documents.drawings.supported_formats') }}</small>
                        @error('media')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-check">
                            <input type="checkbox" name="show_in_education" value="1" class="form-check-input" {{ old('show_in_education') ? 'checked' : '' }}>
                            <span class="form-check-label">{{ __('life_stages.show_in_education') }}</span>
                        </label>
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
