@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => ($stage ?? null)
            ? [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'صفحاتي', 'url' => route('dashboard.my-pages.index')],
                ['label' => 'وثق - ' . $stage->name, 'url' => route('dashboard.my-pages.documents', $stage)],
                ['label' => 'الأصوات', 'url' => route('dashboard.voices.index', ['stage' => $stage->id])],
                ['label' => 'إضافة'],
            ]
            : [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'الأصوات', 'url' => route('dashboard.voices.index')],
                ['label' => 'إضافة'],
            ]
    ])
    <h4 class="mb-4">إضافة صوت</h4>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!($primaryConnection ?? null))
        <div class="alert alert-warning">
            <i class="bx bx-info-circle me-2"></i>
            يرجى <a href="{{ route('dashboard.documents.storage-connections') }}" class="alert-link">ربط منصة تخزين</a> لرفع الملفات الصوتية.
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.voices.store', ($stage ?? null) ? ['stage' => $stage->id] : []) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="record_date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('record_date') is-invalid @enderror" id="record_date" name="record_date" value="{{ old('record_date', date('Y-m-d')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="record_time" class="form-label">الوقت</label>
                        <input type="time" class="form-control @error('record_time') is-invalid @enderror" id="record_time" name="record_time" value="{{ old('record_time') }}">
                        @error('record_time')
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
                    <div class="col-12">
                        <label for="other_info" class="form-label">معلومات أخرى</label>
                        <textarea class="form-control @error('other_info') is-invalid @enderror" id="other_info" name="other_info" rows="3">{{ old('other_info') }}</textarea>
                        @error('other_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="audio" class="form-label">الصوت (ملف صوتي)</label>
                        <input type="file" class="form-control @error('audio') is-invalid @enderror" id="audio" name="audio" accept="audio/*">
                        <small class="text-muted">صيغ مدعومة: MP3, WAV, OGG, M4A. حد أقصى 50 ميجابايت</small>
                        @error('audio')
                            <div class="invalid-feedback">{{ $message }}</div>
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
