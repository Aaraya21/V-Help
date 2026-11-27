<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffRequestController;
use App\Http\Controllers\StudentRequestController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// เปลี่ยนเส้นทางตามบทบาทหลังล็อกอิน
Route::get('/dashboard', function () {
    return auth()->user()->role === 'student'
        ? redirect()->route('student.home')
        : redirect()->route('staff.index');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ---------- นักศึกษา ----------
    Route::middleware(RoleMiddleware::class.':student')->prefix('student')->name('student.')->group(function () {
        Route::get('/home', [StudentRequestController::class, 'home'])->name('home');
        Route::get('/faq', [StudentRequestController::class, 'faq'])->name('faq');
        Route::get('/contact', [StudentRequestController::class, 'contact'])->name('contact');
        Route::post('/notifications/read', [StudentRequestController::class, 'markRead'])->name('notifications.read');
        Route::get('/requests', [StudentRequestController::class, 'index'])->name('index');
        Route::get('/requests/create', [StudentRequestController::class, 'create'])->name('create');
        Route::post('/requests', [StudentRequestController::class, 'store'])->name('store');
        Route::get('/requests/{serviceRequest}', [StudentRequestController::class, 'show'])->name('show');
    });

    // ---------- เจ้าหน้าที่ / หัวหน้างาน / ผู้ดูแล ----------
    Route::middleware(RoleMiddleware::class.':staff,supervisor,admin')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/requests', [StaffRequestController::class, 'index'])->name('index');
        Route::get('/requests/{serviceRequest}', [StaffRequestController::class, 'show'])->name('show');
        Route::patch('/requests/{serviceRequest}/status', [StaffRequestController::class, 'updateStatus'])->name('status');
        Route::get('/report', [StaffRequestController::class, 'report'])->name('report');
    });
});

require __DIR__.'/auth.php';
