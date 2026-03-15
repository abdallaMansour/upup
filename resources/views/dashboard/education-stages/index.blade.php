@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">المراحل التعليمية</h4>
        @if(auth('admin')->check() && auth('admin')->user()->hasPermission('education-stages.manage'))
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.education-grades.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-book me-1"></i> الصفوف
                </a>
                <a href="{{ route('dashboard.education-stages.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> إضافة مرحلة
                </a>
            </div>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>الاسم</th>
                        <th>عدد الصفوف</th>
                        @if(auth('admin')->check() && auth('admin')->user()->hasPermission('education-stages.manage'))
                            <th width="120">الإجراءات</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stages as $stage)
                        <tr>
                            <td>{{ $stage->id }}</td>
                            <td><strong>{{ $stage->name }}</strong></td>
                            <td>{{ $stage->grades_count }}</td>
                            @if(auth('admin')->check() && auth('admin')->user()->hasPermission('education-stages.manage'))
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('dashboard.education-stages.edit', $stage) }}">
                                                <i class="bx bx-edit-alt me-2"></i> تعديل
                                            </a>
                                            <form action="{{ route('dashboard.education-stages.stages.destroy', $stage) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟ سيتم حذف جميع الصفوف التابعة لهذه المرحلة.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bx bx-trash me-2"></i> حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth('admin')->check() && auth('admin')->user()->hasPermission('education-stages.manage') ? '4' : '3' }}" class="text-center py-5 text-body-secondary">
                                لا توجد مراحل تعليمية بعد.
                                @if(auth('admin')->check() && auth('admin')->user()->hasPermission('education-stages.manage'))
                                    <a href="{{ route('dashboard.education-stages.create') }}">إضافة مرحلة</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
