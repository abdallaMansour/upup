@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'وثائقي وملفاتي', 'url' => route('dashboard.documents.index')],
                ['label' => 'ربط منصات التخزين', 'url' => route('dashboard.documents.storage-connections')],
                ['label' => 'تأكيد الانتقال بين المنصات'],
            ]
        ])
        <div class="mb-4">
            <h4 class="mb-1">تأكيد الانتقال بين المنصات</h4>
            <p class="text-body-secondary mb-0 small">سيتم نسخ ملفاتك من المنصة الحالية إلى المنصة المستهدفة</p>
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
                        <strong>المنصة المستهدفة:</strong> {{ $targetName }}
                    </p>
                </div>

                <p class="text-body-secondary mb-4">
                    سيتم نسخ جميع ملفاتك ومجلداتك من المنصة الحالية إلى {{ $targetName }}. بعد إتمام النسخ، ستُعيّن {{ $targetName }} كمنصة التخزين النشطة.
                </p>

                <form action="{{ route('dashboard.documents.switch-storage.proceed') }}" method="POST">
                    @csrf
                    <input type="hidden" name="to" value="{{ $to }}">

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="delete_source" id="delete_source" value="1" {{ old('delete_source') ? 'checked' : '' }}>
                            <label class="form-check-label" for="delete_source">
                                حذف الملفات من {{ \App\Models\StorageConnection::PROVIDERS[$primaryConnection->provider] ?? $primaryConnection->provider }} بعد النسخ
                            </label>
                        </div>
                        <small class="text-body-secondary d-block mt-1">
                            إذا اخترت هذا الخيار، سيتم حذف الملفات من المنصة الحالية بعد نسخها بنجاح. يمكنك الاحتفاظ بنسخة على المنصة الحالية بترك الخيار غير محدد.
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-transfer me-1"></i> متابعة الربط
                        </button>
                        <a href="{{ route('dashboard.documents.storage-connections') }}" class="btn btn-outline-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
