<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserVerified;
use App\Http\Controllers\Dashboard\FaqController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\PagesController;
use App\Http\Controllers\Dashboard\FeatureController;
use App\Http\Controllers\Dashboard\PackageController;
use App\Http\Controllers\Dashboard\AdminAuthController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\SiteSettingController;
use App\Http\Controllers\Dashboard\VerificationController;
use App\Http\Controllers\Dashboard\SupportTicketController;
use App\Http\Controllers\Dashboard\MediaDepartmentController;
use App\Http\Controllers\Dashboard\TechnicalSupportController;
use App\Http\Controllers\Dashboard\AchievementController;
use App\Http\Controllers\Dashboard\ChildhoodStageController;
use App\Http\Controllers\Dashboard\DocumentController;
use App\Http\Controllers\Dashboard\MyPagesController;
use App\Http\Controllers\Dashboard\DrawingController;
use App\Http\Controllers\Dashboard\InjuryController;
use App\Http\Controllers\Dashboard\OtherEventController;
use App\Http\Controllers\Dashboard\VisitController;
use App\Http\Controllers\Dashboard\VoiceController;
use App\Http\Controllers\Dashboard\HeightWeightController;
use App\Http\Controllers\Dashboard\StoragePlatformController;
use App\Http\Controllers\Dashboard\SubscriptionController as DashboardSubscriptionController;

// Admin Auth (login only - no register, no forgot-password)
Route::prefix('auth')->middleware('guest:admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('login', [AdminAuthController::class, 'processLogin'])->name('login.process');
});

Route::post('auth/logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth:admin');

