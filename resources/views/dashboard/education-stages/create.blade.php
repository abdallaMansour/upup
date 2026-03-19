@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
            ['label' => 'المراحل التعليمية', 'url' => route('dashboard.education-stages.index')],
            ['label' => 'إضافة مرحلة'],
        ]
    ])
    <h4 class="mb-4">إضافة مرحلة تعليمية</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.education-stages.stages.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name_ar" class="form-label">الاسم (عربي) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required maxlength="255" placeholder="مثال: الابتدائي">
                    @error('name_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name_en" class="form-label">الاسم (إنجليزي)</label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en') }}" maxlength="255" placeholder="e.g. Primary">
                    @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">إضافة المرحلة</button>
            </form>
        </div>
    </div>
</div>
@endsection
