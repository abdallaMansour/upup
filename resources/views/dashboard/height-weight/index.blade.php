@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => isset($stage) && $stage
            ? [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('dashboard.breadcrumb.my_pages'), 'url' => route('dashboard.my-pages.index')],
                ['label' => __('my_pages.documents') . ' - ' . $stage->name, 'url' => route('dashboard.my-pages.documents', $stage)],
                ['label' => __('dashboard.breadcrumb.height_weight')],
            ]
            : [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('dashboard.breadcrumb.height_weight')],
            ]
    ])
    <h4 class="mb-4">{{ __('life_stages.height_weight') }}</h4>

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
            {!! __('documents.storage_required_media', ['link' => route('dashboard.documents.storage-connections')]) !!}
        </div>
    @endif

    @php
        $createUrl = route('dashboard.height-weight.create', isset($stage) && $stage ? ['stage' => $stage->id] : []);
    @endphp

    <div class="row g-4">
        @foreach ($records as $record)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if ($record->imageDocument)
                        <img src="{{ $record->imageDocument->embed_url ?? $record->imageDocument->view_url }}" class="card-img-top" alt="" style="height: 140px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-label-secondary d-flex align-items-center justify-content-center" style="height: 140px;">
                            <i class="bx bx-ruler bx-lg text-secondary"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ $record->record_date->format('Y-m-d') }} {{ $record->record_time_formatted ? '• ' . $record->record_time_formatted : '' }}</h6>
                        <p class="card-text small text-body-secondary mb-2">
                            {{ __('life_stages.height_display') }}: {{ $record->height ?? '-' }} {{ __('life_stages.unit_cm') }} • {{ __('life_stages.weight_display') }}: {{ $record->weight ?? '-' }} {{ __('life_stages.unit_kg') }}
                        </p>
                        @if ($record->videoDocument)
                            <a href="{{ $record->videoDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary mb-2">
                                <i class="bx bx-video"></i> {{ __('life_stages.video') }}
                            </a>
                        @endif
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('dashboard.height-weight.edit', $record) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bx bx-edit"></i>
                            </a>
                            <form action="{{ route('dashboard.height-weight.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('life_stages.confirm_delete') }}');">
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
            'label' => __('life_stages.add_record_label'),
            'icon' => 'bx-ruler',
        ])
    </div>

    @if ($records->hasPages())
        <div class="mt-4">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
