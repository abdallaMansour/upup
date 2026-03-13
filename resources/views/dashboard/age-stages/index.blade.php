@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">المراحل العمرية</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.age-stages.update') }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    $childhoodMax = old('age_stage_childhood_max', $settings->age_stage_childhood_max ?? 11);
                    $teenagerMax = old('age_stage_teenager_max', $settings->age_stage_teenager_max ?? 17);
                    $adultMax = old('age_stage_adult_max', $settings->age_stage_adult_max ?? 120);
                @endphp
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="age_stage_childhood_max" class="form-label">أقصى عمر لمرحلة الطفولة</label>
                        <input type="number" class="form-control @error('age_stage_childhood_max') is-invalid @enderror" id="age_stage_childhood_max" name="age_stage_childhood_max" value="{{ $childhoodMax }}" min="0" max="120" required>
                        <div class="form-text">من 0 إلى {{ $childhoodMax }} سنة = طفولة</div>
                        @error('age_stage_childhood_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="age_stage_teenager_max" class="form-label">أقصى عمر لمرحلة المراهقة</label>
                        <input type="number" class="form-control @error('age_stage_teenager_max') is-invalid @enderror" id="age_stage_teenager_max" name="age_stage_teenager_max" value="{{ $teenagerMax }}" min="0" max="120" required>
                        <div class="form-text">من {{ $childhoodMax + 1 }} إلى {{ $teenagerMax }} سنة = مراهقة</div>
                        @error('age_stage_teenager_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="age_stage_adult_max" class="form-label">أقصى عمر لمرحلة البالغين</label>
                        <input type="number" class="form-control @error('age_stage_adult_max') is-invalid @enderror" id="age_stage_adult_max" name="age_stage_adult_max" value="{{ $adultMax }}" min="0" max="120" required>
                        <div class="form-text">من {{ $teenagerMax + 1 }} سنة فأكثر = بالغين</div>
                        @error('age_stage_adult_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
