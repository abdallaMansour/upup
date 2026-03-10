@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">صفحاتي</h4>
        <a href="{{ route('dashboard.my-pages.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> إضافة مرحلة جديدة
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($stages->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bx bx-child bx-lg text-muted mb-3"></i>
                <p class="text-muted mb-4">لا توجد مراحل طفولة. <a href="{{ route('dashboard.my-pages.create') }}">أضف أول مرحلة</a></p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach ($stages as $stage)
                @php
                    $coverUrl = $stage->coverImageDocument?->view_url ?? $stage->firstPhotoDocument?->view_url;
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        @if ($coverUrl)
                            <img src="{{ $coverUrl }}" class="card-img-top" alt="{{ $stage->name }}" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-label-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="bx bx-child bx-lg text-secondary"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $stage->name }}</h5>
                            <p class="card-text text-body-secondary small mb-3">
                                <i class="bx bx-calendar me-1"></i> {{ $stage->created_at->format('Y-m-d') }}
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary" title="عرض (قريباً)">
                                    <i class="bx bx-show"></i> عرض
                                </a>
                                <a href="{{ route('dashboard.my-pages.edit', $stage) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bx bx-cog"></i> إعدادات
                                </a>
                                <a href="{{ route('dashboard.my-pages.documents', $stage) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bx bx-file-blank"></i> وثق
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-warning" title="صلاحيه مؤقته (قريباً)">
                                    <i class="bx bx-lock-alt"></i> صلاحيه مؤقته
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-stage" data-stage-id="{{ $stage->id }}" data-stage-name="{{ $stage->name }}" data-delete-url="{{ route('dashboard.my-pages.destroy', $stage) }}">
                                    <i class="bx bx-trash"></i> حذف
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@if ($stages->isNotEmpty())
<form id="delete-stage-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="name_confirmation" id="delete-name-confirmation">
</form>
@endif
@endsection

@section('page-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete-stage').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const stageName = this.dataset.stageName;
            const deleteUrl = this.dataset.deleteUrl;

            Swal.fire({
                title: 'تأكيد الحذف',
                html: 'لحذف هذه المرحلة، يرجى كتابة الاسم في الحقل أدناه:<br><strong>' + stageName + '</strong>',
                input: 'text',
                inputLabel: 'اكتب الاسم للتأكيد',
                inputPlaceholder: stageName,
                showCancelButton: true,
                confirmButtonText: 'حذف',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#d33',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false,
                preConfirm: (value) => {
                    if (value !== stageName) {
                        Swal.showValidationMessage('الاسم غير مطابق');
                        return false;
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-stage-form');
                    form.action = deleteUrl;
                    form.querySelector('#delete-name-confirmation').value = result.value;
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
