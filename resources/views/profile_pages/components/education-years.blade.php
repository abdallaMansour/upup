@props(['educationYears' => [], 'stage'])

@if (empty($educationYears))
    <p class="text-muted text-center py-5 w-100">لا توجد بيانات مرتبطة بالمراحل التعليمية. يمكنك ربط الأقسام (الطول والوزن، الإنجازات، إلخ) من لوحة التحكم.</p>
@else
    <div class="edu-stage-panel active">
        @foreach ($educationYears as $year => $data)
            @php
                $hw = $data['height_weight'] ?? null;
                $events = $data['events'] ?? [];
                $yearTitle = "سنة {$year}";
                $ageStr = $stage->birth_date ? ($year - $stage->birth_date->format('Y')) . ' سنوات' : '';
                $heightStr = $hw ? $hw->height . ' سم' : '';
                $weightStr = $hw ? $hw->weight . ' كج' : '';
                $photos = [];
                if ($hw?->imageDocument) {
                    $photos[] = route('profile.document.embed', [$stage, $hw->imageDocument]);
                }
            @endphp
            <div class="edu-year-card scroll-reveal" data-edu-title="{{ $yearTitle }}" data-edu-date="{{ $year }}"
                data-edu-age="{{ $ageStr }}" data-edu-height="{{ $heightStr }}" data-edu-weight="{{ $weightStr }}"
                data-edu-photos='@json($photos)' data-edu-video="">
                <div class="edu-year-header" onclick="this.closest('.edu-year-card').classList.toggle('expanded')">
                    <div class="edu-year-right">
                        <span class="edu-timeline-dot"></span>
                        <div>
                            <h5 class="edu-year-title">{{ $yearTitle }}</h5>
                            <span class="edu-year-meta"><i class="fas fa-calendar"></i> {{ $year }}@if($ageStr) &nbsp; <i class="fas fa-child"></i> {{ $ageStr }}@endif</span>
                        </div>
                    </div>
                    <div class="edu-year-left">
                        <i class="fas fa-chevron-down edu-expand-icon"></i>
                    </div>
                </div>
                <div class="edu-year-body">
                    @if ($hw)
                        <div class="edu-year-stats">
                            <span class="edu-stat"><i class="fas fa-ruler-vertical"></i> الطول: <strong>{{ $heightStr ?: '-' }}</strong></span>
                            <span class="edu-stat"><i class="fas fa-weight"></i> الوزن: <strong>{{ $weightStr ?: '-' }}</strong></span>
                        </div>
                    @endif
                    @if (!empty($events))
                        <div class="edu-year-events">
                            @foreach ($events as $ev)
                                @php
                                    $item = $ev['item'];
                                    $label = $ev['label'];
                                    $title = $item->title ?? '';
                                    $desc = $item->other_info ?? $item->place ?? '';
                                    $dateStr = $item->record_date?->format('Y-m-d') ?? '';
                                    $photos = [];
                                    if ($item instanceof \App\Models\UserAchievement) {
                                        if ($item->certificateImageDocument) {
                                            $photos[] = route('profile.document.embed', [$stage, $item->certificateImageDocument]);
                                        }
                                        foreach ($item->mediaItems ?? [] as $m) {
                                            if ($m->userDocument) {
                                                $photos[] = route('profile.document.embed', [$stage, $m->userDocument]);
                                            }
                                        }
                                    } elseif (isset($item->mediaDocument) && $item->mediaDocument) {
                                        $photos[] = route('profile.document.embed', [$stage, $item->mediaDocument]);
                                    }
                                    $badgeColors = [
                                        'تكريم' => 'background:#fff3e0;color:#e65100;',
                                        'نجاح' => 'background:#e8f5e9;color:#1b5e20;',
                                        'زيارة' => 'background:#e3f2fd;color:#0d47a1;',
                                        'حدث' => 'background:#f3e5f5;color:#4a148c;',
                                        'رسم' => 'background:#ffebee;color:#b71c1c;',
                                        'صوت' => 'background:#eceff1;color:#263238;',
                                        'إصابة' => 'background:#fff8e1;color:#f57f17;',
                                    ];
                                    $style = $badgeColors[$label] ?? 'background:#e0e0e0;color:#424242;';
                                @endphp
                                <div class="edu-event-item" data-event-type="{{ $label }}" data-event-title="{{ $title }}"
                                    data-event-details="{{ $desc }}" data-event-date="{{ $dateStr }}"
                                    data-event-photos='@json($photos)'>
                                    <span class="edu-event-dot" style="background:#ff9800;"></span>
                                    <div class="edu-event-content">
                                        <div class="edu-event-top">
                                            <strong>{{ $title ?: $label }}</strong>
                                            <span class="edu-event-badge-mini" style="{{ $style }}">{{ $label }}</span>
                                        </div>
                                        @if ($desc)
                                            <p>{{ Str::limit($desc, 80) }}</p>
                                        @endif
                                        <span class="edu-event-date-small"><i class="fas fa-calendar-alt"></i> {{ $dateStr }}</span>
                                    </div>
                                    <div class="post-media-btns">
                                        <button class="media-btn media-btn-blue edu-btn-details" title="التفاصيل"><i class="fas fa-th"></i></button>
                                        @if (!empty($photos))
                                            <button class="media-btn media-btn-green edu-btn-photos" title="الصور"><i class="fas fa-image"></i></button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
