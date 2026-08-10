<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\HR\HRDashboardController;
use App\Http\Controllers\NewTeacher\NewTeacherDashboardController;
use App\Http\Controllers\Principal\PrincipalDashboardController;
use App\Http\Controllers\HR\GuruNewController;
use App\Http\Controllers\NewTeacher\GuruNewProfileController;
use App\Http\Controllers\Observer\OBDashboardController;
use App\Http\Controllers\ExternalObserver\ExtDashboardController;
use App\Http\Controllers\Admin\ManageFormController;
use App\Http\Controllers\HR\HRProfileController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Principal\PrincipalProfileController;
use App\Http\Controllers\Principal\GNListController;
use App\Http\Controllers\Observer\OBProfile;
use App\Http\Controllers\ExternalObserver\EXTProfile;
use App\Http\Controllers\Admin\EvaluationDocController;
use App\Http\Controllers\EvaluationDocDownloadController;


/*Home*/

Route::get('/', function () {
    return redirect()->route('login');
});


/*Admin */
Route::middleware('auth:admin')->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');

    Route::get('/manage-form', [ManageFormController::class, 'index'])->name('admin.manage.form');
    Route::post('/evaluation-doc', [EvaluationDocController::class, 'store'])->name('admin.evaluation.doc.store');
    Route::delete('/evaluation-doc/{doc_id}', [EvaluationDocController::class, 'destroy'])->name('admin.evaluation.doc.delete');
});


/*HR*/
Route::middleware('auth:hr')->prefix('hr')->group(function () {

    Route::get('/dashboard', [HRDashboardController::class, 'index'])->name('hr.dashboard');
    Route::get('/profile', [HRProfileController::class, 'index'])->name('hr.profile');
    Route::put('/profile/password', [HRProfileController::class, 'updatePassword'])->name('hr.profile.password');

    Route::get('/new-teachers', [GuruNewController::class, 'index'])->name('hr.gurunew.index');
    Route::get('/new-teachers/create', [GuruNewController::class, 'create'])->name('hr.gurunew.create');
    Route::post('/new-teachers', [GuruNewController::class, 'store'])->name('hr.gurunew.store');
    Route::put('/new-teachers/{gn_id}', [GuruNewController::class, 'update'])->name('hr.gurunew.update');
});


/*GN*/
Route::middleware('auth:new_teacher')
    ->prefix('new-teacher')
    ->group(function () {

        Route::get('/dashboard', [NewTeacherDashboardController::class, 'index'])->name('new_teacher.dashboard');
        Route::get('/profile', [GuruNewProfileController::class, 'edit'])->name('new_teacher.profile');
        Route::put('/profile', [GuruNewProfileController::class, 'update'])->name('new_teacher.profile.update');
        Route::put('/profile/password', [GuruNewProfileController::class, 'updatePassword'])->name('new_teacher.profile.password');
    });


/*Principal*/
Route::middleware('auth:principal')
    ->prefix('principal')
    ->group(function () {

        Route::get('/dashboard', [PrincipalDashboardController::class, 'index'])->name('principal.dashboard');

        Route::get('/profile', [PrincipalProfileController::class, 'index'])->name('principal.profile');
        Route::put('/profile/password', [PrincipalProfileController::class, 'updatePassword'])->name('principal.profile.password');

        Route::get('/list-teacher', [GNListController::class, 'index'])->name('principal.gn.list');
    });


/*Observer*/
Route::middleware('auth:teacher')
    ->prefix('observer')
    ->group(function () {

        Route::get('/dashboard', [OBDashboardController::class, 'index'])->name('observer.dashboard');
        Route::get('/profile', [OBProfile::class, 'index'])->name('observer.profile');
        Route::put('/profile/password', [OBProfile::class, 'updatePassword'])->name('observer.profile.password');
        Route::get('/download-form', [EvaluationDocDownloadController::class, 'observer'])->name('observer.download.form');
    });


/*ExternalOB*/
Route::middleware('auth:teacher')
    ->prefix('external-observer')
    ->group(function () {

        Route::get('/dashboard', [ExtDashboardController::class, 'index'])->name('external.dashboard');
        Route::get('/profile', [EXTProfile::class, 'index'])->name('external.profile');
        Route::put('/profile/password', [EXTProfile::class, 'updatePassword'])->name('external.profile.password');
        Route::get('/download-form', [EvaluationDocDownloadController::class, 'external'])->name('external.download.form');
    });


/*Shared evaluation document download*/
Route::get(
    '/evaluation-doc/{doc_id}/download',
    [EvaluationDocDownloadController::class, 'download']
)
    ->middleware('auth:teacher')
    ->name('evaluation.doc.download');


/*Authentication Routes*/
require __DIR__ . '/auth.php';
