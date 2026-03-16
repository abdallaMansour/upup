@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => __('dashboard.breadcrumb.dashboard'), 'url' => route('dashboard.index')],
                ['label' => __('support_tickets.tickets')],
            ]
        ])
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h4 class="mb-0">{{ __('support_tickets.title') }}</h4>
            @if (auth('web')->check() || (auth('admin')->check() && auth('admin')->user()->hasPermission('support-tickets.manage')))
                <a href="{{ route('dashboard.support-tickets.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> {{ __('support_tickets.new_ticket') }}
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
                            @auth('admin')
                                <th>{{ __('support_tickets.user_label') }}</th>
                            @endauth
                            <th>الموضوع</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th width="100">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                @auth('admin')
                                    <td><strong>{{ $ticket->user->name }}</strong><br><small class="text-body-secondary">{{ $ticket->user->email }}</small></td>
                                @endauth
                                <td>{{ Str::limit($ticket->subject, 50) }}</td>
                                <td>
                                    @php $statusLabels = \App\Models\SupportTicket::statuses(); @endphp
                                    <span
                                        class="badge @switch($ticket->status)
                                        @case('open') bg-label-warning @break
                                        @case('in_progress') bg-label-info @break
                                        @case('resolved') bg-label-success @break
                                        @case('closed') bg-label-secondary @break
                                        @default bg-label-primary
                                    @endswitch">
                                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                    </span>
                                </td>
                                <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('dashboard.support-tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-show me-1"></i> {{ __('common.view') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth('admin') ? 6 : 5 }}" class="text-center py-5 text-body-secondary">
                                    {{ __('support_tickets.no_tickets') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($tickets->hasPages())
                <div class="card-footer">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
