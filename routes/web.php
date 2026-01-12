<?php
// routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RegistrationApprovalController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TreasurerController;
use App\Http\Controllers\MembershipFormController;
use App\Http\Controllers\GeneralInfoController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\DocumentRenewalController;
use App\Http\Controllers\SocialDevelopmentController;
use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;

// Image serving route (publicly accessible to bypass LocalTunnel restrictions)
Route::get('/img/{path}', [ImageController::class, 'serve'])
    ->where('path', '.*')
    ->name('image.serve');

// Landing Page (Home)
Route::get('/', [LandingController::class, 'index'])->name('landing');

// About System Page
Route::get('/about-system', [LandingController::class, 'aboutSystem'])
    ->name('about.system');
    
// Terms and Conditions page (publicly accessible)
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Registration pending page (publicly accessible)
Route::get('/registration-pending', function () {
    return view('auth.registration-pending');
})->name('registration.pending');

// Password setup pages (publicly accessible)
Route::get('/password-setup/{token}', [App\Http\Controllers\PasswordSetupController::class, 'show'])->name('password.setup');
Route::post('/password-setup', [App\Http\Controllers\PasswordSetupController::class, 'store'])->name('password.setup.store');

// View membership form
Route::get('/registrations/{operator}/membership-form',[RegistrationApprovalController::class, 'viewMembershipForm'])->name('registrations.view-membership-form');

// Download membership form in Operator registration approvals
Route::get('/registrations/{operator}/membership-form/download', [RegistrationApprovalController::class, 'downloadMembershipForm'])->name('registrations.download-membership-form');

// Download membership form (publicly accessible)
Route::get('/download/membership-form', [MembershipFormController::class, 'download'])->name('download.membership.form');

