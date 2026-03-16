@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
            ['label' => __('packages.title'), 'url' => route('dashboard.packages.index')],
            ['label' => __('packages.edit_package')],
        ]
    ])
    <h4 class="mb-4">{{ __('packages.edit_title') }}</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="icon" class="form-label">{{ __('packages.icon') }}</label>
                    @if ($package->hasMedia('icon'))
                    <div class="mb-2">
                        <img src="{{ $package->getFirstMediaUrl('icon') }}" alt="{{ $package->title }}" class="rounded" style="max-height: 60px;">
                        <small class="d-block text-body-secondary">{{ __('packages.current_icon_replace') }}</small>
                    </div>
                    @endif
                    <input type="file" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" accept="image/*">
                    @error('icon')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="title" class="form-label">{{ __('common.title') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $package->title) }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="monthly_price" class="form-label">{{ __('packages.monthly_price') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('monthly_price') is-invalid @enderror" id="monthly_price" name="monthly_price" value="{{ old('monthly_price', $package->monthly_price) }}" required>
                        @error('monthly_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="yearly_price" class="form-label">{{ __('packages.yearly_price') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('yearly_price') is-invalid @enderror" id="yearly_price" name="yearly_price" value="{{ old('yearly_price', $package->yearly_price) }}" required>
                        @error('yearly_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">{{ __('packages.features') }}</label>
                    <div id="features-container" data-placeholder="{{ __('packages.feature_placeholder') }}">
                        @if ($package->features && count($package->features) > 0)
                            @foreach ($package->features as $feature)
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="features[]" value="{{ $feature }}" placeholder="{{ __('packages.feature_placeholder') }}">
                                <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()"><i class="bx bx-trash"></i></button>
                            </div>
                            @endforeach
                        @else
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="features[]" placeholder="{{ __('packages.feature_placeholder') }} 1">
                            <button type="button" class="btn btn-outline-secondary" onclick="addFeature()"><i class="bx bx-plus"></i></button>
                        </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addFeature()"><i class="bx bx-plus me-1"></i> {{ __('packages.add_feature') }}</button>
                    <small class="d-block text-body-secondary mt-1">{{ __('packages.add_more_hint') }}</small>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('packages.update_btn') }}</button>
            </form>
        </div>
    </div>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const count = container.querySelectorAll('.input-group').length + 1;
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    const placeholder = container.dataset.placeholder || 'Feature';
    div.innerHTML = `
        <input type="text" class="form-control" name="features[]" placeholder="${placeholder} ${count}">
        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()"><i class="bx bx-trash"></i></button>
    `;
    container.appendChild(div);
}
</script>
@endsection
