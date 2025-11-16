<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
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

require __DIR__.'/auth.php';

Route::resource('jobs', JobController::class)->middleware(['auth','isAdmin'])->except(['index','show']);
Route::resource('jobs', JobController::class)->middleware('auth')->only(['index','show']);
Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('apply.store')->middleware('auth');
Route::get('/jobs/{job}/applicants', [ApplicationController::class, 'index'])->name('applications.index')->middleware(['auth', 'isAdmin']);
Route::get('/applications/export', [ApplicationController::class, 'export'])->name('applications.export')->middleware(['auth', 'isAdmin']);

Route::patch('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update')->middleware(['auth', 'isAdmin']);
Route::get('/applications/{application}/download', [ApplicationController::class, 'download'])->name('applications.download')->middleware(['auth', 'isAdmin']);
Route::post('/jobs/import', [JobController::class, 'import'])->name('jobs.import')->middleware(['auth', 'isAdmin']);

Route::get('/applications/my', [ApplicationController::class, 'myApplications'])->name('applications.my')->middleware('auth');
