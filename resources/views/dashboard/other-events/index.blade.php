@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => isset($stage) && $stage
            ? [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'صفحاتي', 'url' => route('dashboard.my-pages.index')],
                ['label' => 'وثق - ' . $stage->name, 'url' => route('dashboard.my-pages.documents', $stage)],
                ['label' => 'أحداث أخرى'],
            ]
            : [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'أحداث أخرى'],
            ]
    ])
    <h4 class="mb-4">أحداث أخرى</h4>

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
        $createUrl = route('dashboard.other-events.create', isset($stage) && $stage ? ['stage' => $stage->id] : []);
    @endphp

    <div class="row g-4">
        @foreach ($otherEvents as $otherEvent)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if ($otherEvent->mediaDocument && str_starts_with($otherEvent->mediaDocument->mime_type ?? '', 'image/'))
                        <img src="{{ $otherEvent->mediaDocument->embed_url ?? $otherEvent->mediaDocument->view_url }}" class="card-img-top" alt="" style="height: 140px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-label-success d-flex align-items-center justify-content-center" style="height: 140px;">
                            <i class="bx {{ optional($otherEvent->mediaDocument)->file_icon ?? 'bx-calendar-event' }} bx-lg text-success"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ $otherEvent->title }}</h6>
                        <p class="card-text small text-body-secondary mb-1">
                            {{ $otherEvent->record_date->format('Y-m-d') }} {{ $otherEvent->record_time_formatted ? '• ' . $otherEvent->record_time_formatted : '' }}
                        </p>
                        @if ($otherEvent->other_info)
                            <p class="card-text small text-body-secondary mb-2">{{ Str::limit($otherEvent->other_info, 60) }}</p>
                        @endif
                        <div class="d-flex gap-2 mt-2">
                            @if ($otherEvent->mediaDocument)
                                <a href="{{ $otherEvent->mediaDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show"></i> عرض
                                </a>
                            @endif
                            <a href="{{ route('dashboard.other-events.edit', $otherEvent) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bx bx-edit"></i>
                            </a>
                            <form action="{{ route('dashboard.other-events.destroy', $otherEvent) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
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
            'label' => 'إضافة حدث',
            'icon' => 'bx-calendar-event',
        ])
    </div>

    @if ($otherEvents->hasPages())
        <div class="mt-4">
            {{ $otherEvents->links() }}
        </div>
    @endif
</div>
@endsection
