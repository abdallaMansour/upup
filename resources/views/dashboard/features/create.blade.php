@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'المميزات', 'url' => route('dashboard.features.index')],
                ['label' => 'إضافة ميزة'],
            ]
        ])
        <h4 class="mb-4">إضافة ميزة</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.features.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="image" class="form-label">الصورة</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">الوصف <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">إضافة الميزة</button>
                </form>
            </div>
        </div>
    </div>
@endsection
