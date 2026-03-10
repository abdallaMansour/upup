{{-- بيانات أساسية --}}
<h6 class="text-muted mb-3">البيانات الأساسية</h6>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $stage?->name) }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="mother_name" class="form-label">اسم الأم بالكامل <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('mother_name') is-invalid @enderror" id="mother_name" name="mother_name" value="{{ old('mother_name', $stage?->mother_name) }}" required>
        @error('mother_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="father_name" class="form-label">اسم الأب بالكامل <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{ old('father_name', $stage?->father_name) }}" required>
        @error('father_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label for="naming_reason" class="form-label">سبب التسمية</label>
        <textarea class="form-control @error('naming_reason') is-invalid @enderror" id="naming_reason" name="naming_reason" rows="2">{{ old('naming_reason', $stage?->naming_reason) }}</textarea>
        @error('naming_reason')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- الولادة --}}
<h6 class="text-muted mb-3">الولادة</h6>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label for="birth_date" class="form-label">تاريخ الولادة</label>
        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', $stage?->birth_date?->format('Y-m-d')) }}">
        @error('birth_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="birth_time" class="form-label">وقت الولادة</label>
        <input type="time" class="form-control @error('birth_time') is-invalid @enderror" id="birth_time" name="birth_time" value="{{ old('birth_time', $stage?->birth_time_formatted) }}">
        @error('birth_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="gender" class="form-label">الجنس</label>
        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
            <option value="">-- اختر --</option>
            <option value="male" @selected(old('gender', $stage?->gender) === 'male')>ذكر</option>
            <option value="female" @selected(old('gender', $stage?->gender) === 'female')>أنثى</option>
        </select>
        @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="height" class="form-label">الطول (سم)</label>
        <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('height', $stage?->height) }}" placeholder="مثال: 52">
        @error('height')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="weight" class="form-label">الوزن (كجم)</label>
        <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $stage?->weight) }}" placeholder="مثال: 3.2">
        @error('weight')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="blood_type" class="form-label">فصيلة الدم</label>
        <input type="text" class="form-control @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type" value="{{ old('blood_type', $stage?->blood_type) }}" placeholder="مثال: O+">
        @error('blood_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="doctor" class="form-label">الطبيبة</label>
        <input type="text" class="form-control @error('doctor') is-invalid @enderror" id="doctor" name="doctor" value="{{ old('doctor', $stage?->doctor) }}">
        @error('doctor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="birth_place" class="form-label">مكان الولادة</label>
        <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place" value="{{ old('birth_place', $stage?->birth_place) }}">
        @error('birth_place')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- الملفات والوسائط --}}
<h6 class="text-muted mb-3">الملفات والوسائط</h6>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="cover_image" class="form-label">صورة الغلاف (للكارت)</label>
        @if ($stage?->coverImageDocument)
            <div class="mb-2">
                <a href="{{ $stage->coverImageDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-image me-1"></i> عرض الصورة الحالية
                </a>
            </div>
        @endif
        <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*">
        <small class="text-muted">حد أقصى 10 ميجابايت</small>
        @error('cover_image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="footprint" class="form-label">شاشة طبعة القدم (ملف أو صورة)</label>
        @if ($stage?->footprintDocument)
            <div class="mb-2">
                <a href="{{ $stage->footprintDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-image me-1"></i> عرض الملف الحالي
                </a>
            </div>
        @endif
        <input type="file" class="form-control @error('footprint') is-invalid @enderror" id="footprint" name="footprint" accept="image/*">
        <small class="text-muted">حد أقصى 10 ميجابايت</small>
        @error('footprint')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="first_photo" class="form-label">أول صورة</label>
        @if ($stage?->firstPhotoDocument)
            <div class="mb-2">
                <a href="{{ $stage->firstPhotoDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-image me-1"></i> عرض الصورة الحالية
                </a>
            </div>
        @endif
        <input type="file" class="form-control @error('first_photo') is-invalid @enderror" id="first_photo" name="first_photo" accept="image/*">
        <small class="text-muted">حد أقصى 10 ميجابايت</small>
        @error('first_photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="first_video" class="form-label">أول فيديو</label>
        @if ($stage?->firstVideoDocument)
            <div class="mb-2">
                <a href="{{ $stage->firstVideoDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-video me-1"></i> عرض الفيديو الحالي
                </a>
            </div>
        @endif
        <input type="file" class="form-control @error('first_video') is-invalid @enderror" id="first_video" name="first_video" accept="video/*">
        <small class="text-muted">حد أقصى 50 ميجابايت</small>
        @error('first_video')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="first_gift" class="form-label">أول هدية</label>
        @if ($stage?->firstGiftDocument)
            <div class="mb-2">
                <a href="{{ $stage->firstGiftDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-gift me-1"></i> عرض الملف الحالي
                </a>
            </div>
        @endif
        <input type="file" class="form-control @error('first_gift') is-invalid @enderror" id="first_gift" name="first_gift">
        <small class="text-muted">حد أقصى 50 ميجابايت</small>
        @error('first_gift')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- صور وفيديوهات إضافية --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="other_photos" class="form-label">صور أخرى</label>
        @if ($stage?->otherPhotos->isNotEmpty())
            <div class="mb-2">
                @foreach ($stage->otherPhotos as $media)
                    <a href="{{ $media->userDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-secondary me-1 mb-1">
                        <i class="bx bx-image"></i> {{ $media->userDocument->name }}
                    </a>
                @endforeach
            </div>
        @endif
        <input type="file" class="form-control @error('other_photos.*') is-invalid @enderror" id="other_photos" name="other_photos[]" accept="image/*" multiple>
        <small class="text-muted">يمكن اختيار عدة صور، حد أقصى 10 ميجابايت لكل صورة</small>
        @error('other_photos.*')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="other_videos" class="form-label">فيديوهات أخرى</label>
        @if ($stage?->otherVideos->isNotEmpty())
            <div class="mb-2">
                @foreach ($stage->otherVideos as $media)
                    <a href="{{ $media->userDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-secondary me-1 mb-1">
                        <i class="bx bx-video"></i> {{ $media->userDocument->name }}
                    </a>
                @endforeach
            </div>
        @endif
        <input type="file" class="form-control @error('other_videos.*') is-invalid @enderror" id="other_videos" name="other_videos[]" accept="video/*" multiple>
        <small class="text-muted">يمكن اختيار عدة فيديوهات، حد أقصى 50 ميجابايت لكل فيديو</small>
        @error('other_videos.*')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>
