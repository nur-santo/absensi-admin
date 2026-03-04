<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PerizinanApprovalController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShiftsController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LiburController;
use App\Http\Controllers\Admin\KehadiranController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth('admin')->check()
        ? redirect()->route('admin.home')
        : redirect()->route('admin.login');
});

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->middleware('guest:admin')
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->middleware('guest:admin')
    ->name('admin.login.submit');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->middleware('auth:admin')
    ->name('admin.logout');

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware('auth:admin')
    ->group(function () {

        /*
        | DASHBOARD
        */
        Route::get('/home', [HomeController::class, 'index'])
            ->name('admin.home');
            

        /*
        | USERS
        */
        Route::get('users/search', [UserController::class, 'search'])
            ->name('admin.users.search');

        Route::resource('users', UserController::class)
            ->names('admin.users');

        /*
        | KEHADIRAN
        */
        Route::post('/generate-kehadiran', [KehadiranController::class,'generate'])
            ->name('admin.generate.kehadiran');

        /*
        | PERIZINAN
        */
        Route::get('/perizinan', [PerizinanApprovalController::class, 'index'])
            ->name('admin.perizinan.index');

        Route::post('/perizinan/{perizinan}/approve', [PerizinanApprovalController::class, 'approve'])
            ->name('admin.perizinan.approve');

        Route::post('/perizinan/{perizinan}/reject', [PerizinanApprovalController::class, 'reject'])
            ->name('admin.perizinan.reject');

        /*
        | SETTINGS
        */
        Route::get('/settings', [SettingsController::class, 'index'])
            ->name('admin.settings.index');

        Route::get('/settings/shifts', [ShiftsController::class, 'index'])
            ->name('admin.shifts');

        Route::patch('/settings/shifts/{shift}', [ShiftsController::class, 'update'])
            ->name('admin.settings.shifts.update');

        /*
        | LAPORAN
        */
        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('admin.laporan.index');

        Route::post('/laporan/export', [LaporanController::class, 'export'])
            ->name('admin.laporan.export');

        /*
        | LIBUR
        */
        Route::get('libur/manage/{id?}', [LiburController::class, 'manage'])
            ->name('libur.manage');

        Route::post('libur/store', [LiburController::class, 'store'])
            ->name('libur.store');

        Route::put('libur/update/{id}', [LiburController::class, 'update'])
            ->name('libur.update');

        Route::delete('libur/destroy/{id}', [LiburController::class, 'destroy'])
            ->name('libur.destroy');
    });