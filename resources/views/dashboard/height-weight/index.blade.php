@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if (isset($stage) && $stage)
        <a href="{{ route('dashboard.my-pages.documents', $stage) }}" class="btn btn-label-secondary mb-3">
            <i class="bx bx-arrow-back me-1"></i> رجوع إلى وثق
        </a>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">الطول والوزن</h4>
        <a href="{{ route('dashboard.height-weight.create', isset($stage) && $stage ? ['stage' => $stage->id] : []) }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> إضافة سجل
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
            @if ($records->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bx bx-ruler bx-lg mb-3"></i>
                    <p class="mb-0">لا توجد سجلات. <a href="{{ route('dashboard.height-weight.create', isset($stage) && $stage ? ['stage' => $stage->id] : []) }}">أضف أول سجل</a></p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الوقت</th>
                                <th>الطول (سم)</th>
                                <th>الوزن (كجم)</th>
                                <th>الصورة</th>
                                <th>الفيديو</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    <td>{{ $record->record_date->format('Y-m-d') }}</td>
                                    <td>{{ $record->record_time_formatted ?? '-' }}</td>
                                    <td>{{ $record->height ?? '-' }}</td>
                                    <td>{{ $record->weight ?? '-' }}</td>
                                    <td>
                                        @if ($record->imageDocument)
                                            <a href="{{ $record->imageDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-image"></i>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($record->videoDocument)
                                            <a href="{{ $record->videoDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bx bx-video"></i>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.height-weight.edit', $record) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.height-weight.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
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
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
