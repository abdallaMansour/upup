@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="mb-1">ربط منصات التخزين السحابي</h4>
                <p class="text-body-secondary mb-0 small">اربط حسابك على Google Drive أو Wasabi أو غيرها لعرض ملفاتك ووثائقك</p>
            </div>
            <a href="{{ route('dashboard.documents.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> العودة للوثائق
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
                @if (str_contains(session('error') ?? '', 'SYNC_FAILED'))
                    <hr>
                    <strong>الحل:</strong> اضغط على "إعادة الربط" بجانب Google Drive للحصول على صلاحيات إضافة وحذف الملفات.
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- منصات متاحة للربط --}}
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="mb-3">المنصات المتاحة</h6>
            </div>
            @foreach (\App\Models\StorageConnection::PROVIDERS as $key => $label)
                @php
                    $connected = in_array($key, $connectedProviderKeys ?? []);
                @endphp
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 {{ $connected ? 'border-success' : '' }}">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-md me-3 rounded bg-label-primary">
                                    <i class="bx bx-cloud-download icon-lg"></i>
                                </span>
                                <div>
                                    <h6 class="mb-0">{{ $label }}</h6>
                                    <small class="text-body-secondary">
                                        @if ($connected)
                                            <span class="text-success">متصل</span>
                                        @else
                                            غير متصل
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div>
                                @if ($connected)
                                    <div class="d-flex gap-1 align-items-center flex-wrap">
                                        <span class="badge bg-success">متصل</span>
                                        @if ($key === 'google_drive')
                                            <a href="{{ route('dashboard.documents.google-drive.connect') }}" class="btn btn-sm btn-outline-warning" title="إعادة الربط (مطلوب لإضافة/حذف الملفات)">
                                                <i class="bx bx-link-alt me-1"></i> إعادة الربط
                                            </a>
                                            <form action="{{ route('dashboard.documents.google-drive.sync') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-icon btn-outline-primary" title="مزامنة الملفات">
                                                    <i class="bx bx-refresh"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @elseif ($key === 'google_drive')
                                    <a href="{{ route('dashboard.documents.google-drive.connect') }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-link me-1"></i> ربط
                                    </a>
                                @else
                                    <button type="button" class="btn btn-sm btn-secondary" disabled title="قريباً - جاري إعداد التكامل">
                                        <i class="bx bx-link me-1"></i> قريباً
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- قائمة الاتصالات الحالية --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">الاتصالات الحالية</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>المنصة</th>
                            <th>الاسم</th>
                            <th>الحالة</th>
                            <th>تاريخ الربط</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($connections as $conn)
                            <tr>
                                <td>
                                    <span class="badge bg-label-primary">
                                        {{ \App\Models\StorageConnection::PROVIDERS[$conn->provider] ?? $conn->provider }}
                                    </span>
                                </td>
                                <td>{{ $conn->display_name }}</td>
                                <td>
                                    @if ($conn->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td>{{ $conn->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-body-secondary">
                                    لم تقم بربط أي منصة تخزين بعد. اختر منصة من الأعلى واتبع خطوات الربط.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($connections->hasPages())
                <div class="card-footer">
                    {{ $connections->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
