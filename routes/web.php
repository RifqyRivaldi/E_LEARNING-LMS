<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\QuizTryout;
use App\Livewire\QuizPractice;
use App\Models\Quiz_Scores;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/do-quiztryout/{id}', QuizTryout::class)->name('do-quiztryout');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/do-quizpractice/{id}', Quizpractice::class)->name('do-quizpractice');
});

Route::get('/nilai', function () {
    $scores = Quiz_Scores::where('user_id', Auth::id())->with('quiz')->get();
    return view('livewire.nilai', compact('scores'));
})->name('nilai')->middleware('auth');

Route::get('/', function () {
    return view('welcome');
});


// Route untuk menghapus skor kuis
Route::delete('/quiz_scores/{id}', [QuizTryout::class, 'destroy'])->name('quiz_scores.destroy');
