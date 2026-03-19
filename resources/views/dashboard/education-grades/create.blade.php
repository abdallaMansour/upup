@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
            ['label' => 'المراحل التعليمية', 'url' => route('dashboard.education-stages.index')],
            ['label' => 'الصفوف', 'url' => route('dashboard.education-grades.index')],
            ['label' => 'إضافة صف'],
        ]
    ])
    <h4 class="mb-4">إضافة صف</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.education-grades.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="education_stage_id" class="form-label">المرحلة <span class="text-danger">*</span></label>
                    <select name="education_stage_id" id="education_stage_id" class="form-select @error('education_stage_id') is-invalid @enderror" required>
                        <option value="">اختر المرحلة</option>
                        @foreach ($stages as $s)
                            <option value="{{ $s->id }}" {{ old('education_stage_id') == $s->id ? 'selected' : '' }}>{{ $s->name_ar }}</option>
                        @endforeach
                    </select>
                    @error('education_stage_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name_ar" class="form-label">اسم الصف (عربي) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required maxlength="255" placeholder="مثال: الأول">
                    @error('name_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name_en" class="form-label">اسم الصف (إنجليزي)</label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en') }}" maxlength="255" placeholder="e.g. First">
                    @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">إضافة الصف</button>
            </form>
        </div>
    </div>
</div>
@endsection
