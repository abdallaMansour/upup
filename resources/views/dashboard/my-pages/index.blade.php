@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
            ['label' => __('dashboard.breadcrumb.my_pages')],
        ]
    ])
    <h4 class="mb-4">{{ __('my_pages.title') }}</h4>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
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

    <div class="row g-4">
        @foreach ($stages as $stage)
                @php
                    $coverUrl = $stage->coverImageDocument?->embed_url ?? $stage->coverImageDocument?->view_url ?? $stage->firstPhotoDocument?->embed_url ?? $stage->firstPhotoDocument?->view_url;
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        @if ($coverUrl)
                            <img src="{{ $coverUrl }}" class="card-img-top" alt="{{ $stage->name }}" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-label-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="bx bx-child bx-lg text-secondary"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $stage->name }}</h5>
                            <p class="card-text text-body-secondary small mb-3">
                                <i class="bx bx-calendar me-1"></i> {{ $stage->created_at->format('Y-m-d') }}
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('profile.show', $stage) }}" class="btn btn-sm btn-outline-primary" title="{{ __('common.view') }}" target="_blank">
                                    <i class="bx bx-show"></i> {{ __('common.view') }}
                                </a>
                                <a href="{{ route('dashboard.my-pages.edit', $stage) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bx bx-cog"></i> {{ __('my_pages.settings') }}
                                </a>
                                <a href="{{ route('dashboard.my-pages.documents', $stage) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bx bx-file-blank"></i> {{ __('my_pages.documents') }}
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-warning" title="{{ __('my_pages.temporary_permission') }}" data-bs-toggle="modal" data-bs-target="#permissionModal{{ $stage->id }}">
                                    <i class="bx bx-lock-alt"></i> {{ __('my_pages.temporary_permission') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-stage" data-stage-id="{{ $stage->id }}" data-stage-name="{{ $stage->name }}" data-delete-url="{{ route('dashboard.my-pages.destroy', $stage) }}">
                                    <i class="bx bx-trash"></i> {{ __('common.delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal صلاحيه مؤقته --}}
                <div class="modal fade" id="permissionModal{{ $stage->id }}" tabindex="-1" aria-labelledby="permissionModalLabel{{ $stage->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('dashboard.my-pages.permissions.store', $stage) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="permissionModalLabel{{ $stage->id }}">{{ __('my_pages.modal.title_prefix') }} {{ $stage->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
                                </div>
                                <div class="modal-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $err)
                                                    <li>{{ $err }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        <label for="grantee_name{{ $stage->id }}" class="form-label">{{ __('my_pages.grantee_name') }}</label>
                                        <input type="text" class="form-control" id="grantee_name{{ $stage->id }}" name="grantee_name" value="{{ old('grantee_name') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="grantee_email{{ $stage->id }}" class="form-label">{{ __('common.email') }}</label>
                                        <input type="email" class="form-control" id="grantee_email{{ $stage->id }}" name="grantee_email" value="{{ old('grantee_email') }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="expires_at{{ $stage->id }}" class="form-label">{{ __('my_pages.expires_at') }}</label>
                                            <input type="date" class="form-control" id="expires_at{{ $stage->id }}" name="expires_at" value="{{ old('expires_at') }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="expires_time{{ $stage->id }}" class="form-label">{{ __('my_pages.expires_time') }}</label>
                                            <input type="time" class="form-control" id="expires_time{{ $stage->id }}" name="expires_time" value="{{ old('expires_time', '23:59') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ __('common.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        @endforeach
        @include('dashboard.partials.add-card', [
            'url' => route('dashboard.my-pages.create'),
            'label' => __('my_pages.add_stage'),
            'icon' => 'bx-plus',
        ])
    </div>
</div>

@if ($stages->isNotEmpty())
<form id="delete-stage-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="name_confirmation" id="delete-name-confirmation">
</form>
@endif
@endsection

@section('page-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete-stage').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const stageName = this.dataset.stageName;
            const deleteUrl = this.dataset.deleteUrl;

            Swal.fire({
                title: '{{ __('my_pages.confirm_delete_title') }}',
                html: '{{ __('my_pages.confirm_delete_message') }}<br><strong>' + stageName + '</strong>',
                input: 'text',
                inputLabel: '{{ __('my_pages.confirm_delete_input_label') }}',
                inputPlaceholder: stageName,
                showCancelButton: true,
                confirmButtonText: '{{ __('common.delete') }}',
                cancelButtonText: '{{ __('common.cancel') }}',
                confirmButtonColor: '#d33',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false,
                preConfirm: (value) => {
                    if (value !== stageName) {
                        Swal.showValidationMessage('{{ __('my_pages.name_mismatch') }}');
                        return false;
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-stage-form');
                    form.action = deleteUrl;
                    form.querySelector('#delete-name-confirmation').value = result.value;
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
