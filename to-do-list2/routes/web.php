<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['middleware' => 'auth'], function (){
    Route::post('/tag', [\App\Http\Controllers\TagController::class, 'store'])->name('tag.store');
    Route::post('/category', [\App\Http\Controllers\CategoryController::class, 'store'])->name('category.store');

//    Tasks routes
    Route::get('/', [\App\Http\Controllers\TaskController::class, 'index'])->name('home');
    Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('task.store');
    Route::patch('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'update'])->name('task.update');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
