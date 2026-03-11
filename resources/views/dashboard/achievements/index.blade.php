@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if (isset($stage) && $stage)
        <a href="{{ route('dashboard.my-pages.documents', $stage) }}" class="btn btn-label-secondary mb-3">
            <i class="bx bx-arrow-back me-1"></i> رجوع إلى وثق
        </a>
    @endif
    <h4 class="mb-4">الإنجازات</h4>

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

    @php
        $createUrl = route('dashboard.achievements.create', isset($stage) && $stage ? ['stage' => $stage->id] : []);
    @endphp

    <div class="row g-4">
        @foreach ($achievements as $achievement)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if ($achievement->certificateImageDocument)
                        <img src="{{ $achievement->certificateImageDocument->embed_url ?? $achievement->certificateImageDocument->view_url }}" class="card-img-top" alt="" style="height: 140px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-label-primary d-flex align-items-center justify-content-center" style="height: 140px;">
                            <i class="bx bx-trophy bx-lg text-primary"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ $achievement->title }}</h6>
                        <p class="card-text small text-body-secondary mb-1">
                            {{ $achievement->record_date->format('Y-m-d') }} {{ $achievement->record_time_formatted ? '• ' . $achievement->record_time_formatted : '' }}
                        </p>
                        <p class="card-text small text-body-secondary mb-2">
                            {{ $achievement->type_label }} @if($achievement->place) • {{ $achievement->place }} @endif
                        </p>
                        <div class="d-flex gap-2 mt-2">
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
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @include('dashboard.partials.add-card', [
            'url' => $createUrl,
            'label' => 'إضافة إنجاز',
            'icon' => 'bx-trophy',
        ])
    </div>

    @if ($achievements->hasPages())
        <div class="mt-4">
            {{ $achievements->links() }}
        </div>
    @endif
</div>
@endsection
