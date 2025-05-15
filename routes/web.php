<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/users/dashboard', [MemberController::class, 'dashboard'])->name('users.dashboard');
    //Route::get('/member/members', [SuperAdminController::class, 'index'])->name('superadmin.members');
});

Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/superadmin/dashboard',function(){
        return view('superadmin.dashboard');
    });
    Route::get('/superadmin/pending-members', [SuperadminController::class, 'pendingMembers'])->name('superadmin.pending-members');
});

require __DIR__.'/auth.php';
