@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">تعديل الإنجاز</h4>
        <a href="{{ route('dashboard.achievements.index') }}" class="btn btn-label-secondary">
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
            <form action="{{ route('dashboard.achievements.update', $achievement) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="record_date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('record_date') is-invalid @enderror" id="record_date" name="record_date" value="{{ old('record_date', $achievement->record_date->format('Y-m-d')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="record_time" class="form-label">الوقت</label>
                        <input type="time" class="form-control @error('record_time') is-invalid @enderror" id="record_time" name="record_time" value="{{ old('record_time', $achievement->record_time_formatted) }}">
                        @error('record_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="type" class="form-label">النوع <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            @foreach (\App\Models\UserAchievement::TYPES as $value => $label)
                                <option value="{{ $value }}" @selected(old('type', $achievement->type) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $achievement->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="place" class="form-label">المكان</label>
                        <input type="text" class="form-control @error('place') is-invalid @enderror" id="place" name="place" value="{{ old('place', $achievement->place) }}">
                        @error('place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="academic_year" class="form-label">السنة الدراسية</label>
                        <input type="text" class="form-control @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year" value="{{ old('academic_year', $achievement->academic_year) }}" placeholder="مثال: 2023-2024">
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="school" class="form-label">المدرسة (اختياري)</label>
                        <input type="text" class="form-control @error('school') is-invalid @enderror" id="school" name="school" value="{{ old('school', $achievement->school) }}">
                        @error('school')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="certificate_image" class="form-label">صورة الشهادة</label>
                        @if ($achievement->certificateImageDocument)
                            <div class="mb-2">
                                <a href="{{ $achievement->certificateImageDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-image me-1"></i> عرض الشهادة الحالية
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('certificate_image') is-invalid @enderror" id="certificate_image" name="certificate_image" accept="image/*">
                        <small class="text-muted">حد أقصى 10 ميجابايت (اترك فارغاً للاحتفاظ بالصورة الحالية)</small>
                        @error('certificate_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="photos" class="form-label">إضافة صور جديدة</label>
                        @if ($achievement->photos->isNotEmpty())
                            <div class="mb-2">
                                @foreach ($achievement->photos as $media)
                                    <a href="{{ $media->userDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-secondary me-1 mb-1">
                                        <i class="bx bx-image"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" class="form-control @error('photos.*') is-invalid @enderror" id="photos" name="photos[]" accept="image/*" multiple>
                        <small class="text-muted">يمكن إضافة صور جديدة دون حذف الحالية</small>
                        @error('photos.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="videos" class="form-label">إضافة فيديوهات جديدة</label>
                        @if ($achievement->videos->isNotEmpty())
                            <div class="mb-2">
                                @foreach ($achievement->videos as $media)
                                    <a href="{{ $media->userDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-secondary me-1 mb-1">
                                        <i class="bx bx-video"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" class="form-control @error('videos.*') is-invalid @enderror" id="videos" name="videos[]" accept="video/*" multiple>
                        <small class="text-muted">يمكن إضافة فيديوهات جديدة دون حذف الحالية</small>
                        @error('videos.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-check">
                            <input type="checkbox" name="show_in_education" value="1" class="form-check-input" {{ old('show_in_education', $achievement->show_in_education) ? 'checked' : '' }}>
                            <span class="form-check-label">عرض في المراحل التعليمية</span>
                        </label>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> تحديث
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
