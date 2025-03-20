<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TriviaController;

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

Route::get('/trivia', [TriviaController::class, 'index'])->name('trivia.index');
Route::get('/trivia/question', [TriviaController::class, 'getQuestion'])->name('trivia.question');
Route::post('/trivia/check', [TriviaController::class, 'checkAnswer'])->name('trivia.check');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_middleware'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
}); 