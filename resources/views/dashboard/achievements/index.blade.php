@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">الإنجازات</h4>
        <a href="{{ route('dashboard.achievements.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> إضافة إنجاز
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

    @if (!($primaryConnection ?? null))
        <div class="alert alert-warning mb-4">
            <i class="bx bx-info-circle me-2"></i>
            يرجى <a href="{{ route('dashboard.documents.storage-connections') }}" class="alert-link">ربط منصة تخزين</a> لرفع الصور والفيديوهات.
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if ($achievements->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bx bx-trophy bx-lg mb-3"></i>
                    <p class="mb-0">لا توجد إنجازات. <a href="{{ route('dashboard.achievements.create') }}">أضف أول إنجاز</a></p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الوقت</th>
                                <th>النوع</th>
                                <th>العنوان</th>
                                <th>المكان</th>
                                <th>السنة الدراسية</th>
                                <th>الشهادة</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($achievements as $achievement)
                                <tr>
                                    <td>{{ $achievement->record_date->format('Y-m-d') }}</td>
                                    <td>{{ $achievement->record_time_formatted ?? '-' }}</td>
                                    <td>{{ $achievement->type_label }}</td>
                                    <td>{{ $achievement->title }}</td>
                                    <td>{{ $achievement->place ?? '-' }}</td>
                                    <td>{{ $achievement->academic_year ?? '-' }}</td>
                                    <td>
                                        @if ($achievement->certificateImageDocument)
                                            <a href="{{ $achievement->certificateImageDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-image"></i>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.achievements.edit', $achievement) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.achievements.destroy', $achievement) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $achievements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
