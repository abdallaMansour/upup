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
            @foreach ($storagePlatforms ?? [] as $platform)
                @php
                    $connected = in_array($platform->provider, $connectedProviderKeys ?? []);
                    $isActive = $platform->is_active;
                    $platformIcon = match($platform->provider) {
                        'google_drive' => 'bx bxl-google',
                        'wasabi' => 'bx bx-cloud-upload',
                        'dropbox' => 'bx bxl-dropbox',
                        'one_drive' => 'bx bxl-microsoft',
                        default => 'bx bx-cloud',
                    };
                @endphp
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 {{ $connected ? 'border-success border-2' : 'border' }} {{ !$isActive ? 'bg-light opacity-75' : '' }}">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                                <div class="d-flex align-items-center flex-grow-1 min-w-0">
                                    <span class="avatar avatar-lg me-3 rounded flex-shrink-0 d-flex align-items-center justify-content-center {{ $connected ? 'bg-success' : 'bg-label-primary' }}">
                                        <i class="{{ $platformIcon }} fs-4"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <h6 class="mb-0 text-truncate">{{ $platform->name }}</h6>
                                        <small class="text-body-secondary d-block mt-1">
                                            @if ($connected)
                                                <span class="badge bg-success">متصل</span>
                                            @elseif (!$isActive)
                                                <span class="badge bg-secondary">غير متاحة</span>
                                            @else
                                                <span class="badge bg-label-secondary">غير متصل</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @if ($connected)
                                    @if ($platform->provider === 'google_drive' && $isActive)
                                        <a href="{{ route('dashboard.documents.google-drive.connect') }}" class="btn btn-sm btn-outline-warning" title="إعادة الربط (مطلوب لإضافة/حذف الملفات)">
                                            <i class="bx bx-link-alt me-1"></i> إعادة الربط
                                        </a>
                                        <form action="{{ route('dashboard.documents.google-drive.sync') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="مزامنة الملفات">
                                                <i class="bx bx-refresh me-1"></i> مزامنة
                                            </button>
                                        </form>
                                    @elseif ($platform->provider === 'wasabi' && $isActive)
                                        <a href="{{ route('dashboard.documents.wasabi.connect') }}" class="btn btn-sm btn-outline-warning" title="إعادة الربط">
                                            <i class="bx bx-link-alt me-1"></i> إعادة الربط
                                        </a>
                                        <form action="{{ route('dashboard.documents.wasabi.sync') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="مزامنة الملفات">
                                                <i class="bx bx-refresh me-1"></i> مزامنة
                                            </button>
                                        </form>
                                    @endif
                                @elseif ($platform->provider === 'google_drive' && $isActive)
                                    <a href="{{ route('dashboard.documents.google-drive.connect') }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-link me-1"></i> ربط
                                    </a>
                                @elseif ($platform->provider === 'wasabi' && $isActive)
                                    <a href="{{ route('dashboard.documents.wasabi.connect') }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-link me-1"></i> ربط
                                    </a>
                                @elseif (!$isActive)
                                    <button type="button" class="btn btn-sm btn-secondary" disabled title="المنصة غير متاحة حالياً">
                                        <i class="bx bx-link me-1"></i> غير متاحة
                                    </button>
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
