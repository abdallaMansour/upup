@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
            ['label' => 'المراحل التعليمية', 'url' => route('dashboard.education-stages.index')],
            ['label' => 'تعديل المرحلة'],
        ]
    ])
    <h4 class="mb-4">تعديل المرحلة التعليمية</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.education-stages.stages.update', $education_stage) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $education_stage->name) }}" required maxlength="255">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">تحديث المرحلة</button>
            </form>
        </div>
    </div>
</div>
@endsection
