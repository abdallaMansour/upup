@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('documents.index.title'), 'url' => route('dashboard.documents.index')],
                ['label' => __('dashboard.menu.storage_connections')],
            ]
        ])
        <div class="mb-4">
            <h4 class="mb-1">{{ __('documents.storage.title') }}</h4>
            <p class="text-body-secondary mb-0 small">{{ __('documents.storage.subtitle') }}</p>
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
                    {{ __('documents.storage.sync_fix') }}
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($primaryConnection ?? null)
            <div class="alert alert-info mb-4" role="alert">
                <strong>{{ __('documents.storage.active_platform') }}</strong> {{ \App\Models\StorageConnection::PROVIDERS[$primaryConnection->provider] ?? $primaryConnection->provider }}
                <span class="text-body-secondary">— {{ $primaryConnection->name }}</span>
            </div>
        @endif

        {{-- منصات متاحة للربط --}}
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="mb-3">{{ __('documents.storage.available_platforms') }}</h6>
            </div>
            @foreach ($storagePlatforms ?? [] as $platform)
                @php
                    $connected = in_array($platform->provider, $connectedProviderKeys ?? []);
                    $isPrimary = ($primaryConnection ?? null) && $primaryConnection->provider === $platform->provider;
                    $isNonPrimaryConnected = $connected && !$isPrimary;
                    $restoreConnection = $connectionsForRestore[$platform->provider] ?? null;
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
                                                <span class="badge bg-success">{{ __('documents.storage.connected') }}</span>
                                            @elseif (!$isActive)
                                                <span class="badge bg-secondary">{{ __('documents.storage.unavailable') }}</span>
                                            @else
                                                <span class="badge bg-label-secondary">{{ __('documents.storage.disconnected') }}</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @if ($isNonPrimaryConnected && $restoreConnection && $isActive)
                                    <a href="{{ route('dashboard.documents.switch-storage.confirm-restore', ['connection_id' => $restoreConnection->id]) }}" class="btn btn-sm btn-primary" title="الانتقال إلى هذه المنصة">
                                        <i class="bx bx-transfer me-1"></i> الانتقال إلى هذه المنصة
                                    </a>
                                @elseif ($isPrimary && $platform->provider === 'google_drive' && $isActive)
                                    <a href="{{ route('dashboard.documents.google-drive.connect') }}" class="btn btn-sm btn-outline-warning" title="{{ __('documents.storage.reconnect') }}">
                                        <i class="bx bx-link-alt me-1"></i> {{ __('documents.storage.reconnect_btn') }}
                                    </a>
                                    <form action="{{ route('dashboard.documents.google-drive.sync') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="{{ __('documents.storage.sync_title') }}">
                                            <i class="bx bx-refresh me-1"></i> {{ __('documents.storage.sync') }}
                                        </button>
                                    </form>
                                @elseif ($isPrimary && $platform->provider === 'wasabi' && $isActive)
                                    <a href="{{ route('dashboard.documents.wasabi.connect') }}" class="btn btn-sm btn-outline-warning" title="{{ __('documents.storage.reconnect_btn') }}">
                                        <i class="bx bx-link-alt me-1"></i> {{ __('documents.storage.reconnect_btn') }}
                                    </a>
                                    <form action="{{ route('dashboard.documents.wasabi.sync') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="{{ __('documents.storage.sync_title') }}">
                                            <i class="bx bx-refresh me-1"></i> {{ __('documents.storage.sync') }}
                                        </button>
                                    </form>
                                @elseif (!$connected && $platform->provider === 'google_drive' && $isActive)
                                    <a href="{{ ($hasPrimaryConnection ?? false) ? route('dashboard.documents.switch-storage.confirm', ['to' => 'google_drive']) : route('dashboard.documents.google-drive.connect') }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-link me-1"></i> {{ __('documents.storage.connect') }}
                                    </a>
                                @elseif (!$connected && $platform->provider === 'wasabi' && $isActive)
                                    <a href="{{ ($hasPrimaryConnection ?? false) ? route('dashboard.documents.switch-storage.confirm', ['to' => 'wasabi']) : route('dashboard.documents.wasabi.connect') }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-link me-1"></i> {{ __('documents.storage.connect') }}
                                    </a>
                                @elseif (!$isActive)
                                    <button type="button" class="btn btn-sm btn-secondary" disabled title="{{ __('documents.storage.platform_unavailable') }}">
                                        <i class="bx bx-link me-1"></i> {{ __('documents.storage.unavailable') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-secondary" disabled title="{{ __('documents.storage.coming_soon') }}">
                                        <i class="bx bx-link me-1"></i> {{ __('documents.storage.coming_soon_btn') }}
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
                <h5 class="mb-0">{{ __('documents.storage.current_connections') }}</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('documents.storage.platform') }}</th>
                            <th>{{ __('common.name') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th>{{ __('documents.storage.connected_at') }}</th>
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
                                        <span class="badge bg-success">{{ __('documents.storage.active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('documents.storage.inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ $conn->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-body-secondary">
                                    {{ __('documents.storage.no_connections') }}
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
