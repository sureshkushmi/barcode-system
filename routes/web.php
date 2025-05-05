<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlacklistedWorkerController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\MemberController;

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

    Route::get('/blacklisted-workers', [BlacklistedWorkerController::class, 'index'])->name('blacklisted.index');
    Route::get('/blacklisted-workers/create', [BlacklistedWorkerController::class, 'create'])->name('blacklisted.create');
    Route::post('/blacklisted-workers/store', [BlacklistedWorkerController::class, 'store'])->name('blacklisted.store');
    Route::get('members',[MemberController::class,'index'])->name('members.members');
});

Route::middleware(['auth', 'role:member'])->group(function () {
    Route::get('/member/dashboard', [MemberController::class, 'dashboard'])->name('members.dashboard');
    //Route::get('/member/members', [SuperAdminController::class, 'index'])->name('superadmin.members');
});
Route::middleware(['auth','role:superadmin'])->group(function(){
    Route::get('/superadmin/dashboard',function(){
        return view('superadmin.dashboard');
    });
    //Route::get('/superadmin/members', [SuperAdminController::class, 'index'])->name('superadmin.members');
    Route::get('/superadmin/pending-members', [SuperadminController::class, 'pendingMembers'])->name('superadmin.pending-members');
    Route::post('/superadmin/approve-member/{id}', [SuperadminController::class, 'approveMember'])->name('superadmin.approve-member');
    Route::post('/superadmin/reject-member/{id}', [SuperadminController::class, 'rejectMember'])->name('superadmin.reject-member');
    Route::get('/blacklisted-workers/approve/{id}', [BlacklistedWorkerController::class, 'approve'])->name('blacklisted.approve');
});

require __DIR__.'/auth.php';
