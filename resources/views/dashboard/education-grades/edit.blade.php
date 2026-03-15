@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">تعديل الصف</h4>
        <a href="{{ route('dashboard.education-grades.index') }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> رجوع
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.education-grades.update', $education_grade) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="education_stage_id" class="form-label">المرحلة <span class="text-danger">*</span></label>
                    <select name="education_stage_id" id="education_stage_id" class="form-select @error('education_stage_id') is-invalid @enderror" required>
                        <option value="">اختر المرحلة</option>
                        @foreach ($stages as $s)
                            <option value="{{ $s->id }}" {{ old('education_stage_id', $education_grade->education_stage_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('education_stage_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name" class="form-label">اسم الصف <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $education_grade->name) }}" required maxlength="255">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">تحديث الصف</button>
            </form>
        </div>
    </div>
</div>
@endsection
