@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('packages.title')],
            ]
        ])
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">{{ __('packages.title') }}</h4>
            @if (auth('admin')->check() && auth('admin')->user()->hasPermission('packages.create'))
                <a href="{{ route('dashboard.packages.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> {{ __('packages.add_package') }}
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="60">{{ __('packages.icon') }}</th>
                            <th>{{ __('common.title') }}</th>
                            <th>{{ __('packages.monthly_price') }}</th>
                            <th>{{ __('packages.yearly_price') }}</th>
                            <th>{{ __('packages.features') }}</th>
                            @if (
                                auth('web')->check() ||
                                (
                                    auth('admin')->check() &&
                                    (
                                        auth('admin')->user()->hasPermission('packages.edit') ||
                                        auth('admin')->user()->hasPermission('packages.delete')
                                    )
                                )
                            )
                                <th width="140">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $package)
                            <tr>
                                <td>
                                    @if ($package->hasMedia('icon'))
                                        <img src="{{ $package->getFirstMediaUrl('icon') }}" alt="{{ $package->title }}" class="rounded" style="width: 40px; height: 40px; object-fit: contain;">
                                    @else
                                        <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-package"></i></span>
                                    @endif
                                </td>
                                <td><strong>{{ $package->title }}</strong></td>
                                <td>${{ number_format($package->monthly_price, 2) }}</td>
                                <td>${{ number_format($package->yearly_price, 2) }}</td>
                                <td>
                                    @if ($package->features && count($package->features) > 0)
                                        <span class="badge bg-label-primary">{{ __('packages.feature_count', ['count' => count($package->features)]) }}</span>
                                    @else
                                        <span class="text-body-secondary">-</span>
                                    @endif
                                </td>
                                @if (auth('admin')->check() && (auth('admin')->user()->hasPermission('packages.edit') || auth('admin')->user()->hasPermission('packages.delete')))
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @if (auth('admin')->user()->hasPermission('packages.edit'))
                                                    <a class="dropdown-item" href="{{ route('dashboard.packages.edit', $package) }}">
                                                        <i class="bx bx-edit-alt me-2"></i> تعديل
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->hasPermission('packages.delete'))
                                                    <form action="{{ route('dashboard.packages.destroy', $package) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bx bx-trash me-2"></i> حذف
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                @if (auth('web')->check())
                                    <td>
                                        <a href="{{ route('subscribe.page', $package) }}" class="btn btn-sm btn-primary">
                                            <i class="bx bx-cart me-1"></i> {{ __('packages.subscribe') }}
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-body-secondary">
                                    {{ __('packages.no_packages') }}
                                    @if (auth('admin')->check() && auth('admin')->user()->hasPermission('packages.create'))
                                        <a href="{{ route('dashboard.packages.create') }}">{{ __('packages.create_link') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($packages->hasPages())
                <div class="card-footer">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
