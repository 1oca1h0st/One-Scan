<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::prefix('/project')->middleware(['auth'])->namespace('Project')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('project');
});

Route::prefix('/tools')->middleware(['auth'])->namespace('Tools')->group(function () {

});

Route::prefix('/search')->middleware(['auth'])->namespace('Search')->group(function () {

});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [UserController::class, 'authenticate'])->name('loginUser');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [UserController::class, 'createUser'])->name('createUser');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');
