@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
            ['label' => __('dashboard.breadcrumb.my_pages'), 'url' => route('dashboard.my-pages.index')],
            ['label' => __('my_pages.create.title')],
        ]
    ])
    <h4 class="mb-4">{{ __('my_pages.create.title') }}</h4>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!($primaryConnection ?? null))
        <div class="alert alert-warning">
            <i class="bx bx-info-circle me-2"></i>
            {!! __('documents.storage_required', ['link' => route('dashboard.documents.storage-connections')]) !!}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('life_stages.childhood_data') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.my-pages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- صفحة عامة/خاصة --}}
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_public">{{ __('my_pages.public_page') }}</label>
                </div>
                <div id="is_public_warning" class="alert alert-warning py-2 mb-4 d-none" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    <strong>{{ __('my_pages.public_warning_title') }}</strong><br>
                    <small>{{ __('my_pages.public_warning_desc') }}</small>
                </div>

                @include('dashboard.my-pages._form', ['stage' => null])

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> حفظ
                </button>
                @if (!($primaryConnection ?? null))
                    <small class="text-muted ms-2">(لرفع الملفات يرجى ربط منصة تخزين أولاً)</small>
                @endif
            </form>
        </div>
    </div>
</div>

@section('page-js')
<script>
document.getElementById('is_public').addEventListener('change', function() {
    const warning = document.getElementById('is_public_warning');
    warning.classList.toggle('d-none', !this.checked);
});
if (document.getElementById('is_public').checked) {
    document.getElementById('is_public_warning').classList.remove('d-none');
}
</script>
@endsection
@endsection
