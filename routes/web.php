<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Routes accessible to all users including guests
*/

// Home route - displays published projects
Route::get('/', [ProjectController::class, 'index'])->name('home');

// Locale switching route
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'th'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.switch');

// Authentication routes (login, register, logout, password reset)
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
| Routes that require user authentication
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Project Routes (resource routes plus custom actions)
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/submit', [ProjectController::class, 'submit'])->name('projects.submit');
    Route::post('/projects/{project}/approve', [ProjectController::class, 'approve'])->name('projects.approve');
    Route::post('/projects/{project}/reject', [ProjectController::class, 'reject'])->name('projects.reject');
    
    // Project File Management
    Route::post('/projects/{project}/files', [ProjectFileController::class, 'store'])->name('projects.files.store');
    Route::get('/projects/{project}/files/{file}/download', [ProjectFileController::class, 'download'])->name('projects.files.download');
    Route::delete('/projects/{project}/files/{file}', [ProjectFileController::class, 'destroy'])->name('projects.files.destroy');
    
    // Project Team Member Management
    Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])->name('projects.members.store');
    Route::delete('/projects/{project}/members/{member}', [ProjectMemberController::class, 'destroy'])->name('projects.members.destroy');
    
    // Project Evaluation Routes
    Route::get('/projects/{project}/evaluations/create', [EvaluationController::class, 'create'])->name('projects.evaluations.create');
    Route::post('/projects/{project}/evaluations', [EvaluationController::class, 'store'])->name('projects.evaluations.store');
    
    // Comment Routes
    Route::post('/projects/{project}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Report Routes (for advisors and admins)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/projects-by-year', [ReportController::class, 'projectsByYear'])->name('reports.projects-by-year');
    Route::get('/reports/projects-by-category', [ReportController::class, 'projectsByCategory'])->name('reports.projects-by-category');
    Route::get('/reports/average-scores', [ReportController::class, 'averageScores'])->name('reports.average-scores');
    Route::get('/reports/advisor-projects', [ReportController::class, 'advisorProjects'])->name('reports.advisor-projects');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Routes restricted to admin users only
*/

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // Tag Management
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
});
