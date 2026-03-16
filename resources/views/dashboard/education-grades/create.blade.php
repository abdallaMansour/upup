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
                            <option value="{{ $s->id }}" {{ old('education_stage_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('education_stage_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name" class="form-label">اسم الصف <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="مثال: الأول">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">إضافة الصف</button>
            </form>
        </div>
    </div>
</div>
@endsection