// Protected routes that require authentication
Route::middleware('auth')->group(function () {
    
    // Dashboard - accessible by both admin and operator
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Operator Profile Routes
    Route::get('/operator/profile-data', [App\Http\Controllers\OperatorProfileController::class, 'getProfile'])->name('operator.profile.data');
    Route::get('/operator/profile-view', [App\Http\Controllers\OperatorProfileController::class, 'showProfile'])->name('operator.profile.view');
    Route::post('/operator/profile/update', [App\Http\Controllers\OperatorProfileController::class, 'updateProfile'])->name('operator.profile.update');
    //Route::get('/operator/application-form', [App\Http\Controllers\OperatorProfileController::class, 'viewApplicationForm'])->name('operator.application-form');

    // API Routes for Dashboard modals
    Route::prefix('api')->group(function () {
        // Your existing API routes
        Route::get('/operators', [DashboardApiController::class, 'getOperators']);
        Route::get('/operators/{id}', [DashboardApiController::class, 'getOperatorDetail']);
        Route::get('/drivers', [DashboardApiController::class, 'getDrivers']);
        Route::get('/drivers/{id}', [DashboardApiController::class, 'getDriverDetail']);
        Route::get('/units', [DashboardApiController::class, 'getUnits']);
        Route::get('/my-drivers', [DashboardApiController::class, 'getMyDrivers']);
        Route::get('/my-units', [DashboardApiController::class, 'getMyUnits']);
        Route::get('/my-meeting-attendance', [DashboardApiController::class, 'getMyMeetingAttendance']);
        Route::post('/driver-assignments', [DashboardApiController::class, 'saveDriverAssignments']);
        Route::get('/expiring-documents', [DashboardApiController::class, 'getExpiringDocuments']);

        // Transaction routes
        Route::post('/transactions', [TransactionController::class, 'store']);
        Route::get('/transactions/operator/{operatorId}', [TransactionController::class, 'getOperatorTransactions']);
        Route::get('/transactions/my-transactions', [TransactionController::class, 'getMyTransactions']);
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy']);

        // Driver API routes for CRUD operations
        Route::post('/drivers', [DriverController::class, 'store']);
        Route::put('/drivers/{driver}', [DriverController::class, 'update']);
        Route::delete('/drivers/{driver}', [DriverController::class, 'destroy']);

        // Unit API routes for CRUD operations
        Route::get('/units/{unit}', [UnitController::class, 'show']);
        Route::post('/units', [UnitController::class, 'store']);
        Route::put('/units/{unit}', [UnitController::class, 'update']);
        Route::delete('/units/{unit}', [UnitController::class, 'destroy']);

        // Unit driver assignment routes
        Route::get('/units/{unit}/available-drivers', [UnitController::class, 'getAvailableDrivers']);
        Route::post('/units/{unit}/assign-driver', [UnitController::class, 'assignDriver']);
    });

    // API routes (accessible to authenticated users)
    Route::get('/api/check-upcoming-meeting', [MeetingController::class, 'checkUpcomingMeeting'])->name('api.check-upcoming-meeting');
    Route::get('/api/meeting/{meeting}/attendance-data', [MeetingController::class, 'getAttendanceData'])->name('api.meeting.attendance-data');
    Route::get('/api/meeting/{meeting}/details', [MeetingController::class, 'getMeetingDetails'])->name('api.meeting.details');
    Route::get('/api/operator/{operator}/details', [DashboardApiController::class, 'getOperatorDetails'])->name('api.operator.details');
    Route::get('/api/operator/{operator}/monthly-balances', [DashboardController::class, 'getMonthlyBalances'])->name('api.operator.monthly-balances');
    Route::get('/meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show')->middleware(['role:admin,president']);
    Route::put('/meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update')->middleware(['role:admin,president']);

    // President routes
    Route::middleware(['role:president'])->group(function () {
        Route::get('/president/operators', [DashboardController::class, 'operatorsDirectory'])->name('president.operators');
        Route::get('/president/faqs', [FaqController::class, 'presidentFaqs'])->name('president.faqs');
        Route::get('/president/meetings', [MeetingController::class, 'presidentIndex'])->name('president.meetings');
        Route::post('/president/meetings', [MeetingController::class, 'presidentStore'])->name('president.meetings.store');
        Route::get('/meetings/{meeting}/take-attendance', [MeetingController::class, 'showTakeAttendance'])->name('meetings.take-attendance');
        Route::post('/meetings/{meeting}/submit-attendance', [MeetingController::class, 'submitAttendance'])->name('meetings.submit-attendance');
        Route::get('president/annual-report', [DashboardController::class, 'presidentAnnualReport'])->name('president.annual-report');
        Route::post('president/annual-report', [DashboardController::class, 'saveAnnualReport'])->name('president.annual-report.save');
        Route::post('president/annual-report/pdf', [DashboardController::class, 'generateAnnualReportPDF'])->name('president.annual-report.pdf');
    });

    // Annual Report - Admin routes
    Route::middleware(['role:admin,president,treasurer'])->group(function () {
        Route::get('admin/annual-report', [DashboardController::class, 'adminAnnualReport'])->name('admin.annual-report');
        Route::post('admin/annual-report', [DashboardController::class, 'saveAnnualReport'])->name('admin.annual-report.save');
        Route::post('admin/annual-report/pdf', [DashboardController::class, 'generateAnnualReportPDF'])->name('admin.annual-report.pdf');
    });

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('operators', OperatorController::class);
        Route::resource('meetings', MeetingController::class)->except(['show', 'update']);
        Route::post('/meetings/{meeting}/attendance', [MeetingController::class, 'markAttendance'])->name('meetings.mark-attendance');

        //FAQ
        Route::get('/admin/faqs', [FaqController::class, 'adminFaqs'])->name('admin.faqs');

        // Database Backup Management
        Route::get('admin/database-backups', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'index'])->name('admin.database.index');
        Route::post('admin/database-backups/run', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'runBackup'])->name('admin.database.run');

        // Archived Operators
        Route::get('operators/archived', [OperatorController::class, 'archived'])->name('operators.archived');
        // Restore archived operator
        Route::patch('operators/{id}/restore', [OperatorController::class, 'restore'])->name('operators.restore');
        // Permanently delete operator
        Route::delete('operators/{id}/force-delete', [OperatorController::class, 'forceDestroy'])->name('operators.forceDestroy');

        // Officers management
        Route::get('officers', [OfficerController::class, 'index'])->name('officers.index');
        Route::get('officers/download-pdf', [OfficerController::class, 'downloadPdf'])->name('officers.download-pdf');
        Route::post('officers', [OfficerController::class, 'store'])->name('officers.store');
        Route::patch('officers/{officer}/status', [OfficerController::class, 'updateStatus'])->name('officers.updateStatus');
        Route::delete('officers/{officer}', [OfficerController::class, 'destroy'])->name('officers.destroy');

        // Requirements management
        Route::get('requirements', [RequirementController::class, 'index'])->name('requirements.index');
        Route::post('requirements', [RequirementController::class, 'store'])->name('requirements.store');
        Route::get('requirements/{type}', [RequirementController::class, 'show'])->name('requirements.show');
        Route::delete('requirements/{requirement}', [RequirementController::class, 'destroy'])->name('requirements.destroy');

        // Registration approval management
        Route::get('registrations', [RegistrationApprovalController::class, 'index'])->name('registrations.index');
        Route::get('registrations/{id}', [RegistrationApprovalController::class, 'show'])->name('registrations.show');
        Route::post('registrations/{id}/approve', [RegistrationApprovalController::class, 'approve'])->name('registrations.approve');
        Route::post('registrations/{id}/reject', [RegistrationApprovalController::class, 'reject'])->name('registrations.reject');
        Route::post('registrations/{id}/upload-membership-form', [RegistrationApprovalController::class, 'uploadMembershipForm'])->name('registrations.upload-membership-form');

        // Driver approval management
        Route::get('registrations/drivers/{id}', [RegistrationApprovalController::class, 'showDriver'])->name('registrations.drivers.show');
        Route::post('registrations/drivers/{id}/approve', [RegistrationApprovalController::class, 'approveDriver'])->name('registrations.drivers.approve');
        Route::post('registrations/drivers/{id}/reject', [RegistrationApprovalController::class, 'rejectDriver'])->name('registrations.drivers.reject');

        // Unit approval management
        Route::get('registrations/units/{id}', [RegistrationApprovalController::class, 'showUnit'])->name('registrations.units.show');
        Route::post('registrations/units/{id}/approve', [RegistrationApprovalController::class, 'approveUnit'])->name('registrations.units.approve');
        Route::post('registrations/units/{id}/reject', [RegistrationApprovalController::class, 'rejectUnit'])->name('registrations.units.reject');

        // Financial Books (Read-only for admin)
        Route::get('admin/cash-treasurers-book', [TreasurerController::class, 'cashTreasurersBook'])->name('admin.cash-treasurers-book');
        Route::get('admin/cash-treasurers-book/download-pdf', [TreasurerController::class, 'downloadCashTreasurersBookPdf'])->name('admin.cash-treasurers-book.download-pdf');
        Route::get('admin/cash-receipts-journal', [TreasurerController::class, 'cashReceiptsJournal'])->name('admin.cash-receipts-journal');
        Route::get('admin/cash-receipts-journal/download-pdf', [TreasurerController::class, 'downloadCashReceiptsJournalPdf'])->name('admin.cash-receipts-journal.download-pdf');
        Route::get('admin/cash-disbursement-book', [TreasurerController::class, 'cashDisbursementBook'])->name('admin.cash-disbursement-book');
        Route::get('admin/cash-disbursement-book/download-pdf', [TreasurerController::class, 'downloadCashDisbursementBookPdf'])->name('admin.cash-disbursement-book.download-pdf');
        Route::get('admin/cash-book', [TreasurerController::class, 'cashBook'])->name('admin.cash-book');
        Route::get('admin/cash-book/download-pdf', [TreasurerController::class, 'downloadCashBookPdf'])->name('admin.cash-book.download-pdf');

        // General Info management
        Route::get('admin/general-info', [GeneralInfoController::class, 'index'])->name('admin.general-info');
        Route::post('admin/general-info', [GeneralInfoController::class, 'store'])->name('admin.general-info.store');
        Route::get('admin/general-info/pdf', [GeneralInfoController::class, 'pdf'])->name('admin.general-info.pdf');

        // Reports
        Route::get('admin/report', [DashboardController::class, 'adminReport'])->name('admin.report');

        // Social Development Program routes
        Route::get('social-development/report', [SocialDevelopmentController::class, 'index'])->name('social-development.report');
        Route::post('social-development/cooperative', [SocialDevelopmentController::class, 'saveCooperativeActivities'])->name('social-development.cooperative.save');
        Route::post('social-development/community', [SocialDevelopmentController::class, 'saveCommunityActivities'])->name('social-development.community.save');
        Route::put('social-development/activities/{activity}',[SocialDevelopmentController::class, 'updateActivity'])->name('social-development.activities.update');
        Route::get('social-development/activities', [SocialDevelopmentController::class, 'getActivities'])->name('social-development.activities');
        Route::delete('social-development/activities/{activity}', [SocialDevelopmentController::class, 'deleteActivity'])->name('social-development.activities.delete');
        Route::get('social-development/pdf', [SocialDevelopmentController::class, 'generatePDF'])->name('social-report.pdf');

        // Audit Trail
        Route::get('admin/audit-trail', [DashboardController::class, 'auditTrail'])->name('admin.audit-trail');

        // Document Renewal Management
        Route::get('admin/document-renewals/{id}', [DocumentRenewalController::class, 'show'])->name('admin.document-renewals.show');
        Route::post('admin/document-renewals/{id}/approve', [DocumentRenewalController::class, 'approve'])->name('admin.document-renewals.approve');
        Route::post('admin/document-renewals/{id}/reject', [DocumentRenewalController::class, 'reject'])->name('admin.document-renewals.reject');
    });

    // Operator-only routes
    Route::middleware(['role:operator'])->group(function () {
        Route::resource('drivers', DriverController::class);
        Route::resource('units', UnitController::class);

        Route::get('/operator/faqs', [FaqController::class, 'operatorFaqs'])->name('operator.faqs');

        // Financial Books (Read-only for operator) - Excluding Cash Treasurer's Book
        Route::get('operator/cash-receipts-journal', [TreasurerController::class, 'cashReceiptsJournal'])->name('operator.cash-receipts-journal');
        Route::get('operator/cash-disbursement-book', [TreasurerController::class, 'cashDisbursementBook'])->name('operator.cash-disbursement-book');
        Route::get('operator/cash-book', [TreasurerController::class, 'cashBook'])->name('operator.cash-book');
        Route::get('operator/cash-book/download-pdf', [TreasurerController::class, 'downloadCashBookPdf'])->name('operator.cash-book.download-pdf');
    });

    // Treasurer-only routes
    Route::middleware(['role:treasurer'])->group(function () {
        Route::get('treasurer/cash-treasurers-book', [TreasurerController::class, 'cashTreasurersBook'])->name('treasurer.cash-treasurers-book');
        Route::get('treasurer/cash-receipts-journal', [TreasurerController::class, 'cashReceiptsJournal'])->name('treasurer.cash-receipts-journal');
        Route::get('treasurer/cash-disbursement-book', [TreasurerController::class, 'cashDisbursementBook'])->name('treasurer.cash-disbursement-book');
        Route::get('treasurer/cash-book', [TreasurerController::class, 'cashBook'])->name('treasurer.cash-book');
        Route::get('treasurer/cash-book/download-pdf', [TreasurerController::class, 'downloadCashBookPdf'])->name('treasurer.cash-book.download-pdf');

        Route::get('/treasurer/faqs', [FaqController::class, 'treasurerFaqs'])->name('treasurer.faqs');

        // Unpaid Balance routes
        Route::post('operator/unpaid-balance/update', [TransactionController::class, 'updateUnpaidBalance'])->name('operator.unpaid-balance.update');

        // Penalty management routes
        Route::get('treasurer/penalties', [\App\Http\Controllers\PenaltyController::class, 'index'])->name('treasurer.penalties.index');
        Route::get('treasurer/penalties/{penalty}/payment', [\App\Http\Controllers\PenaltyController::class, 'showPaymentForm'])->name('treasurer.penalties.payment');
        Route::post('treasurer/penalties/{penalty}/payment', [\App\Http\Controllers\PenaltyController::class, 'processPayment'])->name('treasurer.penalties.process-payment');

        // Annual Report routes
        Route::get('treasurer/annual-report', [DashboardController::class, 'treasurerAnnualReport'])->name('treasurer.annual-report');
        Route::post('treasurer/annual-report', [DashboardController::class, 'saveAnnualReport'])->name('treasurer.annual-report.save');
        Route::post('treasurer/annual-report/pdf', [DashboardController::class, 'generateAnnualReportPDF'])->name('treasurer.annual-report.pdf');

        // Particular Prices routes
        Route::get('treasurer/particular-prices', [\App\Http\Controllers\Treasurer\ParticularPriceController::class, 'index'])->name('treasurer.particular-prices');
        Route::post('treasurer/particular-prices', [\App\Http\Controllers\Treasurer\ParticularPriceController::class, 'store'])->name('treasurer.particular-prices.store');
        Route::put('treasurer/particular-prices/{price}', [\App\Http\Controllers\Treasurer\ParticularPriceController::class, 'update'])->name('treasurer.particular-prices.update');
        Route::delete('treasurer/particular-prices/{price}', [\App\Http\Controllers\Treasurer\ParticularPriceController::class, 'destroy'])->name('treasurer.particular-prices.destroy');
        Route::post('treasurer/particular-prices/calculate', [\App\Http\Controllers\Treasurer\ParticularPriceController::class, 'calculate'])->name('treasurer.particular-prices.calculate');
    });

    // GET route accessible to operators (authenticated users)
    Route::get('operator/unpaid-balance', [DashboardController::class, 'getMonthlyBalances'])->name('operator.unpaid-balance.index');

    // Change password for any authenticated user (admin or operator)
    Route::post('/user/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('user.change-password');

    // API routes for penalties
    Route::get('/api/operator/{operator}/penalties', [\App\Http\Controllers\PenaltyController::class, 'getOperatorPenalties'])->name('api.operator.penalties');
});

require __DIR__.'/auth.php';