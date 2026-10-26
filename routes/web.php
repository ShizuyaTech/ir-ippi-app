<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentValidationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LksBipartitController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProgramAchievementController;
use App\Http\Controllers\PublicAssessmentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

// Public assessment list
Route::get('/assessments', [PublicAssessmentController::class, 'index'])->name('assessments.public');

// Validation routes - pastikan seperti ini
Route::get('/assessment/{assessment}/validate', [AssessmentValidationController::class, 'showValidationForm'])->name('assessment.validate.form');
Route::post('/assessment/{assessment}/validate', [AssessmentValidationController::class, 'validateUser'])->name('assessment.validate');

// Public routes dengan controller terpisah
Route::get('/assessment/{assessment}/form', [PublicAssessmentController::class, 'showForm'])->name('assessment.form');
Route::get('/assessment/{assessment}/form/{page?}', [PublicAssessmentController::class, 'showForm'])->name('assessment.form.page');
// Route::get('/assessment/{assessment}/form/{page?}', [AssessmentController::class, 'showForm'])->name('assessment.form.page');
Route::post('/assessment/{assessment}/submit', [PublicAssessmentController::class, 'submitResponse'])->name('assessment.submit');
Route::get('/assessment/{assessment}/thank-you', [PublicAssessmentController::class, 'thankYou'])->name('assessment.thankyou');

// Public routes for automotive news
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');
Route::get('/news/topic/{topic}', [NewsController::class, 'searchByTopic'])->name('news.search-topic');
Route::get('/news/industry-insights', [NewsController::class, 'industryInsights'])->name('news.industry-insights');

// Public routes for programs and achievements
Route::get('/programs/{id}', [ProgramAchievementController::class, 'show'])->name('programs.show');

Route::get('/safety-reports/create', [\App\Http\Controllers\SafetyReportController::class, 'create'])->name('safety-reports.create');
Route::post('/safety-reports/verify-employee', [\App\Http\Controllers\SafetyReportController::class, 'verifyEmployee'])
    ->middleware('throttle:5,1')
    ->name('safety-reports.verify-employee');
Route::post('/safety-reports', [\App\Http\Controllers\SafetyReportController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('safety-reports.store');
Route::get('/safety-reports/confirmation', [\App\Http\Controllers\SafetyReportController::class, 'confirmation'])
    ->name('safety-reports.confirmation');

Route::middleware(['auth', 'dashboard.safety'])->group(function () {
    // Use DashboardController so the view receives required variables (scores, isAdmin, etc.)
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    Route::resource('users', UserController::class)->middleware('admin');
    Route::get('safety-report-employees/template', [\App\Http\Controllers\SafetyReportEmployeeController::class, 'downloadTemplate'])
        ->middleware('admin')
        ->name('safety-report-employees.template');
    Route::post('safety-report-employees/import', [\App\Http\Controllers\SafetyReportEmployeeController::class, 'import'])
        ->middleware('admin')
        ->name('safety-report-employees.import');
    Route::resource('safety-report-employees', \App\Http\Controllers\SafetyReportEmployeeController::class)
        ->except(['show'])
        ->middleware('admin');
    Route::resource('admin/assessments', AssessmentController::class);
    Route::get('/assessments/{assessment}/responses', [AssessmentController::class, 'responses'])->name('assessments.responses');
    Route::post('/admin/assessments/{assessment}/generate-codes', [AssessmentController::class, 'generateAdditionalCodes'])->name('admin.assessments.generate-codes');
    Route::post('/assessments/{assessment}/generate-codes', [AssessmentController::class, 'generateAdditionalCodes'])->name('assessments.generate-codes');
    Route::get('/admin/assessments/{assessment}/download-codes', [AssessmentController::class, 'downloadCodes'])->name('admin.assessments.download-codes');
    Route::get('/assessments/{assessment}/download-codes', [AssessmentController::class, 'downloadCodes'])->name('assessments.download-codes');
    Route::post('/admin/assessments/{assessment}/toggle-status', [AssessmentController::class, 'toggleStatus'])->name('admin.assessments.toggle-status');
    Route::post('/assessments/{assessment}/toggle-status', [AssessmentController::class, 'toggleStatus'])->name('assessments.toggle-status');

    // Question routes
    Route::put('/questions/{question}', [AssessmentController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/questions/{question}', [AssessmentController::class, 'destroyQuestion'])->name('questions.destroy');
    Route::post('/admin/assessments/{assessment}/questions', [AssessmentController::class, 'storeQuestion'])->name('assessments.questions.store');

    // Resource routes untuk feedback
    Route::resource('feedbacks', FeedbackController::class);

    // Custom routes
    Route::post('/feedbacks/{id}/respond', [FeedbackController::class, 'respond'])->name('feedbacks.respond');
    Route::post('/feedbacks/{id}/submit', [FeedbackController::class, 'submit'])->name('feedbacks.submit');

    // Resource routes untuk schedules
    Route::resource('schedules', ScheduleController::class);

    // Calendar view
    Route::get('/schedules-calendar', [ScheduleController::class, 'calendar'])->name('schedules.calendar');

    Route::post('lks-bipartit/{id}/update-status', [LksBipartitController::class, 'updateStatus'])->name('lks-bipartit.update-status');
    Route::resource('lks-bipartit', LksBipartitController::class);
    // Route khusus untuk update status saja
    Route::post('lks-bipartit/{id}/quick-status', [LksBipartitController::class, 'quickStatus'])->name('lks-bipartit.quick-status');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('dashboard-scores', DashboardController::class)->except(['show']);
    Route::post('/dashboard/scores', [DashboardController::class, 'updateScores'])->name('dashboard.update-scores');

    Route::prefix('safety-reports')->name('safety-reports.')->middleware('safety.role:safety_pic,ir_reviewer,she')->group(function () {
        Route::get('/', [\App\Http\Controllers\SafetyReportController::class, 'index'])->name('index');
        Route::get('/{safetyReport}', [\App\Http\Controllers\SafetyReportController::class, 'show'])->name('show');
        Route::get('/{safetyReport}/images/{type}', [\App\Http\Controllers\SafetyReportController::class, 'image'])->name('image');
        Route::get('/{safetyReport}/print', [\App\Http\Controllers\SafetyReportController::class, 'print'])->name('print');
        Route::post('/{safetyReport}/verify', [\App\Http\Controllers\SafetyReportController::class, 'verify'])
            ->middleware('safety.role:safety_pic')
            ->name('verify');
        Route::post('/{safetyReport}/ir-decision', [\App\Http\Controllers\SafetyReportController::class, 'decideAsIr'])
            ->middleware('safety.role:ir_reviewer')
            ->name('ir-decision');
        Route::post('/{safetyReport}/she-decision', [\App\Http\Controllers\SafetyReportController::class, 'decideAsShe'])
            ->middleware('safety.role:she')
            ->name('she-decision');
        Route::post('/{safetyReport}/plan', [\App\Http\Controllers\SafetyReportController::class, 'storePlan'])
            ->middleware('safety.role:she')
            ->name('plan');
        Route::post('/{safetyReport}/close', [\App\Http\Controllers\SafetyReportController::class, 'close'])
            ->middleware('safety.role:she')
            ->name('close');
    });

    // Program & Achievement Management
    Route::middleware(['auth'])->group(function () {
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::resource('program-achievements', ProgramAchievementController::class);
            Route::post('program-achievements/{id}/toggle-status', [ProgramAchievementController::class, 'toggleStatus'])
                ->name('program-achievements.toggle-status');
        });
    });

});
