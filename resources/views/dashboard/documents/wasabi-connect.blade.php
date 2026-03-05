@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="mb-1">ربط Wasabi</h4>
                <p class="text-body-secondary mb-0 small">أدخل بيانات الاتصال بحساب Wasabi. يمكنك إنشاء Access Key من <a href="https://console.wasabisys.com" target="_blank" rel="noopener">Wasabi Console</a></p>
            </div>
            <a href="{{ route('dashboard.documents.storage-connections') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> العودة
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.documents.wasabi.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="access_key" class="form-label">Access Key ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('access_key') is-invalid @enderror" id="access_key" name="access_key" value="{{ old('access_key') }}" placeholder="مثال: 1A2B3C4D5E6F7G8H9I0J" required autofocus>
                        @error('access_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="secret_key" class="form-label">Secret Access Key <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('secret_key') is-invalid @enderror" id="secret_key" name="secret_key" required>
                        @error('secret_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-body-secondary">يمكنك العثور عليها في Wasabi Console > Access Keys</small>
                    </div>
                    <div class="mb-3">
                        <label for="bucket" class="form-label">اسم الـ Bucket <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('bucket') is-invalid @enderror" id="bucket" name="bucket" value="{{ old('bucket') }}" placeholder="مثال: my-bucket" required>
                        @error('bucket')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="region" class="form-label">المنطقة (Region)</label>
                        <select class="form-select @error('region') is-invalid @enderror" id="region" name="region" title="يجب أن تطابق منطقة إنشاء الـ Bucket في Wasabi Console">
                            <option value="us-east-1" {{ old('region', 'us-east-1') === 'us-east-1' ? 'selected' : '' }}>US East 1 (N. Virginia)</option>
                            <option value="us-east-2" {{ old('region') === 'us-east-2' ? 'selected' : '' }}>US East 2 (N. Virginia)</option>
                            <option value="us-west-1" {{ old('region') === 'us-west-1' ? 'selected' : '' }}>US West 1 (Oregon)</option>
                            <option value="eu-central-1" {{ old('region') === 'eu-central-1' ? 'selected' : '' }}>EU Central 1 (Amsterdam)</option>
                            <option value="eu-central-2" {{ old('region') === 'eu-central-2' ? 'selected' : '' }}>EU Central 2 (Warsaw)</option>
                            <option value="ap-northeast-1" {{ old('region') === 'ap-northeast-1' ? 'selected' : '' }}>AP Northeast 1 (Tokyo)</option>
                            <option value="ap-northeast-2" {{ old('region') === 'ap-northeast-2' ? 'selected' : '' }}>AP Northeast 2 (Osaka)</option>
                        </select>
                        @error('region')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-body-secondary">يمكنك معرفة منطقة الـ Bucket من Wasabi Console عند عرض تفاصيل الـ Bucket</small>
                    </div>
                    <div class="mb-4">
                        <label for="prefix" class="form-label">المسار (اختياري)</label>
                        <input type="text" class="form-control @error('prefix') is-invalid @enderror" id="prefix" name="prefix" value="{{ old('prefix') }}" placeholder="مثال: documents/ أو فارغ للجذر">
                        @error('prefix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-body-secondary">لربط مجلد معين فقط داخل الـ bucket. اتركه فارغاً لاستخدام جميع الملفات.</small>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الاتصال (اختياري)</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="مثال: Wasabi الرئيسي">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-link me-1"></i> ربط Wasabi
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
