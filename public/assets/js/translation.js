/* ===========================
   TRANSLATION DATA (AR / EN)
   =========================== */
const translations = {
    ar: {
        // Page
        pageTitle: 'صفحة الطفولة',

        // Top Navbar
        navChildhood: 'المطولة',
        navEditProfile: 'تعديل الملف',
        navAttendance: 'المواظبة',
        navSeller: 'البائع',
        navExpenses: 'النفقة',
        colorLabel: 'مظاهر الأطفال',
        navInfoLabel: 'أطفل نقال - المختبف وتغنيمة 🎨',

        // Cover
        btnEditCover: 'تحديث صورة البروفايل',
        profileName: 'محمد عبدالله',
        badgeChildhood: 'صدقة الطفولة',
        profileBio: 'طفلي عزاز يحب الاستكشاف والتعليم 🎓',

        // Stats
        statPosts: 'المنشورات',
        statPoints: 'النقاط',
        statCategories: 'الفئات',
        statStages: 'مراحل',

        // Tabs
        tabHome: 'الرئيسية',
        tabBirth: 'الولادة',
        tabHeight: 'الطول',
        tabCertificates: 'الإنجازات',
        tabEducation: 'التعليم',
        tabPhotos: 'الصور',
        tabInfo: 'الأحداث',

        // Post 1 – Graduation
        badgeSuccess: 'ناجح',
        post1Title: 'حفل التخرج من الجامعة',
        post1Desc: 'حصل محمد على شهادة البكالوريوس في هندسة الحاسب مع مرتبة الشرف الأولى.',
        post1Date: '10 يونيو 2022',
        post1Time: '10:00 صباحاً',
        post1Location: 'الرياض',

        // Post 2 – Award
        badgeWinner: 'فائز',
        post2Title: 'جائزة التميز الأكاديمي',
        post2Desc: 'فاز محمد بجائزة التميز الأكاديمي من وزارة التعليم.',
        post2Date: '5 مايو 2021',
        post2Time: '2:00 مساءً',

        // Actions
        btnShare: 'مشاركة',
        btnComment: 'تعليق',
        btnLike: 'اعجبني',

        // Sidebar – Quick Info
        sidebarQuickInfo: 'نبذة سريعة',
        labelBirthDate: 'تاريخ الميلاد',
        valBirthPlace: 'مستشفى الملك فهد الرياض',
        labelBirthPlace: 'مكان الولادة',
        valWeight: '3.2 كج',
        labelWeight: 'الوزن عند الولادة',
        valHeight: '174 سم',
        labelCurrentHeight: 'الطول الحالي',
        valEducation: 'جامعة الملك سعود',
        labelEducation: 'التعليم',

        // Sidebar – Photos
        sidebarPhotos: 'الصور',
        viewAll: 'عرض الكل',

        // Empty tabs
        emptyBirth: 'لا توجد بيانات ولادة',
        emptyHeight: 'لا توجد بيانات طول',
        emptyCerts: 'لا توجد شهادات',
        emptyEdu: 'لا توجد بيانات تعليم',
        emptyPhotos: 'لا توجد صور',
        emptyInfo: 'لا توجد معلومات',

        // Profile Edit Modal
        modalTitle: 'تعديل الملف الشخصي',
        labelName: 'الاسم',
        labelTags: 'الألقاب (الشارات)',
        btnAddTag: 'إضافة',
        labelBio: 'النبـذة التعريفية',
        labelProfileImage: 'صورة الملف الشخصي',
        hintProfileImage: 'اترك فارغاً لاستخدام الأيقونة الافتراضية',
        labelCoverImage: 'صورة البروفايل',
        hintCoverImage: 'اترك فارغاً لاستخدام التدرج الافتراضي',
        dragDropText: 'اختر صورة من جهازك',
        btnChooseFile: 'اختر ملف',
        noFileChosen: 'لم يتم اختيار ملف',
        btnSave: 'حفظ التغييرات',
        btnCancel: 'إلغاء',

        // Birth Section
        birthTitle: 'بيانات الولادة',
        btnEditBirth: 'تعديل',
        footprintTitle: 'بصمة القدم',
        birthInfoTitle: 'معلومات الولادة',
        birthName: 'اسم المولود',
        birthDateLabel: 'تاريخ الولادة',
        birthTimeLabel: 'وقت الولادة',
        birthHeightLabel: 'الطول عند الولادة',
        birthWeightLabel: 'الوزن عند الولادة',
        birthPlaceLabel: 'مكان الولادة',
        birthPhotosTitle: 'صور الولادة',
        noFootprint: 'لم يتم إضافة بصمة بعد',
        noBirthPhotos: 'لم يتم إضافة صور بعد',
        addBirthPhotos: 'أضف صور الولادة (يمكن أكثر من صورة)',
        birthModalTitle: 'تعديل بيانات الولادة',

        // Height / Measurements Section
        heightTitle: 'سجل القياسات',
        heightSubtitle: 'تتبع نمو طفلك بمرور الوقت',
        btnAddMeasurement: 'إضافة قياس',
        latestHeight: 'آخر طول',
        latestWidth: 'آخر عرض',
        totalMeasurements: 'عدد القياسات',
        noMeasurements: 'لم يتم إضافة قياسات بعد',
        btnStartMeasuring: 'ابدأ التسجيل',
        heightModalTitle: 'إضافة قياس جديد',
        measureHeight: 'الطول (سم)',
        measureWidth: 'العرض (سم)',
        measureStage: 'العمر أو المرحلة',
        measureStagePlaceholder: 'مثل: 6 أشهر، سنة، مرحلة الحبو...',
        measureDate: 'التاريخ',
        measureTime: 'الوقت',
        measureImage: 'صورة (اختياري)',
        btnSaveMeasurement: 'حفظ القياس',

        // Certificates Section
        certsTitle: 'الشهادات والإنجازات',
        certsSubtitle: 'وثّق إنجازات وشهادات طفلك',
        btnAddCert: 'إضافة شهادة',
        noCertificates: 'لم يتم إضافة شهادات بعد',
        btnStartCerts: 'أضف أول شهادة',
        certModalTitle: 'إضافة شهادة جديدة',
        certTitleLabel: 'عنوان الشهادة',
        certTitlePlaceholder: 'مثل: شهادة تفوق، شهادة حفظ...',
        certDetailsLabel: 'التفاصيل',
        certDetailsPlaceholder: 'أضف تفاصيل الشهادة أو الإنجاز...',
        certDateLabel: 'التاريخ',
        certTimeLabel: 'الوقت',
        certThumbnailLabel: 'صورة الشهادة الرئيسية',
        certPhotosLabel: 'صور إضافية',
        certAddPhotos: 'أضف صور إضافية (يمكن أكثر من صورة)',
        btnSaveCert: 'حفظ الشهادة',

        // Education Section
        eduTitle: 'السجل التعليمي',
        eduSubtitle: 'تتبع المراحل التعليمية والأحداث المدرسية',
        btnAddEdu: 'إضافة حدث',
        stageEarlyChildhood: 'طفولة مبكرة',
        stageKindergarten: 'رياض أطفال',
        stagePrimary: 'ابتدائية',
        stageMiddle: 'المتوسطة',
        stageSecondary: 'الثانوية',
        stageUniversity: 'الجامعة',
        eduStageLabel: 'المرحلة التعليمية',
        eduEventTypeLabel: 'نوع الحدث',
        eduTitleLabel: 'العنوان',
        eduDetailsLabel: 'التفاصيل',
        eduDateLabel: 'التاريخ',
        eduTimeLabel: 'الوقت',
        eduMainPhotoLabel: 'الصورة الرئيسية',
        eduExtraPhotosLabel: 'صور إضافية',
        btnSaveEdu: 'حفظ الحدث',
        eventTypeGraduation: 'تخرج',
        eventTypeAward: 'تكريم',
        eventTypeCompetition: 'مسابقة',
        eventTypeExam: 'اختبار',
        eventTypeTrip: 'رحلة',
        eventTypeOther: 'أخرى',

        // Gallery / Photos Section
        galleryTitle: 'معرض الصور',
        gallerySubtitle: 'اسحب الصور لإعادة ترتيبها',
        btnAddPhotos: 'إضافة صور',
        galleryCount: '12 صورة',

        // Info Section
        infoTitle: 'المعلومات الشخصية',
        infoSubtitle: 'تفاصيل وأحداث مهمة في حياة الطفل',
        btnAddInfo: 'أضف معلومة',
        infoModalTitle: 'إضافة معلومة جديدة',
        infoEventTypeLabel: 'نوع الحدث',
        eventTypeMilestone: 'إنجاز شخصي',
        eventTypeMedical: 'طبي',
        eventTypeSocial: 'اجتماعي',
        eventTypeFamily: 'عائلي',
        infoAgeStageLabel: 'المرحلة العمرية',
        infoAgeStagePlaceholder: 'مثل: الطفولة المبكرة، سنة واحدة، 3 سنوات...',
        infoTitleLabel: 'العنوان',
        infoTitlePlaceholder: 'مثل: أول خطوات، زيارة الطبيب، عيد الميلاد...',
        infoDetailsLabel: 'التفاصيل',
        infoDetailsPlaceholder: 'أضف تفاصيل هذا الحدث المهم...',
        infoDateLabel: 'التاريخ',
        infoTimeLabel: 'الوقت',
        infoMainPhotoLabel: 'الصورة الرئيسية',
        infoExtraPhotosLabel: 'الصور الإضافية',
        infoAddPhotos: 'أضف صور إضافية (يمكن أكثر من صورة)',
        btnSaveInfo: 'حفظ المعلومة',
        noInformation: 'لم يتم إضافة معلومات بعد',
        btnStartInfo: 'أضف أول معلومة',

        // Cover bio (all stages)
        coverBioMyName: 'انا اسمي',
        coverBioAndAge: 'و عمري',
        unitYear: 'سنة',
        unitYears: 'سنوات',
        yearPrefix: 'سنة ',
        badgeAdulthood: 'مرحلة البلوغ',
        badgeTeenager: 'مرحلة المراهقة',
        badgeChild: 'مرحلة الطفولة',

        // Home events
        homeEventsTitle: 'آخر الأحداث',
        viewAllEvents: 'عرض الكل',

        // Buttons
        btnDetails: 'التفاصيل',
        btnPhotos: 'الصور',

        // Birth section labels
        labelBloodType: 'فصيلة الدم',
        labelFatherName: 'اسم الأب',
        labelMotherName: 'اسم الأم',
        labelDoctor: 'الطبيب',
        altFootprint: 'بصمة القدم',

        // Height / Adults
        heightSubtitleAdults: 'تتبع النمو بمرور الوقت',
        certsSubtitleAdults: 'وثّق إنجازاتك وشهاداتك',
        recordedAt: 'تم التسجيل:',
        timeAm: ' صباحاً',
        timePm: ' مساءً',
        unitCm: 'سم',
        unitKg: 'كج',

        // Education section
        eduSectionTitle: 'المراحل التعليمية',
        eduSectionSub: 'بطاقة كل سنة دراسية',

        // Events section
        eventsTitle: 'الأحداث',
        eventsSubtitle: 'جميع الأحداث والمناسبات المهمة',
        filterAll: 'الكل',
        noEvents: 'لا توجد أحداث بعد.',
        noEventsYet: 'لا توجد أحداث بعد',
        noEventsInPeriod: 'لا توجد أحداث في هذه الفترة',

        // Life Book
        lifeBookTitle: 'كتاب الحياة',
        viewLifeBook: 'عرض كتاب الحياة',

        // Education years component
        emptyEduData: 'لا توجد بيانات مرتبطة بالمراحل التعليمية. يمكنك ربط الأقسام (الطول والوزن، الإنجازات، إلخ) من لوحة التحكم.',
        labelHeight: 'الطول:',
        labelWeight: 'الوزن:',
        badgeHonor: 'تكريم',
        badgeAchievementSuccess: 'نجاح',
        badgeVisit: 'زيارة',
        badgeEvent: 'حدث',
        badgeDrawing: 'رسم',
        badgeVoice: 'صوت',
        badgeInjury: 'إصابة',

        // Stats (adults/child/teen)
        statVisits: 'الزيارات والأحداث',
        statAchievements: 'الإنجازات',
        statMeasurements: 'سجل القياسات',
        statAge: 'العمر (سنة)',

        // JS / Modal strings
        modalCoverImage: 'صورة البروفايل',
        modalProfileImage: 'الصورة الشخصية',
        modalMeasurementsTitle: 'سجل القياسات',
        modalHeightLabel: 'الطول: ',
        modalWeightLabel: 'الوزن: ',
        modalMeasureImage: 'صورة القياس',
        modalBirthPhotos: 'صور الولادة',
        modalStageLabel: 'المرحلة:',
        modalEventTypeLabel: 'نوع الحدث:',
        modalGradeLabel: 'التقدير:',
        sourceBirth: 'الولادة'
    },

    en: {
        // Page
        pageTitle: 'Childhood Page',

        // Top Navbar
        navChildhood: 'Extended',
        navEditProfile: 'Edit Profile',
        navAttendance: 'Attendance',
        navSeller: 'Seller',
        navExpenses: 'Expenses',
        colorLabel: 'Kids Themes',
        navInfoLabel: 'Child Portal - Discovery & Learning 🎨',

        // Cover
        btnEditCover: 'Update Cover Photo',
        profileName: 'Mohammed Abdullah',
        badgeChildhood: 'Childhood Charity',
        profileBio: 'My child loves exploring and learning 🎓',

        // Stats
        statPosts: 'Posts',
        statPoints: 'Points',
        statCategories: 'Categories',
        statStages: 'Stages',

        // Tabs
        tabHome: 'Home',
        tabBirth: 'Birth',
        tabHeight: 'Height',
        tabCertificates: 'Achievements',
        tabEducation: 'Education',
        tabPhotos: 'Photos',
        tabInfo: 'Events',

        // Post 1 – Graduation
        badgeSuccess: 'Passed',
        post1Title: 'University Graduation Ceremony',
        post1Desc: 'Mohammed received a Bachelor\'s degree in Computer Engineering with First Class Honors.',
        post1Date: 'June 10, 2022',
        post1Time: '10:00 AM',
        post1Location: 'Riyadh',

        // Post 2 – Award
        badgeWinner: 'Winner',
        post2Title: 'Academic Excellence Award',
        post2Desc: 'Mohammed won the Academic Excellence Award from the Ministry of Education.',
        post2Date: 'May 5, 2021',
        post2Time: '2:00 PM',

        // Actions
        btnShare: 'Share',
        btnComment: 'Comment',
        btnLike: 'Like',

        // Sidebar – Quick Info
        sidebarQuickInfo: 'Quick Info',
        labelBirthDate: 'Date of Birth',
        valBirthPlace: 'King Fahd Hospital, Riyadh',
        labelBirthPlace: 'Place of Birth',
        valWeight: '3.2 kg',
        labelWeight: 'Weight at Birth',
        valHeight: '174 cm',
        labelCurrentHeight: 'Current Height',
        valEducation: 'King Saud University',
        labelEducation: 'Education',

        // Sidebar – Photos
        sidebarPhotos: 'Photos',
        viewAll: 'View All',

        // Empty tabs
        emptyBirth: 'No birth data available',
        emptyHeight: 'No height data available',
        emptyCerts: 'No certificates available',
        emptyEdu: 'No education data available',
        emptyPhotos: 'No photos available',
        emptyInfo: 'No information available',

        // Profile Edit Modal
        modalTitle: 'Edit Profile',
        labelName: 'Name',
        labelTags: 'Nicknames (Badges)',
        btnAddTag: 'Add',
        labelBio: 'Bio',
        labelProfileImage: 'Profile Image',
        hintProfileImage: 'Leave empty to use default icon',
        labelCoverImage: 'Cover Image',
        hintCoverImage: 'Leave empty to use default gradient',
        dragDropText: 'Choose image from your device',
        btnChooseFile: 'Choose File',
        noFileChosen: 'No file chosen',
        btnSave: 'Save Changes',
        btnCancel: 'Cancel',

        // Birth Section
        birthTitle: 'Birth Data',
        btnEditBirth: 'Edit',
        footprintTitle: 'Footprint',
        birthInfoTitle: 'Birth Information',
        birthName: 'Newborn Name',
        birthDateLabel: 'Date of Birth',
        birthTimeLabel: 'Time of Birth',
        birthHeightLabel: 'Height at Birth',
        birthWeightLabel: 'Weight at Birth',
        birthPlaceLabel: 'Place of Birth',
        birthPhotosTitle: 'Birth Photos',
        noFootprint: 'No footprint added yet',
        noBirthPhotos: 'No photos added yet',
        addBirthPhotos: 'Add birth photos (multiple allowed)',
        birthModalTitle: 'Edit Birth Data',

        // Height / Measurements Section
        heightTitle: 'Growth Records',
        heightSubtitle: 'Track your child\'s growth over time',
        btnAddMeasurement: 'Add Measurement',
        latestHeight: 'Latest Height',
        latestWidth: 'Latest Width',
        totalMeasurements: 'Total Records',
        noMeasurements: 'No measurements added yet',
        btnStartMeasuring: 'Start Recording',
        heightModalTitle: 'Add New Measurement',
        measureHeight: 'Height (cm)',
        measureWidth: 'Width (cm)',
        measureStage: 'Age or Stage',
        measureStagePlaceholder: 'e.g. 6 months, 1 year, crawling stage...',
        measureDate: 'Date',
        measureTime: 'Time',
        measureImage: 'Image (optional)',
        btnSaveMeasurement: 'Save Measurement',

        // Certificates Section
        certsTitle: 'Certificates & Achievements',
        certsSubtitle: 'Document your child\'s certificates and achievements',
        btnAddCert: 'Add Certificate',
        noCertificates: 'No certificates added yet',
        btnStartCerts: 'Add First Certificate',
        certModalTitle: 'Add New Certificate',
        certTitleLabel: 'Certificate Title',
        certTitlePlaceholder: 'e.g. Excellence Certificate, Memorization Certificate...',
        certDetailsLabel: 'Details',
        certDetailsPlaceholder: 'Add certificate or achievement details...',
        certDateLabel: 'Date',
        certTimeLabel: 'Time',
        certThumbnailLabel: 'Main Certificate Image',
        certPhotosLabel: 'Additional Photos',
        certAddPhotos: 'Add additional photos (multiple allowed)',
        btnSaveCert: 'Save Certificate',

        // Education Section
        eduTitle: 'Education Records',
        eduSubtitle: 'Track educational stages and school events',
        btnAddEdu: 'Add Event',
        stageEarlyChildhood: 'Early Childhood',
        stageKindergarten: 'Kindergarten',
        stagePrimary: 'Primary',
        stageMiddle: 'Middle School',
        stageSecondary: 'High School',
        stageUniversity: 'University',
        eduStageLabel: 'Educational Stage',
        eduEventTypeLabel: 'Event Type',
        eduTitleLabel: 'Title',
        eduDetailsLabel: 'Details',
        eduDateLabel: 'Date',
        eduTimeLabel: 'Time',
        eduMainPhotoLabel: 'Main Photo',
        eduExtraPhotosLabel: 'Additional Photos',
        btnSaveEdu: 'Save Event',
        eventTypeGraduation: 'Graduation',
        eventTypeAward: 'Award',
        eventTypeCompetition: 'Competition',
        eventTypeExam: 'Exam',
        eventTypeTrip: 'Trip',
        eventTypeOther: 'Other',

        // Gallery / Photos Section
        galleryTitle: 'Photo Gallery',
        gallerySubtitle: 'Drag photos to reorder them',
        btnAddPhotos: 'Add Photos',
        galleryCount: '12 photos',

        // Info Section
        infoTitle: 'Personal Information',
        infoSubtitle: 'Important details and events in child\'s life',
        btnAddInfo: 'Add Information',
        infoModalTitle: 'Add New Information',
        infoEventTypeLabel: 'Event Type',
        eventTypeMilestone: 'Personal Milestone',
        eventTypeMedical: 'Medical',
        eventTypeSocial: 'Social',
        eventTypeFamily: 'Family',
        infoAgeStageLabel: 'Age Stage',
        infoAgeStagePlaceholder: 'e.g., Early Childhood, 1 year old, 3 years...',
        infoTitleLabel: 'Title',
        infoTitlePlaceholder: 'e.g., First Steps, Doctor Visit, Birthday...',
        infoDetailsLabel: 'Details',
        infoDetailsPlaceholder: 'Add details about this important event...',
        infoDateLabel: 'Date',
        infoTimeLabel: 'Time',
        infoMainPhotoLabel: 'Main Photo',
        infoExtraPhotosLabel: 'Additional Photos',
        infoAddPhotos: 'Add additional photos (multiple allowed)',
        btnSaveInfo: 'Save Information',
        noInformation: 'No information added yet',
        btnStartInfo: 'Add First Information',

        // Cover bio (all stages)
        coverBioMyName: 'My name is',
        coverBioAndAge: 'and I am',
        unitYear: 'year',
        unitYears: 'years',
        yearPrefix: 'Year ',
        badgeAdulthood: 'Adulthood',
        badgeTeenager: 'Teenage',
        badgeChild: 'Childhood',

        // Home events
        homeEventsTitle: 'Latest Events',
        viewAllEvents: 'View All',

        // Buttons
        btnDetails: 'Details',
        btnPhotos: 'Photos',

        // Birth section labels
        labelBloodType: 'Blood Type',
        labelFatherName: "Father's Name",
        labelMotherName: "Mother's Name",
        labelDoctor: 'Doctor',
        altFootprint: 'Footprint',

        // Height / Adults
        heightSubtitleAdults: 'Track growth over time',
        certsSubtitleAdults: 'Document your achievements and certificates',
        recordedAt: 'Recorded:',
        timeAm: ' AM',
        timePm: ' PM',
        unitCm: 'cm',
        unitKg: 'kg',

        // Education section
        eduSectionTitle: 'Educational Stages',
        eduSectionSub: 'Card for each academic year',

        // Events section
        eventsTitle: 'Events',
        eventsSubtitle: 'All events and important occasions',
        filterAll: 'All',
        noEvents: 'No events yet.',
        noEventsYet: 'No events yet',
        noEventsInPeriod: 'No events in this period',

        // Life Book
        lifeBookTitle: 'Life Book',
        viewLifeBook: 'View Life Book',

        // Education years component
        emptyEduData: 'No data linked to educational stages. You can link sections (height, weight, achievements, etc.) from the dashboard.',
        labelHeight: 'Height:',
        labelWeight: 'Weight:',
        badgeHonor: 'Honor',
        badgeAchievementSuccess: 'Success',
        badgeVisit: 'Visit',
        badgeEvent: 'Event',
        badgeDrawing: 'Drawing',
        badgeVoice: 'Voice',
        badgeInjury: 'Injury',

        // Stats (adults/child/teen)
        statVisits: 'Visits & Events',
        statAchievements: 'Achievements',
        statMeasurements: 'Growth Records',
        statAge: 'Age (years)',

        // JS / Modal strings
        modalCoverImage: 'Cover Image',
        modalProfileImage: 'Profile Image',
        modalMeasurementsTitle: 'Growth Records',
        modalHeightLabel: 'Height: ',
        modalWeightLabel: 'Weight: ',
        modalMeasureImage: 'Measurement Image',
        modalBirthPhotos: 'Birth Photos',
        modalStageLabel: 'Stage:',
        modalEventTypeLabel: 'Event Type:',
        modalGradeLabel: 'Grade:',
        sourceBirth: 'Birth'
    }
};

