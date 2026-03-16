@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => isset($stage) && $stage
            ? [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'صفحاتي', 'url' => route('dashboard.my-pages.index')],
                ['label' => 'وثق - ' . $stage->name, 'url' => route('dashboard.my-pages.documents', $stage)],
                ['label' => 'الأصوات'],
            ]
            : [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'الأصوات'],
            ]
    ])
    <h4 class="mb-4">الأصوات</h4>

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
            يرجى <a href="{{ route('dashboard.documents.storage-connections') }}" class="alert-link">ربط منصة تخزين</a> لرفع الملفات الصوتية.
        </div>
    @endif

    @php
        $createUrl = route('dashboard.voices.create', isset($stage) && $stage ? ['stage' => $stage->id] : []);
    @endphp

    <div class="row g-4">
        @foreach ($voices as $voice)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-img-top bg-label-secondary d-flex align-items-center justify-content-center" style="height: 140px;">
                        @if ($voice->audioDocument)
                            <a href="{{ $voice->audioDocument->view_url }}" target="_blank" class="btn btn-primary btn-lg rounded-circle">
                                <i class="bx bx-play bx-lg"></i>
                            </a>
                        @else
                            <i class="bx bx-music bx-lg text-secondary"></i>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $voice->title }}</h6>
                        <p class="card-text small text-body-secondary mb-1">
                            {{ $voice->record_date->format('Y-m-d') }} {{ $voice->record_time_formatted ? '• ' . $voice->record_time_formatted : '' }}
                        </p>
                        @if ($voice->other_info)
                            <p class="card-text small text-body-secondary mb-2">{{ Str::limit($voice->other_info, 60) }}</p>
                        @endif
                        <div class="d-flex gap-2 mt-2">
                            @if ($voice->audioDocument)
                                <a href="{{ $voice->audioDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-play"></i> استماع
                                </a>
                            @endif
                            <a href="{{ route('dashboard.voices.edit', $voice) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bx bx-edit"></i>
                            </a>
                            <form action="{{ route('dashboard.voices.destroy', $voice) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
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
            'label' => 'إضافة صوت',
            'icon' => 'bx-music',
        ])
    </div>

    @if ($voices->hasPages())
        <div class="mt-4">
            {{ $voices->links() }}
        </div>
    @endif
</div>
@endsection
