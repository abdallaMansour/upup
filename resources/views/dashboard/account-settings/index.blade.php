@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('dashboard.account_settings.title')],
            ],
        ])

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab', 'account') === 'account' ? 'active' : '' }}"
                                href="{{ route('dashboard.account-settings.index', ['tab' => 'account']) }}">
                                <i class="icon-base bx bx-user icon-sm me-1_5"></i>
                                {{ __('dashboard.account_settings.account_tab') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') === 'security' ? 'active' : '' }}"
                                href="{{ route('dashboard.account-settings.index', ['tab' => 'security']) }}">
                                <i class="icon-base bx bx-lock-alt icon-sm me-1_5"></i>
                                {{ __('dashboard.account_settings.security_tab') }}
                            </a>
                        </li>
                    </ul>
                </div>

                @if (request('tab', 'account') === 'account')
                    {{-- Account --}}
                    <div class="card mb-6">
                        <div class="card-body pt-4">
                            <form action="{{ route('dashboard.account-settings.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row g-6">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">{{ __('dashboard.account_settings.name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                            value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">{{ __('dashboard.account_settings.email') }} <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">{{ __('dashboard.account_settings.phone') }}</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                            value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="education_stage_id" class="form-label">{{ __('dashboard.modal.stage') }}</label>
                                        <select name="education_stage_id" id="education_stage_id" class="form-select @error('education_stage_id') is-invalid @enderror">
                                            <option value="">{{ __('dashboard.modal.select_stage') }}</option>
                                            @foreach ($educationStages ?? [] as $stage)
                                                <option value="{{ $stage->id }}" {{ old('education_stage_id', $user->education_stage_id) == $stage->id ? 'selected' : '' }}>
                                                    {{ $stage->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('education_stage_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="education_grade_id" class="form-label">{{ __('dashboard.modal.grade') }}</label>
                                        <select name="education_grade_id" id="education_grade_id" class="form-select @error('education_grade_id') is-invalid @enderror">
                                            <option value="">{{ __('dashboard.modal.select_grade') }}</option>
                                            @php
                                                $selectedStageId = old('education_stage_id', $user->education_stage_id);
                                                $selectedStage = ($educationStages ?? collect())->firstWhere('id', $selectedStageId);
                                            @endphp
                                            @if ($selectedStage)
                                                @foreach ($selectedStage->grades as $grade)
                                                    <option value="{{ $grade->id }}" {{ old('education_grade_id', $user->education_grade_id) == $grade->id ? 'selected' : '' }}>
                                                        {{ $grade->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('education_grade_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="school_name" class="form-label">{{ __('dashboard.modal.school_name') }}</label>
                                        <input type="text" name="school_name" id="school_name" class="form-control @error('school_name') is-invalid @enderror"
                                            value="{{ old('school_name', $user->school_name) }}" placeholder="{{ __('dashboard.modal.school_placeholder') }}" maxlength="255">
                                        @error('school_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <button type="submit" class="btn btn-primary me-3">{{ __('common.save') }}</button>
                                    <a href="{{ route('dashboard.index') }}" class="btn btn-label-secondary">{{ __('common.cancel') }}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- Security --}}
                    <div class="card mb-6">
                        <h5 class="card-header">{{ __('dashboard.account_settings.change_password') }}</h5>
                        <div class="card-body pt-1">
                            <form action="{{ route('dashboard.account-settings.password.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="mb-6 col-md-6 form-password-toggle form-control-validation">
                                        <label class="form-label" for="current_password">{{ __('dashboard.account_settings.current_password') }}</label>
                                        <div class="input-group input-group-merge">
                                            <input class="form-control @error('current_password') is-invalid @enderror" type="password" name="current_password"
                                                id="current_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                            <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-6 col-md-6 form-password-toggle form-control-validation">
                                        <label class="form-label" for="password">{{ __('dashboard.account_settings.new_password') }}</label>
                                        <div class="input-group input-group-merge">
                                            <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                            <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-6 col-md-6 form-password-toggle form-control-validation">
                                        <label class="form-label" for="password_confirmation">{{ __('dashboard.account_settings.confirm_password') }}</label>
                                        <div class="input-group input-group-merge">
                                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                            <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <button type="submit" class="btn btn-primary me-3">{{ __('common.save') }}</button>
                                    <button type="reset" class="btn btn-label-secondary">{{ __('common.cancel') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Education grade depends on stage
            const stageSelect = document.getElementById('education_stage_id');
            const gradeSelect = document.getElementById('education_grade_id');
            const gradesData = @json($educationStages->keyBy('id')->map(fn($s) => $s->grades->map(fn($g) => ['id' => $g->id, 'name' => $g->name])->values()->toArray())->toArray());

            if (stageSelect && gradeSelect) {
                stageSelect.addEventListener('change', function() {
                    const stageId = this.value;
                    gradeSelect.innerHTML = '<option value="">{{ __('dashboard.modal.select_grade') }}</option>';
                    if (stageId && gradesData[stageId]) {
                        gradesData[stageId].forEach(function(g) {
                            const opt = document.createElement('option');
                            opt.value = g.id;
                            opt.textContent = g.name;
                            gradeSelect.appendChild(opt);
                        });
                    }
                });
            }

            // Form password toggle (show/hide)
            document.querySelectorAll('.form-password-toggle .input-group-text').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const input = this.closest('.input-group').querySelector('input');
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('bx-hide', 'bx-show');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('bx-show', 'bx-hide');
                    }
                });
            });
        });
    </script>
@endsection