/* ===========================
   LANGUAGE STATE
   =========================== */
let currentLanguage = 'ar';

/* Expose for inline scripts */
window.translations = translations;
window.getCurrentLanguage = () => currentLanguage;

/* Toggle Language */
function toggleLanguage() {
    currentLanguage = currentLanguage === 'ar' ? 'en' : 'ar';
    applyLanguage(currentLanguage);
}

/* Apply Language */
function applyLanguage(lang) {
    const html = document.documentElement;

    // Set direction & lang attribute
    html.setAttribute('lang', lang);
    html.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');

    // Swap Bootstrap CSS (RTL ↔ LTR)
    const bsLink = document.querySelector('link[href*="bootstrap"]');
    if (bsLink) {
        if (lang === 'ar') {
            bsLink.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css';
        } else {
            bsLink.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css';
        }
    }

    // Update title attributes
    document.querySelectorAll('[data-translate-title]').forEach(el => {
        const key = el.getAttribute('data-translate-title');
        if (translations[lang] && translations[lang][key]) {
            el.setAttribute('title', translations[lang][key]);
        }
    });

    // Update all translatable elements
    document.querySelectorAll('[data-translate]').forEach(el => {
        const key = el.getAttribute('data-translate');
        const contentAr = el.getAttribute('data-content-ar');
        const contentEn = el.getAttribute('data-content-en');
        if (contentAr !== null || contentEn !== null) {
            const val = lang === 'ar' ? (contentAr || contentEn || '') : (contentEn || contentAr || '');
            if (el.tagName === 'TITLE') {
                document.title = val || translations[lang]?.pageTitle || document.title;
            } else if (el.tagName === 'IMG') {
                el.setAttribute('alt', val || '');
            } else {
                const icon = el.querySelector(':scope > i');
                if (icon) {
                    el.innerHTML = icon.outerHTML + ' ' + (val || '').trim();
                } else {
                    el.textContent = val;
                }
            }
            return;
        }
        if (translations[lang] && translations[lang][key]) {
            const val = translations[lang][key];
            if (el.tagName === 'IMG') {
                el.setAttribute('alt', val);
            } else {
                const icon = el.querySelector('i');
                if (icon) {
                    el.innerHTML = icon.outerHTML + ' ' + val;
                } else {
                    el.textContent = val;
                }
            }
        }
    });

    // Update elements with user content (data-content-ar / data-content-en)
    document.querySelectorAll('[data-content-ar], [data-content-en]').forEach(el => {
        if (el.hasAttribute('data-translate')) return;
        const contentAr = el.getAttribute('data-content-ar') || '';
        const contentEn = el.getAttribute('data-content-en') || '';
        const val = lang === 'ar' ? (contentAr || contentEn) : (contentEn || contentAr);
        const icon = el.querySelector(':scope > i');
        if (icon) {
            el.innerHTML = icon.outerHTML + ' ' + (val || '').trim();
        } else {
            el.textContent = val;
        }
    });

    // Update page title (if not handled by data-content above)
    const titleEl = document.querySelector('title[data-translate]');
    if (titleEl && titleEl.getAttribute('data-content-ar') === null && titleEl.getAttribute('data-content-en') === null) {
        document.title = translations[lang].pageTitle || document.title;
    }

    // Save preference
    localStorage.setItem('preferredLanguage', lang);

    // Update single lang toggle button (shows language to switch TO)
    updateLangToggleButton();
}

function updateLangToggleButton() {
    const btn = document.getElementById('langToggleBtn');
    if (!btn) return;
    btn.textContent = currentLanguage === 'ar' ? (btn.getAttribute('data-switch-to-en') || 'EN') : (btn.getAttribute('data-switch-to-ar') || 'ع');
}

/* Init on load (visitor's localStorage preference takes priority, then stage default) */
document.addEventListener('DOMContentLoaded', function () {
    const stageLang = typeof window.stageDefaultLang !== 'undefined' && window.stageDefaultLang ? window.stageDefaultLang : null;
    const saved = localStorage.getItem('preferredLanguage') || stageLang;
    if (saved) {
        currentLanguage = saved;
        applyLanguage(currentLanguage);
    } else {
        currentLanguage = stageLang || 'ar';
        updateLangToggleButton();
    }
});
