<?php

use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::get('/profile', function () {
    return view('user.profile');
})->middleware('auth')->name('profile');
Route::get('/edit-profile', function () {
    return view('user.edit-profile');
});
Route::patch('/edit-profile/{id}', [RegisterUserController::class, 'update'])->name('edit-profile');
// Auth
Route::get('/register', [RegisterUserController::class, 'create']);
Route::post('/register', [RegisterUserController::class, 'store'])->name('register');

Route::get('/login', [SessionController::class, 'create']);
Route::post('/login', [SessionController::class, 'store']);

Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');;
