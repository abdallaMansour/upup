{{-- بطاقة إضافة جديدة - تستخدم في صفحات القوائم --}}
@props([
    'url' => '#',
    'label' => __('dashboard.breadcrumb.add'),
    'icon' => 'bx-plus',
])
<div class="col-12 col-md-6 col-lg-4">
    <a href="{{ $url }}" class="card h-100 text-decoration-none border border-2 border-dashed shadow-sm add-card-link" style="transition: all 0.2s;">
        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center py-5">
            <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-label-primary" style="width: 64px; height: 64px;">
                <i class="bx {{ $icon }} bx-lg text-primary"></i>
            </span>
            <h6 class="card-title mb-0 text-body">{{ $label }}</h6>
        </div>
    </a>
</div>
