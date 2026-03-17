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
        @if (session('error'))
            <div class="alert alert-warning alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (auth('admin')->check())
            {{-- عرض الأدمن: جدول --}}
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="60">{{ __('packages.icon') }}</th>
                                <th>{{ __('common.title') }}</th>
                                <th>{{ __('packages.monthly_price') }}</th>
                                <th>{{ __('packages.yearly_price') }}</th>
                                <th>{{ __('packages.max_pages') }}</th>
                                <th>{{ __('packages.features') }}</th>
                                @if (
                                    auth('admin')->user()->hasPermission('packages.edit') ||
                                    auth('admin')->user()->hasPermission('packages.delete')
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
                                    <td>{{ $package->max_pages ?? 1 }}</td>
                                    <td>
                                        @if ($package->features && count($package->features) > 0)
                                            <span class="badge bg-label-primary">{{ __('packages.feature_count', ['count' => count($package->features)]) }}</span>
                                        @else
                                            <span class="text-body-secondary">-</span>
                                        @endif
                                    </td>
                                    @if (auth('admin')->user()->hasPermission('packages.edit') || auth('admin')->user()->hasPermission('packages.delete'))
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-body-secondary">
                                        {{ __('packages.no_packages') }}
                                        @if (auth('admin')->user()->hasPermission('packages.create'))
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
        @else
            {{-- عرض المستخدم: بطاقات مع تبديل شهري/سنوي --}}
            <div class="text-center mb-6">
                <label class="switch switch-sm switch-primary me-0">
                    <span class="switch-label fs-6 text-body me-3">دفع شهري</span>
                    <input type="checkbox" class="switch-input price-duration-toggler" checked />
                    <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                    </span>
                    <span class="switch-label fs-6 text-body ms-3">دفع سنوي</span>
                </label>
            </div>
            @php
                $activePackageId = auth('web')->user()->active_subscription?->package_id;
            @endphp
            <div class="row g-4">
                @forelse ($packages as $index => $package)
                    @php
                        $isActivePackage = $activePackageId && $package->id === $activePackageId;
                    @endphp
                    <div class="col-xl-4 col-lg-6 d-flex">
                        <div class="card w-100 {{ $index === 1 && $packages->count() > 2 && !$isActivePackage ? 'border border-primary shadow-sm' : '' }} {{ $isActivePackage ? 'border border-success' : '' }} h-100 position-relative">
                            @if ($isActivePackage)
                                <span class="position-absolute top-0 end-0 m-2 badge bg-success z-1">
                                    <i class="bx bx-check-circle me-1"></i> الباقة الحالية
                                </span>
                            @endif
                            <div class="card-header">
                                <div class="text-center">
                                    @if ($package->hasMedia('icon'))
                                        <img src="{{ $package->getFirstMediaUrl('icon') }}" alt="{{ $package->title }}" class="mb-4 pb-2" style="max-height: 60px; object-fit: contain;" />
                                    @else
                                        <span class="avatar avatar-xl mb-4 mx-auto d-flex align-items-center justify-content-center bg-label-primary rounded">
                                            <i class="bx bx-package icon-32px text-primary"></i>
                                        </span>
                                    @endif
                                    <h4 class="mb-0">{{ $package->title }}</h4>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="price-monthly h2 text-primary fw-extrabold mb-0">${{ number_format($package->monthly_price, 0) }}</span>
                                        <span class="price-yearly h2 text-primary fw-extrabold mb-0 d-none">${{ number_format($package->yearly_price / 12, 0) }}</span>
                                        <sub class="h6 text-body-secondary mb-n1 ms-1">/mo</sub>
                                    </div>
                                    <div class="position-relative pt-2">
                                        <div class="price-yearly text-body-secondary price-yearly-toggle d-none">$ {{ number_format($package->yearly_price, 0) }} / year</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <ul class="list-unstyled pricing-list flex-grow-1 mb-0">
                                    @forelse ($package->features ?? [] as $feature)
                                        @if ($feature)
                                            <li>
                                                <h6 class="d-flex align-items-center mb-3">
                                                    <span class="badge badge-center rounded-pill {{ ($index === 1 && $packages->count() > 2) || $isActivePackage ? 'bg-primary' : 'bg-label-primary' }} p-0 me-3"><i class="icon-base bx bx-check icon-12px"></i></span>
                                                    {{ $feature }}
                                                </h6>
                                            </li>
                                        @endif
                                    @empty
                                        <li class="text-body-secondary">لا توجد مميزات مدرجة</li>
                                    @endforelse
                                </ul>
                                <div class="d-grid mt-auto pt-4">
                                    @if ($isActivePackage)
                                        <span class="btn btn-success disabled">
                                            <i class="bx bx-check me-1"></i> الباقة الحالية
                                        </span>
                                    @else
                                        <a href="{{ route('dashboard.packages.checkout', $package) }}" class="btn {{ $index === 1 && $packages->count() > 2 ? 'btn-primary' : 'btn-label-primary' }}">{{ __('packages.subscribe') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-8">
                        <p class="text-body-secondary mb-0">{{ __('packages.no_packages') }}</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
@endsection

@if (auth('web')->check())
@section('page-js')
    <script src="{{ asset('assets/js/pages-pricing.js') }}"></script>
@endsection
@endif
