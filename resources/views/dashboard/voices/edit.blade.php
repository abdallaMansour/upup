@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @php $editStage = $voice->childhoodStage ?? null; @endphp
    @include('dashboard.partials.breadcrumb', [
        'items' => $editStage
            ? [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('dashboard.menu.my_pages'), 'url' => route('dashboard.my-pages.index')],
                ['label' => __('my_pages.documents') . ' - ' . $editStage->name, 'url' => route('dashboard.my-pages.documents', $editStage)],
                ['label' => __('documents.voices.title'), 'url' => route('dashboard.voices.index', ['stage' => $editStage->id])],
                ['label' => __('common.edit')],
            ]
            : [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('documents.voices.title'), 'url' => route('dashboard.voices.index')],
                ['label' => __('common.edit')],
            ]
    ])
    <h4 class="mb-4">{{ __('documents.voices.edit_title') }}</h4>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!($primaryConnection ?? null))
        <div class="alert alert-warning">
            <i class="bx bx-info-circle me-2"></i>
            {!! __('documents.storage_required_audio', ['link' => route('dashboard.documents.storage-connections')]) !!}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.voices.update', $voice) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="record_date" class="form-label">{{ __('common.date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('record_date') is-invalid @enderror" id="record_date" name="record_date" value="{{ old('record_date', $voice->record_date->format('Y-m-d')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="record_time" class="form-label">{{ __('common.time') }}</label>
                        <input type="time" class="form-control @error('record_time') is-invalid @enderror" id="record_time" name="record_time" value="{{ old('record_time', $voice->record_time_formatted) }}">
                        @error('record_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $voice->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="other_info" class="form-label">{{ __('common.other_info') }}</label>
                        <textarea class="form-control @error('other_info') is-invalid @enderror" id="other_info" name="other_info" rows="3">{{ old('other_info', $voice->other_info) }}</textarea>
                        @error('other_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="audio" class="form-label">{{ __('documents.voices.audio_label') }}</label>
                        @if ($voice->audioDocument)
                            <div class="mb-2">
                                <a href="{{ $voice->audioDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-play me-1"></i> {{ __('documents.voices.listen_current') }}
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('audio') is-invalid @enderror" id="audio" name="audio" accept="audio/*">
                        <small class="text-muted">{{ __('documents.voices.supported_formats_leave_empty') }}</small>
                        @error('audio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-check">
                            <input type="checkbox" name="show_in_education" value="1" class="form-check-input" {{ old('show_in_education', $voice->show_in_education) ? 'checked' : '' }}>
                            <span class="form-check-label">{{ __('life_stages.show_in_education') }}</span>
                        </label>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> {{ __('common.update') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
