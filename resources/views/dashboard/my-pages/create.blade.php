@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
            ['label' => 'صفحاتي', 'url' => route('dashboard.my-pages.index')],
            ['label' => 'إضافة مرحلة طفولة جديدة'],
        ]
    ])
    <h4 class="mb-4">إضافة مرحلة طفولة جديدة</h4>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!($primaryConnection ?? null))
        <div class="alert alert-warning">
            <i class="bx bx-info-circle me-2"></i>
            يرجى <a href="{{ route('dashboard.documents.storage-connections') }}" class="alert-link">ربط منصة تخزين</a> (Google Drive أو Wasabi) لرفع الملفات والصور.
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">بيانات مرحلة الطفولة</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.my-pages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- صفحة عامة/خاصة --}}
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_public">صفحة عامة (يمكن للآخرين مشاهدتها)</label>
                </div>
                <div id="is_public_warning" class="alert alert-warning py-2 mb-4 d-none" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    <strong>هل أنت متأكد من فتح الصفحة للجميع؟</strong><br>
                    <small>ننصح بإبقاء الصفحة مغلقة لأنها تحتوي على بيانات شخصية.</small>
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
