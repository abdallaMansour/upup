@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'وثائقي وملفاتي', 'url' => route('dashboard.documents.index')],
                ['label' => 'ربط منصات التخزين', 'url' => route('dashboard.documents.storage-connections')],
                ['label' => 'تأكيد العودة لمنصة سابقة'],
            ]
        ])
        <div class="mb-4">
            <h4 class="mb-1">تأكيد العودة لمنصة سابقة</h4>
            <p class="text-body-secondary mb-0 small">سيتم مزامنة ملفاتك الحالية إلى المنصة المستهدفة</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <p class="mb-2">
                        <strong>المنصة الحالية (النشطة):</strong>
                        {{ \App\Models\StorageConnection::PROVIDERS[$primaryConnection->provider] ?? $primaryConnection->provider }}
                        <span class="text-body-secondary">— {{ $primaryConnection->name }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>المنصة المستهدفة:</strong>
                        {{ \App\Models\StorageConnection::PROVIDERS[$targetConnection->provider] ?? $targetConnection->provider }}
                        <span class="text-body-secondary">— {{ $targetConnection->name }}</span>
                    </p>
                </div>

                <p class="text-body-secondary mb-4">
                    سيتم مزامنة الملفات الحالية (من {{ \App\Models\StorageConnection::PROVIDERS[$primaryConnection->provider] ?? $primaryConnection->provider }}) إلى {{ \App\Models\StorageConnection::PROVIDERS[$targetConnection->provider] ?? $targetConnection->provider }}. الملفات القديمة على {{ \App\Models\StorageConnection::PROVIDERS[$targetConnection->provider] ?? $targetConnection->provider }} ستُستبدل.
                </p>

                <form action="{{ route('dashboard.documents.switch-storage.restore') }}" method="POST">
                    @csrf
                    <input type="hidden" name="connection_id" value="{{ $targetConnection->id }}">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-transfer me-1"></i> متابعة الانتقال
                        </button>
                        <a href="{{ route('dashboard.documents.storage-connections') }}" class="btn btn-outline-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
