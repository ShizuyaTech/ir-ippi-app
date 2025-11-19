<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentValidationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\IrAssessmentController;
use App\Http\Controllers\LksBipartitController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProgramAchievementController;
use App\Http\Controllers\PublicAssessmentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

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

Route::middleware('auth')->group(function () {
    // Use DashboardController so the view receives required variables (scores, isAdmin, etc.)
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
    
    Route::resource('users', UserController::class);
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
    
    // Program & Achievement Management
    Route::middleware(['auth'])->group(function () {
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::resource('program-achievements', ProgramAchievementController::class);
            Route::post('program-achievements/{id}/toggle-status', [ProgramAchievementController::class, 'toggleStatus'])
                ->name('program-achievements.toggle-status');
        });
    });

});


