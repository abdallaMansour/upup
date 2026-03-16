@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('faq.title')],
            ]
        ])
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">{{ __('faq.title') }}</h4>
            @if(auth('admin')->check() && auth('admin')->user()->hasPermission('faq.manage'))
                <a href="{{ route('dashboard.faq.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> {{ __('faq.add_question') }}
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>{{ __('faq.question') }}</th>
                            <th>{{ __('faq.answer') }}</th>
                            @if(auth('admin')->check() && auth('admin')->user()->hasPermission('faq.manage'))
                                <th width="120">{{ __('common.actions') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($faqs as $faq)
                            <tr>
                                <td>{{ $faq->id }}</td>
                                <td><strong>{{ Str::limit($faq->question, 60) }}</strong></td>
                                <td>{{ Str::limit(strip_tags($faq->answer), 80) }}</td>
                                @if(auth('admin')->check() && auth('admin')->user()->hasPermission('faq.manage'))
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('dashboard.faq.edit', $faq) }}">
                                                    <i class="bx bx-edit-alt me-2"></i> {{ __('common.edit') }}
                                                </a>
                                                <form action="{{ route('dashboard.faq.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm({{ json_encode(__('common.confirm')) }});">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bx bx-trash me-2"></i> {{ __('common.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth('admin')->check() ? '4' : '3' }}" class="text-center py-5 text-body-secondary">
                                    {{ __('faq.no_faq') }}
                                    @if(auth('admin')->check() && auth('admin')->user()->hasPermission('faq.manage'))
                                        <a href="{{ route('dashboard.faq.create') }}">{{ __('faq.add_link') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($faqs->hasPages())
                <div class="card-footer">
                    {{ $faqs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
