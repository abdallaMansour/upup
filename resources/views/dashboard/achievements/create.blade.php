@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">إضافة إنجاز</h4>
        <a href="{{ ($stage ?? null) ? route('dashboard.my-pages.documents', $stage) : route('dashboard.achievements.index') }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> رجوع
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!($primaryConnection ?? null))
        <div class="alert alert-warning">
            <i class="bx bx-info-circle me-2"></i>
            يرجى <a href="{{ route('dashboard.documents.storage-connections') }}" class="alert-link">ربط منصة تخزين</a> لرفع الصور والفيديوهات.
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.achievements.store', ($stage ?? null) ? ['stage' => $stage->id] : []) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="record_date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('record_date') is-invalid @enderror" id="record_date" name="record_date" value="{{ old('record_date', date('Y-m-d')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="record_time" class="form-label">الوقت</label>
                        <input type="time" class="form-control @error('record_time') is-invalid @enderror" id="record_time" name="record_time" value="{{ old('record_time') }}">
                        @error('record_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="type" class="form-label">النوع <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            @foreach (\App\Models\UserAchievement::TYPES as $value => $label)
                                <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="place" class="form-label">المكان</label>
                        <input type="text" class="form-control @error('place') is-invalid @enderror" id="place" name="place" value="{{ old('place') }}">
                        @error('place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="academic_year" class="form-label">السنة الدراسية</label>
                        <input type="text" class="form-control @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year" value="{{ old('academic_year') }}" placeholder="مثال: 2023-2024">
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="school" class="form-label">المدرسة (اختياري)</label>
                        <input type="text" class="form-control @error('school') is-invalid @enderror" id="school" name="school" value="{{ old('school') }}">
                        @error('school')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="certificate_image" class="form-label">صورة الشهادة</label>
                        <input type="file" class="form-control @error('certificate_image') is-invalid @enderror" id="certificate_image" name="certificate_image" accept="image/*">
                        <small class="text-muted">حد أقصى 10 ميجابايت</small>
                        @error('certificate_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="photos" class="form-label">الصور</label>
                        <input type="file" class="form-control @error('photos.*') is-invalid @enderror" id="photos" name="photos[]" accept="image/*" multiple>
                        <small class="text-muted">حد أقصى 10 ميجابايت لكل صورة</small>
                        @error('photos.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="videos" class="form-label">الفيديوهات</label>
                        <input type="file" class="form-control @error('videos.*') is-invalid @enderror" id="videos" name="videos[]" accept="video/*" multiple>
                        <small class="text-muted">حد أقصى 50 ميجابايت لكل فيديو</small>
                        @error('videos.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-check">
                            <input type="checkbox" name="show_in_education" value="1" class="form-check-input" {{ old('show_in_education') ? 'checked' : '' }}>
                            <span class="form-check-label">عرض في المراحل التعليمية</span>
                        </label>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> حفظ
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
