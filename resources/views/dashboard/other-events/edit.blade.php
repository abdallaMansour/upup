@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">تعديل الحدث</h4>
        <a href="{{ route('dashboard.other-events.index') }}" class="btn btn-label-secondary">
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
            <form action="{{ route('dashboard.other-events.update', $other_event) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="record_date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('record_date') is-invalid @enderror" id="record_date" name="record_date" value="{{ old('record_date', $other_event->record_date->format('Y-m-d')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="record_time" class="form-label">الوقت</label>
                        <input type="time" class="form-control @error('record_time') is-invalid @enderror" id="record_time" name="record_time" value="{{ old('record_time', $other_event->record_time_formatted) }}">
                        @error('record_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $other_event->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="other_info" class="form-label">معلومات أخرى</label>
                        <textarea class="form-control @error('other_info') is-invalid @enderror" id="other_info" name="other_info" rows="3">{{ old('other_info', $other_event->other_info) }}</textarea>
                        @error('other_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="media" class="form-label">الصورة / الفيديو</label>
                        @if ($other_event->mediaDocument)
                            <div class="mb-2">
                                <a href="{{ $other_event->mediaDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bx {{ $other_event->mediaDocument->file_icon }} me-1"></i> عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('media') is-invalid @enderror" id="media" name="media" accept="image/*,video/*">
                        <small class="text-muted">صيغ مدعومة: JPG, PNG, GIF, WebP, MP4, WebM, MOV. حد أقصى 50 ميجابايت (اترك فارغاً للاحتفاظ بالملف الحالي)</small>
                        @error('media')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-check">
                            <input type="checkbox" name="show_in_education" value="1" class="form-check-input" {{ old('show_in_education', $other_event->show_in_education) ? 'checked' : '' }}>
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
