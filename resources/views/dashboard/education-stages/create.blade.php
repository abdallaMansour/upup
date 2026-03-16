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
                    <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="مثال: الابتدائي">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">إضافة المرحلة</button>
            </form>
        </div>
    </div>
</div>
@endsection
