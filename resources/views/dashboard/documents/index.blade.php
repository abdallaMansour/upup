@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard.partials.breadcrumb', [
            'items' => [
                ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
                ['label' => 'وثائقي وملفاتي'],
            ]
        ])
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
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                @if (str_contains(session('error') ?? '', 'SYNC_FAILED'))
                    <hr class="my-2">
                    <strong>الحل:</strong> اذهب إلى <a href="{{ route('dashboard.documents.storage-connections') }}" class="alert-link">ربط منصات التخزين</a> واضغط "إعادة الربط" بجانب Google Drive.
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info alert-dismissible" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- المنصة النشطة (واحدة فقط) --}}
        @if ($primaryConnection ?? null)
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">المنصة النشطة</h6>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge bg-label-primary py-2 px-3">
                            <i class="bx bx-cloud me-1"></i>
                            {{ $primaryConnection->display_name }}
                        </span>
                        @if ($primaryConnection->provider === 'google_drive')
                            <form action="{{ route('dashboard.documents.google-drive.sync') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-refresh me-1"></i> مزامنة
                                </button>
                            </form>
                        @elseif ($primaryConnection->provider === 'wasabi')
                            <form action="{{ route('dashboard.documents.wasabi.sync') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-refresh me-1"></i> مزامنة
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('dashboard.documents.storage-connections') }}" class="badge bg-label-secondary py-2 px-3 text-decoration-none">
                            <i class="bx bx-cog me-1"></i> إدارة
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h5 class="mb-0">الملفات والوثائق</h5>
                    {{-- Breadcrumb --}}
                    @if (isset($breadcrumb) && count($breadcrumb) > 1)
                        <nav aria-label="breadcrumb" class="mb-0">
                            <ol class="breadcrumb mb-0 py-0 ps-0">
                                @foreach ($breadcrumb as $item)
                                    @if ($loop->last)
                                        <li class="breadcrumb-item active">{{ $item['name'] }}</li>
                                    @else
                                        <li class="breadcrumb-item">
                                            <a href="{{ $item['id'] ? route('dashboard.documents.index', ['folder' => $item['id']]) : route('dashboard.documents.index') }}">
                                                {{ $item['name'] }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                    @endif
                </div>
                @if ($primaryConnection ?? null)
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                            <i class="bx bx-folder-plus me-1"></i> مجلد جديد
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
                            <i class="bx bx-upload me-1"></i> رفع ملف
                        </button>
                    </div>
                @else
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
                            <th>الاسم</th>
                            <th>المنصة</th>
                            <th>الحجم</th>
                            <th>التاريخ</th>
                            <th width="180">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $doc)
                            <tr>
                                <td>
                                    <span class="avatar-initial rounded {{ $doc->isFolder() ? 'bg-label-warning' : 'bg-label-secondary' }}">
                                        <i class="bx {{ $doc->file_icon }}"></i>
                                    </span>
                                </td>
                                <td>
                                    @if ($doc->isFolder())
                                        <a href="{{ route('dashboard.documents.index', ['folder' => $doc->id]) }}" class="text-body fw-medium">
                                            {{ $doc->name }}
                                        </a>
                                    @else
                                        <strong>{{ $doc->name }}</strong>
                                        @if ($doc->original_name && $doc->original_name !== $doc->name)
                                            <br><small class="text-body-secondary">{{ $doc->original_name }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($doc->storageConnection)
                                        <span class="badge bg-label-info">{{ $doc->storageConnection->display_name }}</span>
                                    @else
                                        <span class="badge bg-label-secondary">{{ \App\Models\StorageConnection::PROVIDERS[$doc->provider] ?? '-' }}</span>
                                    @endif
                                </td>
                                <td>{{ $doc->isFolder() ? '-' : $doc->formatted_size }}</td>
                                <td>{{ $doc->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if ($doc->view_url)
                                            <a href="{{ $doc->view_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-icon btn-outline-primary" title="{{ $doc->provider === 'wasabi' ? 'تحميل' : 'فتح في Google Drive' }}">
                                                <i class="bx bx-link-external"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-icon btn-outline-secondary move-btn" title="نقل"
                                            data-doc-id="{{ $doc->id }}"
                                            data-doc-name="{{ $doc->name }}">
                                            <i class="bx bx-move"></i>
                                        </button>
                                        <form action="{{ route('dashboard.documents.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="حذف">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-body-secondary">
                                    @if (!($primaryConnection ?? null))
                                        لا توجد ملفات بعد. قم بربط منصة تخزين سحابي (مثل Google Drive أو Wasabi) لعرض ملفاتك هنا.
                                        <br>
                                        <a href="{{ route('dashboard.documents.storage-connections') }}" class="btn btn-primary btn-sm mt-3">
                                            <i class="bx bx-link me-1"></i> ربط منصة تخزين
                                        </a>
                                    @else
                                        لا توجد ملفات أو مجلدات في هذا المجلد.
                                        <br>
                                        <button type="button" class="btn btn-primary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                                            <i class="bx bx-folder-plus me-1"></i> إنشاء مجلد
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
                                            <i class="bx bx-upload me-1"></i> رفع ملف
                                        </button>
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

    {{-- Modal: إنشاء مجلد --}}
    @if ($primaryConnection ?? null)
        <div class="modal fade" id="createFolderModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('dashboard.documents.folders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $folderId ?? '' }}">
                        <div class="modal-header">
                            <h5 class="modal-title">مجلد جديد</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">اسم المجلد</label>
                                <input type="text" name="name" class="form-control" required maxlength="255" placeholder="أدخل اسم المجلد">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">إنشاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal: رفع ملف --}}
        <div class="modal fade" id="uploadFileModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('dashboard.documents.files.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $folderId ?? '' }}">
                        <div class="modal-header">
                            <h5 class="modal-title">رفع ملف</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">الملف (حجم أقصى 50 ميجابايت)</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">رفع</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal: نقل --}}
        <div class="modal fade" id="moveModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="moveForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">نقل: <span id="moveDocName"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">المجلد الهدف</label>
                                <select name="new_parent_id" id="moveParentSelect" class="form-select">
                                    <option value="">الرئيسية (الجذر)</option>
                                    @foreach ($allFolders ?? [] as $f)
                                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">نقل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('page-js')
    @if (($primaryConnection ?? null) && isset($allFolders))
        <script>
            document.querySelectorAll('.move-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const docId = this.dataset.docId;
                    const docName = this.dataset.docName;
                    document.getElementById('moveDocName').textContent = docName;
                    document.getElementById('moveForm').action = '{{ url("dashboard/documents") }}/' + docId + '/move';
                    document.getElementById('moveParentSelect').value = '';
                    new bootstrap.Modal(document.getElementById('moveModal')).show();
                });
            });
        </script>
    @endif
@endsection
