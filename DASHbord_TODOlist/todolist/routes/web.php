<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dashboard and To-Do list routes (only for authenticated users)
Route::middleware(['auth', 'auth.role'])->group(function () {
    Route::get('/dashboard', [TodoController::class, 'dashboard'])->name('dashboard');


    // To-Do list routes
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::post('/todos/store', [TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/update/{id}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/delete/{id}', [TodoController::class, 'destroy'])->name('todos.destroy');
    Route::patch('/todos/toggle-complete/{id}', [TodoController::class, 'toggleComplete'])->name('todos.toggleComplete');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::resource('categories', CategoryController::class);
    Route::get('/todos/{id}/edit', [TodoController::class, 'edit'])->name('todos.edit');
    Route::put('/todos/{id}', [TodoController::class, 'update'])->name('todos.update');
    

});

// Profile routes (only for authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication routes
require __DIR__ . '/auth.php';