// Dashboard Pages (users + admins - users see limited menu)
Route::middleware(['auth:web,admin', EnsureUserVerified::class])->group(function () {
    Route::get('/', [PagesController::class, 'index'])->name('index');

    // Verification (must be accessible before full verification)
    Route::get('verification', [VerificationController::class, 'index'])->name('verification.index');
    Route::post('verification/email/send', [VerificationController::class, 'sendEmailCode'])->name('verification.email.send');
    Route::post('verification/email', [VerificationController::class, 'verifyEmail'])->name('verification.email.verify');
    Route::post('verification/phone/send', [VerificationController::class, 'sendPhoneCode'])->name('verification.phone.send');
    Route::post('verification/phone', [VerificationController::class, 'verifyPhone'])->name('verification.phone.verify');

    // Packages: index for both, create/store/edit/update/destroy for admin only
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('faq', [FaqController::class, 'index'])->name('faq.index');
    Route::get('features', [FeatureController::class, 'index'])->name('features.index');
    Route::get('privacy-policy', [SiteSettingController::class, 'privacyPolicy'])->name('privacy-policy.index');
    Route::get('terms-and-conditions', [SiteSettingController::class, 'termsAndConditions'])->name('terms-and-conditions.index');
    Route::get('media-department', [MediaDepartmentController::class, 'index'])->name('media-department.index')->middleware('permission:media-department.manage');

    // Support Tickets (users + admins)
    Route::get('support-tickets', [SupportTicketController::class, 'index'])->name('support-tickets.index');
    Route::get('support-tickets/create', [SupportTicketController::class, 'create'])->name('support-tickets.create');
    Route::post('support-tickets', [SupportTicketController::class, 'store'])->name('support-tickets.store');
    Route::get('support-tickets/{support_ticket}', [SupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('support-tickets/{support_ticket}/reply', [SupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::put('support-tickets/{support_ticket}/status', [SupportTicketController::class, 'updateStatus'])->name('support-tickets.status')->middleware(['auth:admin', 'permission:support-tickets.manage']);

    // Documents & Storage (web users only - auth('web')->check())
    Route::middleware('auth:web')->group(function () {
        Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('documents/{document}/view', [DocumentController::class, 'viewFile'])->name('documents.view-file');
        Route::get('documents/{document}/embed', [DocumentController::class, 'embedFile'])->name('documents.embed');
        Route::post('documents/folders', [DocumentController::class, 'storeFolder'])->name('documents.folders.store');
        Route::post('documents/files', [DocumentController::class, 'storeFile'])->name('documents.files.store');
        Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::put('documents/{document}/move', [DocumentController::class, 'move'])->name('documents.move');
        Route::get('documents/storage-connections', [DocumentController::class, 'storageConnections'])->name('documents.storage-connections');
        Route::get('documents/google-drive/connect', [DocumentController::class, 'connectGoogleDrive'])->name('documents.google-drive.connect');
        Route::get('documents/google-drive/callback', [DocumentController::class, 'callbackGoogleDrive'])->name('documents.google-drive.callback');
        Route::post('documents/google-drive/sync', [DocumentController::class, 'syncGoogleDrive'])->name('documents.google-drive.sync');
        Route::get('documents/wasabi/connect', [DocumentController::class, 'connectWasabi'])->name('documents.wasabi.connect');
        Route::post('documents/wasabi/store', [DocumentController::class, 'storeWasabi'])->name('documents.wasabi.store');
        Route::post('documents/wasabi/sync', [DocumentController::class, 'syncWasabi'])->name('documents.wasabi.sync');
        Route::get('documents/switch-storage/confirm', [DocumentController::class, 'switchStorageConfirm'])->name('documents.switch-storage.confirm');
        Route::get('documents/switch-storage/confirm-restore', [DocumentController::class, 'switchStorageConfirmRestore'])->name('documents.switch-storage.confirm-restore');
        Route::post('documents/switch-storage/proceed', [DocumentController::class, 'switchStorageProceed'])->name('documents.switch-storage.proceed');
        Route::post('documents/switch-storage/restore', [DocumentController::class, 'switchStorageRestore'])->name('documents.switch-storage.restore');

        Route::get('life-stages/childhood', [ChildhoodStageController::class, 'index'])->name('life-stages.childhood.index');
        Route::post('life-stages/childhood', [ChildhoodStageController::class, 'store'])->name('life-stages.childhood.store');
        Route::put('life-stages/childhood', [ChildhoodStageController::class, 'update'])->name('life-stages.childhood.update');

        Route::get('my-pages', [MyPagesController::class, 'index'])->name('my-pages.index');
        Route::get('my-pages/create', [MyPagesController::class, 'create'])->name('my-pages.create');
        Route::post('my-pages', [MyPagesController::class, 'store'])->name('my-pages.store');
        Route::get('my-pages/{stage}/edit', [MyPagesController::class, 'edit'])->name('my-pages.edit');
        Route::put('my-pages/{stage}', [MyPagesController::class, 'update'])->name('my-pages.update');
        Route::get('my-pages/{stage}/documents', [MyPagesController::class, 'documents'])->name('my-pages.documents');
        Route::post('my-pages/{stage}/permissions', [MyPagesController::class, 'storePermission'])->name('my-pages.permissions.store');
        Route::delete('my-pages/{stage}', [MyPagesController::class, 'destroy'])->name('my-pages.destroy');

        Route::get('height-weight', [HeightWeightController::class, 'index'])->name('height-weight.index');
        Route::get('height-weight/create', [HeightWeightController::class, 'create'])->name('height-weight.create');
        Route::post('height-weight', [HeightWeightController::class, 'store'])->name('height-weight.store');
        Route::get('height-weight/{height_weight}/edit', [HeightWeightController::class, 'edit'])->name('height-weight.edit');
        Route::put('height-weight/{height_weight}', [HeightWeightController::class, 'update'])->name('height-weight.update');
        Route::delete('height-weight/{height_weight}', [HeightWeightController::class, 'destroy'])->name('height-weight.destroy');

        Route::get('achievements', [AchievementController::class, 'index'])->name('achievements.index');
        Route::get('achievements/create', [AchievementController::class, 'create'])->name('achievements.create');
        Route::post('achievements', [AchievementController::class, 'store'])->name('achievements.store');
        Route::get('achievements/{achievement}/edit', [AchievementController::class, 'edit'])->name('achievements.edit');
        Route::put('achievements/{achievement}', [AchievementController::class, 'update'])->name('achievements.update');
        Route::delete('achievements/{achievement}', [AchievementController::class, 'destroy'])->name('achievements.destroy');

        Route::get('voices', [VoiceController::class, 'index'])->name('voices.index');
        Route::get('voices/create', [VoiceController::class, 'create'])->name('voices.create');
        Route::post('voices', [VoiceController::class, 'store'])->name('voices.store');
        Route::get('voices/{voice}/edit', [VoiceController::class, 'edit'])->name('voices.edit');
        Route::put('voices/{voice}', [VoiceController::class, 'update'])->name('voices.update');
        Route::delete('voices/{voice}', [VoiceController::class, 'destroy'])->name('voices.destroy');

        Route::get('drawings', [DrawingController::class, 'index'])->name('drawings.index');
        Route::get('drawings/create', [DrawingController::class, 'create'])->name('drawings.create');
        Route::post('drawings', [DrawingController::class, 'store'])->name('drawings.store');
        Route::get('drawings/{drawing}/edit', [DrawingController::class, 'edit'])->name('drawings.edit');
        Route::put('drawings/{drawing}', [DrawingController::class, 'update'])->name('drawings.update');
        Route::delete('drawings/{drawing}', [DrawingController::class, 'destroy'])->name('drawings.destroy');

        Route::get('visits', [VisitController::class, 'index'])->name('visits.index');
        Route::get('visits/create', [VisitController::class, 'create'])->name('visits.create');
        Route::post('visits', [VisitController::class, 'store'])->name('visits.store');
        Route::get('visits/{visit}/edit', [VisitController::class, 'edit'])->name('visits.edit');
        Route::put('visits/{visit}', [VisitController::class, 'update'])->name('visits.update');
        Route::delete('visits/{visit}', [VisitController::class, 'destroy'])->name('visits.destroy');

        Route::get('injuries', [InjuryController::class, 'index'])->name('injuries.index');
        Route::get('injuries/create', [InjuryController::class, 'create'])->name('injuries.create');
        Route::post('injuries', [InjuryController::class, 'store'])->name('injuries.store');
        Route::get('injuries/{injury}/edit', [InjuryController::class, 'edit'])->name('injuries.edit');
        Route::put('injuries/{injury}', [InjuryController::class, 'update'])->name('injuries.update');
        Route::delete('injuries/{injury}', [InjuryController::class, 'destroy'])->name('injuries.destroy');

        Route::get('other-events', [OtherEventController::class, 'index'])->name('other-events.index');
        Route::get('other-events/create', [OtherEventController::class, 'create'])->name('other-events.create');
        Route::post('other-events', [OtherEventController::class, 'store'])->name('other-events.store');
        Route::get('other-events/{other_event}/edit', [OtherEventController::class, 'edit'])->name('other-events.edit');
        Route::put('other-events/{other_event}', [OtherEventController::class, 'update'])->name('other-events.update');
        Route::delete('other-events/{other_event}', [OtherEventController::class, 'destroy'])->name('other-events.destroy');
    });

    Route::middleware('auth:admin')->group(function () {
        // المستخدمين
        Route::middleware('permission:users.view')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
        });
        Route::middleware('permission:users.edit')->group(function () {
            Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        });
        Route::middleware('permission:users.delete')->group(function () {
            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });
        Route::middleware('permission:users.ban')->group(function () {
            Route::post('users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
            Route::post('users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
        });

        Route::middleware('permission:packages.create')->group(function () {
            Route::get('packages/create', [PackageController::class, 'create'])->name('packages.create');
            Route::post('packages', [PackageController::class, 'store'])->name('packages.store');
        });
        Route::middleware('permission:packages.edit')->group(function () {
            Route::get('packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
            Route::put('packages/{package}', [PackageController::class, 'update'])->name('packages.update');
        });
        Route::middleware('permission:packages.delete')->group(function () {
            Route::delete('packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
        });

        Route::middleware('permission:subscriptions.view')->group(function () {
            Route::get('subscriptions', [DashboardSubscriptionController::class, 'index'])->name('subscriptions.index');
            Route::get('subscriptions/{subscription}', [DashboardSubscriptionController::class, 'show'])->name('subscriptions.show');
        });

        // FAQ CRUD (admin only)
        Route::middleware('permission:faq.manage')->group(function () {
            Route::get('faq/create', [FaqController::class, 'create'])->name('faq.create');
            Route::post('faq', [FaqController::class, 'store'])->name('faq.store');
            Route::get('faq/{faq}/edit', [FaqController::class, 'edit'])->name('faq.edit');
            Route::put('faq/{faq}', [FaqController::class, 'update'])->name('faq.update');
            Route::delete('faq/{faq}', [FaqController::class, 'destroy'])->name('faq.destroy');
        });

        // Features CRUD (admin only)
        Route::middleware('permission:features.manage')->group(function () {
            Route::get('features/create', [FeatureController::class, 'create'])->name('features.create');
            Route::post('features', [FeatureController::class, 'store'])->name('features.store');
            Route::get('features/{feature}/edit', [FeatureController::class, 'edit'])->name('features.edit');
            Route::put('features/{feature}', [FeatureController::class, 'update'])->name('features.update');
            Route::delete('features/{feature}', [FeatureController::class, 'destroy'])->name('features.destroy');
        });

        // Site Settings (privacy policy & terms - same table, separate sections)
        Route::middleware('permission:site-settings.manage')->group(function () {
            Route::put('privacy-policy', [SiteSettingController::class, 'updatePrivacyPolicy'])->name('privacy-policy.update');
            Route::put('terms-and-conditions', [SiteSettingController::class, 'updateTermsAndConditions'])->name('terms-and-conditions.update');
        });

        // Media Department (admin only)
        Route::middleware('permission:media-department.manage')->group(function () {
            Route::put('media-department', [MediaDepartmentController::class, 'update'])->name('media-department.update');
        });

        // Storage Platforms (admin only)
        Route::middleware('permission:storage-platforms.manage')->group(function () {
            Route::get('storage-platforms', [StoragePlatformController::class, 'index'])->name('storage-platforms.index');
            Route::put('storage-platforms/{storage_platform}', [StoragePlatformController::class, 'update'])->name('storage-platforms.update');
        });

        // Technical Support (admin only)
        Route::middleware('permission:technical-support.view')->group(function () {
            Route::get('technical-support', [TechnicalSupportController::class, 'index'])->name('technical-support.index');
            Route::get('technical-support/mails', [TechnicalSupportController::class, 'mails'])->name('technical-support.mails');
            Route::get('technical-support/{contact_message}', [TechnicalSupportController::class, 'show'])->name('technical-support.show');
        });
        Route::middleware('permission:technical-support.manage')->group(function () {
            Route::post('technical-support/{contact_message}/reply', [TechnicalSupportController::class, 'reply'])->name('technical-support.reply');
        });

        // Admin Management (role & permissions)
        Route::middleware('permission:admins.view')->group(function () {
            Route::get('admins', [AdminController::class, 'index'])->name('admins.index');
        });
        Route::middleware('permission:admins.create')->group(function () {
            Route::get('admins/create', [AdminController::class, 'create'])->name('admins.create');
            Route::post('admins', [AdminController::class, 'store'])->name('admins.store');
        });
        Route::middleware('permission:admins.edit')->group(function () {
            Route::get('admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
            Route::put('admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
        });
        Route::middleware('permission:admins.delete')->group(function () {
            Route::delete('admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
        });

        Route::middleware('permission:roles.view')->group(function () {
            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        });
        Route::middleware('permission:roles.create')->group(function () {
            Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        });
        Route::middleware('permission:roles.edit')->group(function () {
            Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        });
        Route::middleware('permission:roles.delete')->group(function () {
            Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        });

        Route::middleware('permission:permissions.view')->group(function () {
            Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
        });
        Route::middleware('permission:permissions.edit')->group(function () {
            Route::post('permissions/update', [PermissionController::class, 'updateRolePermissions'])->name('permissions.update');
        });
    });
});
