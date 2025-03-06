<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\QuizTryout;
use App\Livewire\QuizPractice;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/do-quiztryout/{id}', QuizTryout::class)->name('do-quiztryout');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/do-quizpractice/{id}', Quizpractice::class)->name('do-quizpractice');
});

Route::get('/', function () {
    return view('welcome');
});