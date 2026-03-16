@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('features.title'), 'url' => route('dashboard.features.index')],
                ['label' => __('features.edit_feature')],
            ]
        ])
        <h4 class="mb-4">{{ __('features.edit_title') }}</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.features.update', $feature) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="image" class="form-label">{{ __('features.image') }}</label>
                        @if ($feature->hasMedia('image'))
                            <div class="mb-2">
                                <img src="{{ $feature->getFirstMediaUrl('image') }}" alt="{{ $feature->title }}" class="rounded" style="max-height: 80px;">
                                <small class="d-block text-body-secondary">{{ __('features.current_image_replace') }}</small>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="title" class="form-label">{{ __('common.title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $feature->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">الوصف <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $feature->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('features.update_btn') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
