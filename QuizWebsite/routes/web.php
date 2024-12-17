<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\QuestionControlller;
use App\Http\Controllers\Schedule_Controller;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\QuizExamController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/TorS', [RoleController::class, 'TorS'])->name('TorS');
Route::post('/questions/add', [QuestionControlller::class, 'add'])->name('questions.add');

Route::get('/TorS', [RoleController::class, 'TorS'])->name('TorS');


Route::get('/registration',  [AuthManager::class, 'registration'])->name('registration');

Route::post('/registration.post', [AuthManager::class, 'registrationPost'])->name('registration.post');
Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');
Route::post('/store-question', [QuestionControlller::class, 'storeQuestion'])->name('storeQuestion');
Route::post('/store-quiz', [QuestionControlller::class, 'storeQuiz'])->name('storeQuiz');
Route::get('/login', function () {
    return view('Teacher');
})->name('login');

Route::get('/teacher', function () {
    return view('Teacher');
})->name('teacher.view');

Route::get('/registration.post', function () {
    return redirect()->route('registration');
});
Route::get('/store-quiz', function () {
    return redirect()->back()->withErrors(['error' => 'Invalid request method. Use the form to submit.']);
});

//quiz list cont + result cont
Route::post('/submit-student', [QuestionControlller::class, 'submitStudent'])->name('student.submit');
Route::get('/quiz-list', [App\Http\Controllers\QuizListController::class, 'showQuizList'])->name('quiz.list');
Route::get('/quiz/{id}/details', [App\Http\Controllers\QuizListController::class, 'showQuizDetails'])->name('quiz.details');
Route::get('/quiz/{id}/leaderboard', [App\Http\Controllers\BoardController::class, 'showboard'])->name('quiz.leaderboard');


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/TorS?role=teacher'); 
})->name('logout');
Route::post('/enter-room', [App\Http\Controllers\QuizListController::class, 'enterRoom'])->name('enter.room');
Route::get('/quiz-listStud', [App\Http\Controllers\QuizListController::class, 'showQuizListToStudents'])->name('quiz.listStud');
Route::get('/quiz/{id}/take', [QuizExamController::class, 'takeQuiz'])->name('quiz.take');
Route::post('/quiz/{quiz}/submit/{student}', [QuizExamController::class, 'submitQuizAnswered'])->name('quiz.submit');


Route::post('/store-result', [App\Http\Controllers\ResultController::class, 'storeResult'])->name('result.store');
Route::get('/student/results/{student_id}', [ResultController::class, 'showResult'])->name('student.results');



//Dhruba//
//Dhruba//



//Route::get('/quiz-list/quiz/{id}', [App\Http\Controllers\QuizListController::class, 'showQuizDetails'])->name('quiz.details');
Route::post('quiz/{id}/schedule', [Schedule_Controller::class, 'schedule'])->name('quiz.schedule');

