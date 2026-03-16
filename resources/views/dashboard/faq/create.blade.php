@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'الأسئلة الشائعة', 'url' => route('dashboard.faq.index')],
                ['label' => 'إضافة سؤال'],
            ]
        ])
        <h4 class="mb-4">إضافة سؤال شائع</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.faq.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="question" class="form-label">السؤال <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" name="question" value="{{ old('question') }}" required>
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="answer" class="form-label">الإجابة <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" name="answer" rows="5" required>{{ old('answer') }}</textarea>
                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">إضافة السؤال</button>
                </form>
            </div>
        </div>
    </div>
@endsection
