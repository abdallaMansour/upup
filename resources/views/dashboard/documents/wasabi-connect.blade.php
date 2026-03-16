@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('documents.index.title'), 'url' => route('dashboard.documents.index')],
                ['label' => __('dashboard.menu.storage_connections'), 'url' => route('dashboard.documents.storage-connections')],
                ['label' => __('documents.wasabi.title')],
            ]
        ])
        <div class="mb-4">
            <h4 class="mb-1">{{ __('documents.wasabi.title') }}</h4>
            <p class="text-body-secondary mb-0 small">{{ __('documents.wasabi.subtitle') }} <a href="https://console.wasabisys.com" target="_blank" rel="noopener">Wasabi Console</a></p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.documents.wasabi.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="access_key" class="form-label">{{ __('documents.wasabi.access_key') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('access_key') is-invalid @enderror" id="access_key" name="access_key" value="{{ old('access_key') }}" placeholder="{{ __('documents.wasabi.access_key_placeholder') }}" required autofocus>
                        @error('access_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="secret_key" class="form-label">{{ __('documents.wasabi.secret_key') }} <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('secret_key') is-invalid @enderror" id="secret_key" name="secret_key" required>
                        @error('secret_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-body-secondary">{{ __('documents.wasabi.secret_key_hint') }}</small>
                    </div>
                    <div class="mb-3">
                        <label for="bucket" class="form-label">{{ __('documents.wasabi.bucket') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('bucket') is-invalid @enderror" id="bucket" name="bucket" value="{{ old('bucket') }}" placeholder="{{ __('documents.wasabi.bucket_placeholder') }}" required>
                        @error('bucket')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="region" class="form-label">{{ __('documents.wasabi.region') }}</label>
                        <select class="form-select @error('region') is-invalid @enderror" id="region" name="region" title="يجب أن تطابق منطقة إنشاء الـ Bucket في Wasabi Console">
                            <option value="us-east-1" {{ old('region', 'us-east-1') === 'us-east-1' ? 'selected' : '' }}>US East 1 (N. Virginia)</option>
                            <option value="us-east-2" {{ old('region') === 'us-east-2' ? 'selected' : '' }}>US East 2 (N. Virginia)</option>
                            <option value="us-west-1" {{ old('region') === 'us-west-1' ? 'selected' : '' }}>US West 1 (Oregon)</option>
                            <option value="eu-central-1" {{ old('region') === 'eu-central-1' ? 'selected' : '' }}>EU Central 1 (Amsterdam)</option>
                            <option value="eu-central-2" {{ old('region') === 'eu-central-2' ? 'selected' : '' }}>EU Central 2 (Warsaw)</option>
                            <option value="ap-northeast-1" {{ old('region') === 'ap-northeast-1' ? 'selected' : '' }}>AP Northeast 1 (Tokyo)</option>
                            <option value="ap-northeast-2" {{ old('region') === 'ap-northeast-2' ? 'selected' : '' }}>AP Northeast 2 (Osaka)</option>
                        </select>
                        @error('region')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-body-secondary">يمكنك معرفة منطقة الـ Bucket من Wasabi Console عند عرض تفاصيل الـ Bucket</small>
                    </div>
                    <div class="mb-4">
                        <label for="prefix" class="form-label">{{ __('documents.wasabi.prefix') }}</label>
                        <input type="text" class="form-control @error('prefix') is-invalid @enderror" id="prefix" name="prefix" value="{{ old('prefix') }}" placeholder="{{ __('documents.wasabi.prefix_placeholder') }}">
                        @error('prefix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-body-secondary">{{ __('documents.wasabi.prefix_hint') }}</small>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('documents.wasabi.connection_name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __('documents.wasabi.connection_name_placeholder') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-link me-1"></i> {{ __('documents.wasabi.connect_btn') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
