<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\QuestionControlller;
use App\Http\Controllers\Schedule_Controller;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\QuizExamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;



/////////////////////////
Route::get('/', function () {
    return view('welcome');
});

Route::get('/TorS', [RoleController::class, 'TorS'])->name('TorS');
//////////////////



///////////////
Route::get('/registration',  [AuthManager::class, 'registration'])->name('registration');
Route::post('/registration.post', [AuthManager::class, 'registrationPost'])->name('registration.post');
Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');
Route::get('/registration.post', function () {
    return redirect()->route('registration');
});

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/TorS?role=teacher'); 
})->name('logout');
//////////////////


Route::post('/questions/add', [QuestionControlller::class, 'add'])->name('questions.add');
Route::delete('/questions/{id}', [QuestionControlller::class, 'destroyQuestion'])->name('questions.delete');
Route::post('/store-quiz', [QuestionControlller::class, 'storeQuiz'])->name('storeQuiz');
Route::post('/submit-student', [QuestionControlller::class, 'submitStudent'])->name('student.submit');
Route::get('/store-quiz', function () {
    return redirect()->back()->withErrors(['error' => 'Invalid request method. Use the form to submit.']);
});

///////////////////////

Route::get('/teacher', function () {
    return view('teacher\addquiz');
})->name('teacher.view');

//////////////



Route::get('/quiz-list', [App\Http\Controllers\QuizListController::class, 'showQuizList'])->name('quiz.list');
Route::get('/quiz/{id}/details', [App\Http\Controllers\QuizListController::class, 'showQuizDetails'])->name('quiz.details');
Route::post('/enter-room', [App\Http\Controllers\QuizListController::class, 'enterRoom'])->name('enter.room');
Route::get('/quiz-listStud', [App\Http\Controllers\QuizListController::class, 'showQuizListToStudents'])->name('quiz.listStud');
Route::delete('/quiz/{id}', [App\Http\Controllers\QuizListController::class, 'destroy'])->name('quiz.destroy');



//////////////////////
Route::get('/quiz/{id}/leaderboard', [App\Http\Controllers\BoardController::class, 'showboard'])->name('quiz.leaderboard');
/////////////////////


Route::get('/quiz/{id}/take', [QuizExamController::class, 'takeQuiz'])->name('quiz.take');
Route::post('/quiz/{quiz}/submit/{student}', [QuizExamController::class, 'submitQuizAnswered'])->name('quiz.submit');
Route::post('/quiz/startnow/{id}', [QuizExamController::class, 'startNow'])->name('quiz.startnow');
Route::post('/quiz/violation', [QuizExamController::class, 'sendViolationEmail']);


/////////////////////////
Route::post('/store-result', [App\Http\Controllers\ResultController::class, 'storeResult'])->name('result.store');
Route::get('/student/results/{student_id}', [ResultController::class, 'showResult'])->name('student.results');
////////////////


Route::post('quiz/{id}/schedule', [Schedule_Controller::class, 'schedule'])->name('quiz.schedule');

///////////////////


Route::get('/questions/edit/{id}', [QuestionControlller::class, 'edittoupdate'])->name('questions.edit');
Route::put('/questions/update/{id}', [QuestionControlller::class, 'update'])->name('questions.update');

Route::post('/report-tab-switch', function (Request $request) {
    // Get the currently authenticated user (assumed to be a student)
  //  $student = Auth::user(); // or Auth::id() for just the ID

    if ($request->state === 'hidden') {
        $quiz = Quiz::with('teacher')->find($request->quiz_id);

        if ($quiz && $quiz->teacher) {
            $teacherEmail = $quiz->teacher->email;
            $studentId = session('student_id');

            // Send email using Brevo SMTP
            Mail::raw("Student with ID {$studentId} switched tabs on quiz '{$quiz->title}'", function ($message) use ($teacherEmail) {
                $message->to($teacherEmail)
                        ->subject('Tab Switch Alert')
                        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')); // Ensure from address is set
            });

            return response()->json(['status' => 'Email sent.']);
        } else {
            Log::warning('Quiz or teacher not found.');
        }
    }

    return response()->json(['status' => 'Logged but no email sent.']);
});


///////////

Route::get('/api/quiz/{id}/timing', function ($id) {
    $quiz = \App\Models\Quiz::findOrFail($id);
    $start = \Carbon\Carbon::parse($quiz->start_datetime)->setTimezone('Asia/Dhaka');
    $end = $start->copy()->addMinutes($quiz->duration);

    return response()->json([
        'start_datetime' => $start->toIso8601String(),
        'end_datetime' => $end->toIso8601String(),
    ]);
});



//////////////

