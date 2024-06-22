<?php

use App\Models\JobApplication;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\JobsController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobApplicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/jobs',[JobController::class,'index'])->name('jobs.index');
Route::get('/jobs/detail/{id}',[JobController::class,'detail'])->name('jobDetail');
Route::post('/apply-job',[JobController::class,'applyJob'])->name('applyJob');
Route::post('/save-job',[JobController::class,'saveJob'])->name('saveJob');


Route::prefix('admin')->middleware('checkRole')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/jobs', [JobsController::class, 'index'])->name('admin.jobs');
    Route::get('/jobs/{id}', [JobsController::class, 'edit'])->name('admin.jobs.edit');
    Route::post('/jobs/{id}', [JobsController::class, 'update'])->name('admin.jobs.update');
    Route::delete('/jobs', [JobsController::class, 'destroy'])->name('admin.jobs.destroy');
    Route::get('/jobApplications', [JobApplicationController::class, 'index'])->name('admin.jobApplications');
    Route::delete('/jobApplications', [JobApplicationController::class, 'destroy'])->name('admin.jobApplications.destroy');
});




Route::prefix('account')->group(function () {
    // Guest Routes
    Route::middleware('guest')->group(function () {
        Route::get('/registration', [AccountController::class, 'registration'])->name('account.register');
        Route::post('/save-registration', [AccountController::class, 'saveRegistration'])->name('account.save-registration');
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');
        Route::post('/auth', [AccountController::class, 'authenticate'])->name('account.auth');
    });

    // Authenticated Routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('update-profile', [AccountController::class, 'updateUserProfile'])->name('account.update-profile');
        Route::post('update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
        Route::post('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::post('update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.update-profilePic');
        Route::get('create-job', [AccountController::class, 'createJob'])->name('account.createJob');
        Route::post('save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
        Route::get('my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
        Route::get('my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
        Route::post('my-jobs/update/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
        Route::post('my-jobs/delete', [AccountController::class, 'deleteJob'])->name('account.deleteJob');

        Route::get('my-job-applications', [AccountController::class, 'myJobApplications'])->name('account.myJobApplications');
        Route::post('remove-job-application', [AccountController::class, 'removeAppliedJobs'])->name('account.removeAppliedJobs');

        Route::get('saved-jobs',[AccountController::class,'savedJobs'])->name('account.savedJobs');
        Route::post('remove-saved-application', [AccountController::class, 'removeSavedJobs'])->name('account.removeSavedJobs');
    });
});
