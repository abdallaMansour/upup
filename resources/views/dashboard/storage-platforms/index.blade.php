@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">إدارة منصات التخزين</h4>
                <p class="text-body-secondary mb-0 small">تفعيل أو تعطيل منصات التخزين السحابي. المنصات المعطلة لا يمكن للمستخدمين الربط معها.</p>
            </div>
        </div>

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

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>المنصة</th>
                            <th>الاسم</th>
                            <th>الحالة</th>
                            <th>عدد المستخدمين المرتبطين</th>
                            <th width="150">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($platforms as $platform)
                            <tr>
                                <td>
                                    <span class="badge bg-label-primary">{{ $platform->provider }}</span>
                                </td>
                                <td>{{ $platform->name }}</td>
                                <td>
                                    @if ($platform->is_active)
                                        <span class="badge bg-success">مفعّل</span>
                                    @else
                                        <span class="badge bg-secondary">معطّل</span>
                                    @endif
                                </td>
                                <td>{{ $platform->storage_connections_count }}</td>
                                <td>
                                    @php
                                        $hasConnections = $platform->storage_connections_count > 0;
                                        $canDeactivate = !$hasConnections;
                                    @endphp
                                    @if ($platform->is_active)
                                        @if ($canDeactivate)
                                            <form action="{{ route('dashboard.storage-platforms.update', $platform) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد تعطيل هذه المنصة؟ لن يتمكن المستخدمون من الربط بها.');">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="is_active" value="0" />
                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                    <i class="bx bx-power-off me-1"></i> تعطيل
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-secondary" disabled title="لا يمكن التعطيل - يوجد مستخدمون مرتبطون">
                                                <i class="bx bx-power-off me-1"></i> تعطيل
                                            </button>
                                        @endif
                                    @else
                                        <form action="{{ route('dashboard.storage-platforms.update', $platform) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="is_active" value="1" />
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bx bx-check me-1"></i> تفعيل
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
