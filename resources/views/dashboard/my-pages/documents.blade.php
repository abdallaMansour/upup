@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">وثق - {{ $stage->name }}</h4>
        <a href="{{ route('dashboard.my-pages.index') }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> رجوع
        </a>
    </div>

    <p class="text-body-secondary mb-4">اختر القسم الذي تريد إضافة أو عرض المحتوى فيه</p>

    <div class="row g-4">
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.height-weight.index', ['stage' => $stage->id]) }}" class="card h-100 text-decoration-none border shadow-sm">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-warning" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-ruler icon-lg text-warning"></i>
                    </span>
                    <h6 class="card-title mb-1">الطول والوزن</h6>
                    <small class="text-body-secondary">تتبع النمو</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.achievements.index', ['stage' => $stage->id]) }}" class="card h-100 text-decoration-none border shadow-sm">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-primary" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-trophy icon-lg text-primary"></i>
                    </span>
                    <h6 class="card-title mb-1">الإنجازات</h6>
                    <small class="text-body-secondary">إنجازاتي</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.voices.index', ['stage' => $stage->id]) }}" class="card h-100 text-decoration-none border shadow-sm">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-secondary" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-music icon-lg text-secondary"></i>
                    </span>
                    <h6 class="card-title mb-1">الأصوات</h6>
                    <small class="text-body-secondary">التسجيلات الصوتية</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.drawings.index', ['stage' => $stage->id]) }}" class="card h-100 text-decoration-none border shadow-sm">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-danger" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-palette icon-lg text-danger"></i>
                    </span>
                    <h6 class="card-title mb-1">الرسم</h6>
                    <small class="text-body-secondary">رسوماتي</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.visits.index', ['stage' => $stage->id]) }}" class="card h-100 text-decoration-none border shadow-sm">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-info" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-map-alt icon-lg text-info"></i>
                    </span>
                    <h6 class="card-title mb-1">الزيارات</h6>
                    <small class="text-body-secondary">سجل الزيارات</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.injuries.index', ['stage' => $stage->id]) }}" class="card h-100 text-decoration-none border shadow-sm">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-danger" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-first-aid icon-lg text-danger"></i>
                    </span>
                    <h6 class="card-title mb-1">الإصابات</h6>
                    <small class="text-body-secondary">سجل الإصابات</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('dashboard.other-events.index', ['stage' => $stage->id]) }}" class="card h-100 text-decoration-none border shadow-sm">
                <div class="card-body text-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-success" style="width: 56px; height: 56px;">
                        <i class="icon-base bx bx-calendar-event icon-lg text-success"></i>
                    </span>
                    <h6 class="card-title mb-1">أحداث أخرى</h6>
                    <small class="text-body-secondary">مناسبات وذكريات</small>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
