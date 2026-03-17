@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
            ['label' => __('packages.title'), 'url' => route('dashboard.packages.index')],
            ['label' => __('packages.add_package')],
        ]
    ])
    <h4 class="mb-4">{{ __('packages.create_title') }}</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.packages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="icon" class="form-label">{{ __('packages.icon') }}</label>
                    <input type="file" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" accept="image/*">
                    @error('icon')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="title" class="form-label">{{ __('common.title') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="monthly_price" class="form-label">{{ __('packages.monthly_price') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('monthly_price') is-invalid @enderror" id="monthly_price" name="monthly_price" value="{{ old('monthly_price', 0) }}" required>
                        @error('monthly_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="yearly_price" class="form-label">{{ __('packages.yearly_price') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('yearly_price') is-invalid @enderror" id="yearly_price" name="yearly_price" value="{{ old('yearly_price', 0) }}" required>
                        @error('yearly_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="max_pages" class="form-label">{{ __('packages.max_pages') }} <span class="text-danger">*</span></label>
                    <input type="number" min="1" class="form-control @error('max_pages') is-invalid @enderror" id="max_pages" name="max_pages" value="{{ old('max_pages', 1) }}" required>
                    <small class="text-body-secondary">{{ __('packages.max_pages_hint') }}</small>
                    @error('max_pages')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">{{ __('packages.features') }}</label>
                    <div id="features-container" data-placeholder="{{ __('packages.feature_placeholder') }}">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="features[]" placeholder="{{ __('packages.feature_placeholder') }} 1">
                            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()"><i class="bx bx-trash"></i></button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addFeature()"><i class="bx bx-plus me-1"></i> {{ __('packages.add_feature') }}</button>
                    <small class="d-block text-body-secondary mt-1">{{ __('packages.add_more_hint') }}</small>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('packages.create_btn') }}</button>
            </form>
        </div>
    </div>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const placeholder = container.dataset.placeholder || 'Feature';
    const count = container.querySelectorAll('.input-group').length + 1;
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" class="form-control" name="features[]" placeholder="${placeholder} ${count}">
        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()"><i class="bx bx-trash"></i></button>
    `;
    container.appendChild(div);
}
</script>
@endsection
