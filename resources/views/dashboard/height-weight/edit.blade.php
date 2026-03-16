@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @php $editStage = $heightWeight->childhoodStage ?? null; @endphp
    @include('dashboard.partials.breadcrumb', [
        'items' => $editStage
            ? [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('dashboard.breadcrumb.my_pages'), 'url' => route('dashboard.my-pages.index')],
                ['label' => __('my_pages.documents') . ' - ' . $editStage->name, 'url' => route('dashboard.my-pages.documents', $editStage)],
                ['label' => __('dashboard.breadcrumb.height_weight'), 'url' => route('dashboard.height-weight.index', ['stage' => $editStage->id])],
                ['label' => __('dashboard.breadcrumb.edit')],
            ]
            : [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('dashboard.breadcrumb.height_weight'), 'url' => route('dashboard.height-weight.index')],
                ['label' => __('dashboard.breadcrumb.edit')],
            ]
    ])
    <h4 class="mb-4">{{ __('life_stages.edit_record') }}</h4>

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
            <form action="{{ route('dashboard.height-weight.update', $heightWeight) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="record_date" class="form-label">{{ __('common.date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('record_date') is-invalid @enderror" id="record_date" name="record_date" value="{{ old('record_date', $heightWeight->record_date->format('Y-m-d')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="record_time" class="form-label">{{ __('common.time') }}</label>
                        <input type="time" class="form-control @error('record_time') is-invalid @enderror" id="record_time" name="record_time" value="{{ old('record_time', $heightWeight->record_time_formatted) }}">
                        @error('record_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="height" class="form-label">{{ __('life_stages.height') }}</label>
                        <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('height', $heightWeight->height) }}" placeholder="{{ __('life_stages.example', ['value' => '170']) }}">
                        @error('height')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="weight" class="form-label">{{ __('life_stages.weight') }}</label>
                        <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $heightWeight->weight) }}" placeholder="{{ __('life_stages.example', ['value' => '70']) }}">
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="image" class="form-label">{{ __('common.image') }}</label>
                        @if ($heightWeight->imageDocument)
                            <div class="mb-2">
                                <a href="{{ $heightWeight->imageDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-image me-1"></i> {{ __('common.view_current_image') }}
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="text-muted">{{ __('life_stages.max_10mb_leave_empty') }}</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="video" class="form-label">{{ __('life_stages.video') }}</label>
                        @if ($heightWeight->videoDocument)
                            <div class="mb-2">
                                <a href="{{ $heightWeight->videoDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-video me-1"></i> {{ __('common.view_current_video') }}
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('video') is-invalid @enderror" id="video" name="video" accept="video/*">
                        <small class="text-muted">{{ __('life_stages.max_50mb_leave_empty') }}</small>
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-check">
                            <input type="checkbox" name="show_in_education" value="1" class="form-check-input" {{ old('show_in_education', $heightWeight->show_in_education) ? 'checked' : '' }}>
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
