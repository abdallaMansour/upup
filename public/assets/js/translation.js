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
        colorLabel: 'ثيمات الأطفال',
        navInfoLabel: 'أطفل نقال - المختبف وتغنيمة 🎨',

        // Cover
        btnEditCover: 'تحديث صورة الغلاف',
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
        tabCertificates: 'الشهادات',
        tabEducation: 'التعليم',
        tabPhotos: 'الصور',
        tabInfo: 'المعلومات',

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
        labelCoverImage: 'صورة الغلاف',
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
        btnStartInfo: 'أضف أول معلومة'
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
        tabCertificates: 'Certificates',
        tabEducation: 'Education',
        tabPhotos: 'Photos',
        tabInfo: 'Information',

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
        btnStartInfo: 'Add First Information'
    }
};

/* ===========================
   LANGUAGE STATE
   =========================== */
let currentLanguage = 'ar';

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

    // Update all translatable elements
    document.querySelectorAll('[data-translate]').forEach(el => {
        const key = el.getAttribute('data-translate');
        if (translations[lang] && translations[lang][key]) {
            // Preserve inner icons
            const icon = el.querySelector('i');
            if (icon) {
                const iconHTML = icon.outerHTML;
                if (lang === 'ar') {
                    el.innerHTML = iconHTML + ' ' + translations[lang][key];
                } else {
                    el.innerHTML = iconHTML + ' ' + translations[lang][key];
                }
            } else {
                el.textContent = translations[lang][key];
            }
        }
    });

    // Update page title
    document.title = translations[lang].pageTitle || document.title;

    // Save preference
    localStorage.setItem('preferredLanguage', lang);
}

/* Init on load */
document.addEventListener('DOMContentLoaded', function () {
    const saved = localStorage.getItem('preferredLanguage');
    if (saved) {
        currentLanguage = saved;
        applyLanguage(currentLanguage);
    }
});
