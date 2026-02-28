@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h4 class="mb-0">وثائقي وملفاتي</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.documents.storage-connections') }}" class="btn btn-outline-primary">
                    <i class="bx bx-link me-1"></i> ربط منصة تخزين
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- روابط سريعة للمنصات المتصلة --}}
        @if ($storageConnections->isNotEmpty())
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">منصات التخزين المتصلة</h6>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @foreach ($storageConnections as $conn)
                            <span class="badge bg-label-primary py-2 px-3">
                                <i class="bx bx-cloud me-1"></i>
                                {{ $conn->display_name }}
                            </span>
                            @if ($conn->provider === 'google_drive')
                                <form action="{{ route('dashboard.documents.google-drive.sync') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-refresh me-1"></i> مزامنة
                                    </button>
                                </form>
                            @endif
                        @endforeach
                        <a href="{{ route('dashboard.documents.storage-connections') }}" class="badge bg-label-secondary py-2 px-3 text-decoration-none">
                            <i class="bx bx-cog me-1"></i> إدارة
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">الملفات والوثائق</h5>
                @if ($storageConnections->isEmpty())
                    <a href="{{ route('dashboard.documents.storage-connections') }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-link me-1"></i> اربط منصة لعرض الملفات
                    </a>
                @endif
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>الملف</th>
                            <th>المنصة</th>
                            <th>الحجم</th>
                            <th>التاريخ</th>
                            <th width="80">عرض</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $doc)
                            <tr>
                                <td>
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx {{ $doc->file_icon }}"></i>
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $doc->name }}</strong>
                                    @if ($doc->original_name && $doc->original_name !== $doc->name)
                                        <br><small class="text-body-secondary">{{ $doc->original_name }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($doc->storageConnection)
                                        <span class="badge bg-label-info">{{ $doc->storageConnection->display_name }}</span>
                                    @else
                                        <span class="badge bg-label-secondary">{{ $doc->provider ?? '-' }}</span>
                                    @endif
                                </td>
                                <td>{{ $doc->formatted_size }}</td>
                                <td>{{ $doc->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if ($doc->view_url)
                                        <a href="{{ $doc->view_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary" title="فتح في Google Drive">
                                            <i class="bx bx-link-external"></i>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-body-secondary">
                                    @if ($storageConnections->isEmpty())
                                        لا توجد ملفات بعد. قم بربط منصة تخزين سحابي (مثل Google Drive أو Wasabi) لعرض ملفاتك هنا.
                                        <br>
                                        <a href="{{ route('dashboard.documents.storage-connections') }}" class="btn btn-primary btn-sm mt-3">
                                            <i class="bx bx-link me-1"></i> ربط منصة تخزين
                                        </a>
                                    @else
                                        لا توجد ملفات في المنصات المتصلة بعد.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($documents->hasPages())
                <div class="card-footer">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
