{{-- بيانات أساسية --}}
<h6 class="text-muted mb-3">{{ __('life_stages.basic_data') }}</h6>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="name" class="form-label">{{ __('common.name') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $stage?->name) }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="mother_name" class="form-label">{{ __('life_stages.mother_name') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('mother_name') is-invalid @enderror" id="mother_name" name="mother_name" value="{{ old('mother_name', $stage?->mother_name) }}" required>
        @error('mother_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="father_name" class="form-label">{{ __('life_stages.father_name') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{ old('father_name', $stage?->father_name) }}" required>
        @error('father_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label for="naming_reason" class="form-label">{{ __('life_stages.naming_reason') }}</label>
        <textarea class="form-control @error('naming_reason') is-invalid @enderror" id="naming_reason" name="naming_reason" rows="2">{{ old('naming_reason', $stage?->naming_reason) }}</textarea>
        @error('naming_reason')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- الولادة --}}
<h6 class="text-muted mb-3">{{ __('life_stages.birth') }}</h6>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label for="birth_date" class="form-label">{{ __('life_stages.birth_date') }}</label>
        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', $stage?->birth_date?->format('Y-m-d')) }}">
        @error('birth_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="birth_time" class="form-label">{{ __('life_stages.birth_time') }}</label>
        <input type="time" class="form-control @error('birth_time') is-invalid @enderror" id="birth_time" name="birth_time" value="{{ old('birth_time', $stage?->birth_time_formatted) }}">
        @error('birth_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="gender" class="form-label">{{ __('life_stages.gender') }}</label>
        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
            <option value="">{{ __('common.select') }}</option>
            <option value="male" @selected(old('gender', $stage?->gender) === 'male')>{{ __('life_stages.gender_male') }}</option>
            <option value="female" @selected(old('gender', $stage?->gender) === 'female')>{{ __('life_stages.gender_female') }}</option>
        </select>
        @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="height" class="form-label">{{ __('life_stages.height') }}</label>
        <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('height', $stage?->height) }}" placeholder="{{ __('life_stages.example', ['value' => '52']) }}">
        @error('height')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="weight" class="form-label">{{ __('life_stages.weight') }}</label>
        <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $stage?->weight) }}" placeholder="{{ __('life_stages.example', ['value' => '3.2']) }}">
        @error('weight')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="blood_type" class="form-label">{{ __('life_stages.blood_type') }}</label>
        <input type="text" class="form-control @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type" value="{{ old('blood_type', $stage?->blood_type) }}" placeholder="{{ __('life_stages.example', ['value' => 'O+']) }}">
        @error('blood_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="doctor" class="form-label">{{ __('life_stages.doctor') }}</label>
        <input type="text" class="form-control @error('doctor') is-invalid @enderror" id="doctor" name="doctor" value="{{ old('doctor', $stage?->doctor) }}">
        @error('doctor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="birth_place" class="form-label">{{ __('life_stages.birth_place') }}</label>
        <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place" value="{{ old('birth_place', $stage?->birth_place) }}">
        @error('birth_place')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- الملفات والوسائط --}}
<h6 class="text-muted mb-3">{{ __('life_stages.media_files') }}</h6>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="cover_image" class="form-label">{{ __('life_stages.cover_image') }}</label>
        @if ($stage?->coverImageDocument)
            <div class="mb-2">
                <a href="{{ $stage->coverImageDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-image me-1"></i> {{ __('common.view_current_image') }}
                </a>
            </div>
        @endif
        <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*">
        <small class="text-muted">{{ __('common.max_10mb') }}</small>
        @error('cover_image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="footprint" class="form-label">{{ __('life_stages.footprint') }}</label>
        @if ($stage?->footprintDocument)
            <div class="mb-2">
                <a href="{{ $stage->footprintDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-image me-1"></i> {{ __('common.view_current_file') }}
                </a>
            </div>
        @endif
        <input type="file" class="form-control @error('footprint') is-invalid @enderror" id="footprint" name="footprint" accept="image/*">
        <small class="text-muted">{{ __('common.max_10mb') }}</small>
        @error('footprint')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="first_photo" class="form-label">{{ __('life_stages.first_photo') }}</label>
        @if ($stage?->firstPhotoDocument)
            <div class="mb-2">
                <a href="{{ $stage->firstPhotoDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-image me-1"></i> {{ __('common.view_current_image') }}
                </a>
            </div>
        @endif
        <input type="file" class="form-control @error('first_photo') is-invalid @enderror" id="first_photo" name="first_photo" accept="image/*">
        <small class="text-muted">{{ __('common.max_10mb') }}</small>
        @error('first_photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="first_video" class="form-label">{{ __('life_stages.first_video') }}</label>
        @if ($stage?->firstVideoDocument)
            <div class="mb-2">
                <a href="{{ $stage->firstVideoDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-video me-1"></i> {{ __('common.view_current_video') }}
                </a>
            </div>
        @endif
        <input type="file" class="form-control @error('first_video') is-invalid @enderror" id="first_video" name="first_video" accept="video/*">
        <small class="text-muted">{{ __('common.max_50mb') }}</small>
        @error('first_video')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="first_gift" class="form-label">{{ __('life_stages.first_gift') }}</label>
        @if ($stage?->firstGiftDocument)
            <div class="mb-2">
                <a href="{{ $stage->firstGiftDocument->view_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-gift me-1"></i> {{ __('common.view_current_file') }}
                </a>
            </div>
        @endif
        <input type="file" class="form-control @error('first_gift') is-invalid @enderror" id="first_gift" name="first_gift">
        <small class="text-muted">{{ __('common.max_50mb') }}</small>
        @error('first_gift')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- صور وفيديوهات إضافية --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="other_photos" class="form-label">{{ __('life_stages.other_photos') }}</label>
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
        <small class="text-muted">{{ __('life_stages.other_photos_hint') }}</small>
        @error('other_photos.*')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="other_videos" class="form-label">{{ __('life_stages.other_videos') }}</label>
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
        <small class="text-muted">{{ __('life_stages.other_videos_hint') }}</small>
        @error('other_videos.*')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>
