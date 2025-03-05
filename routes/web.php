<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\QuizTryout;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/do-quiztryout/{id}', QuizTryout::class)->name('do-quiztryout');
});

Route::get('/', function () {
    return view('welcome');
});