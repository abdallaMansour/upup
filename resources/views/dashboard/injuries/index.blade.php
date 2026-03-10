@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if (isset($stage) && $stage)
        <a href="{{ route('dashboard.my-pages.documents', $stage) }}" class="btn btn-label-secondary mb-3">
            <i class="bx bx-arrow-back me-1"></i> رجوع إلى وثق
        </a>
    @endif
    <h4 class="mb-4">الإصابات</h4>

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
        $createUrl = route('dashboard.injuries.create', isset($stage) && $stage ? ['stage' => $stage->id] : []);
    @endphp

    <div class="row g-4">
        @foreach ($injuries as $injury)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if ($injury->mediaDocument && str_starts_with($injury->mediaDocument->mime_type ?? '', 'image/'))
                        <img src="{{ $injury->mediaDocument->view_url }}" class="card-img-top" alt="" style="height: 140px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-label-danger d-flex align-items-center justify-content-center" style="height: 140px;">
                            <i class="bx {{ optional($injury->mediaDocument)->file_icon ?? 'bx-first-aid' }} bx-lg text-danger"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ $injury->title }}</h6>
                        <p class="card-text small text-body-secondary mb-1">
                            {{ $injury->record_date->format('Y-m-d') }} {{ $injury->record_time_formatted ? '• ' . $injury->record_time_formatted : '' }}
                        </p>
                        @if ($injury->other_info)
                            <p class="card-text small text-body-secondary mb-2">{{ Str::limit($injury->other_info, 60) }}</p>
                        @endif
                        <div class="d-flex gap-2 mt-2">
                            @if ($injury->mediaDocument)
                                <a href="{{ $injury->mediaDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show"></i> عرض
                                </a>
                            @endif
                            <a href="{{ route('dashboard.injuries.edit', $injury) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bx bx-edit"></i>
                            </a>
                            <form action="{{ route('dashboard.injuries.destroy', $injury) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
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
            'label' => 'إضافة إصابة',
            'icon' => 'bx-first-aid',
        ])
    </div>

    @if ($injuries->hasPages())
        <div class="mt-4">
            {{ $injuries->links() }}
        </div>
    @endif
</div>
@endsection
