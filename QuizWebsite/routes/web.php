<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthManager;

use App\Http\Controllers\QuestionControlller;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/TorS', [RoleController::class, 'TorS'])->name('TorS');

Route::get('/registration',  [AuthManager::class, 'registration'])->name('registration');
Route::post('/registration.post', [AuthManager::class, 'registrationPost'])->name('registration.post');

Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');


Route::post('/store-question', [QuestionControlller::class, 'storeQuestion'])->name('storeQuestion');
Route::post('/store-quiz', [QuestionControlller::class, 'storeQuiz'])->name('storeQuiz');
//Route::get('/login', [RoleController::class, 'TorS'])->name('login');
Route::get('/login', function () {
    return view('Teacher');
})->name('login');
Route::get('/registration.post', function () {
    return redirect()->route('registration');
});

//Route::get('/student-form', [AuthManager::class, 'studentForm'])->name('student.form');

Route::post('/submit-student', [QuestionControlller::class, 'submitStudent'])->name('student.submit');
Route::get('/quiz-list', [App\Http\Controllers\QuizListController::class, 'showQuizList'])->name('quiz.list');


Route::get('/quiz/{id}', [App\Http\Controllers\QuizListController::class, 'showQuizDetails'])->name('quiz.details');
