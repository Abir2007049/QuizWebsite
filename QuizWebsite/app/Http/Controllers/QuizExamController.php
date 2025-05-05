<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuizViolationMail;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class QuizExamController extends Controller
{
    // Show the quiz to the student
    public function takeQuiz(Request $request, $quiz_id)
    {
        // ✅ Get student_id securely from session
        $studentId = session('student_id');
        log::info('Student ID from session: ' . $studentId);
    
        // If not found, redirect
        if (!$studentId) {
            return redirect()->route('quiz.listStud')->with('error', 'Please enter the quiz room first.');
        }
    
        $quiz = Quiz::findOrFail($quiz_id);
        $startDatetime = Carbon::parse($quiz->start_datetime)->setTimezone('Asia/Dhaka');
        $duration = $quiz->duration;
        $finishDateTime = $startDatetime->copy()->addMinutes($duration);
        $current = Carbon::now('Asia/Dhaka');
    
        if ($current->lt($startDatetime)) {
            return back()->with('error', 'The quiz has not started yet.');
        }
    
        if ($current->gt($finishDateTime)) {
            return back()->with('error', 'The quiz has already ended.');
        }
    
        return view('student.questions', [
            'quiz' => $quiz,
            'duration' => $current->diffInSeconds($finishDateTime, false),
            'student_id' => $studentId, // optional: if your Blade view uses it
        ]);
    }
    


    public function startNow(Request $request, $id)
    {
        $current = Carbon::now('Asia/Dhaka');
        $quiz = Quiz::findOrFail($id);
        $totalDuration = $quiz->questions->sum('duration') / 60;
        
        $quiz->start_datetime = $current;
        $quiz->duration = $totalDuration;
        $quiz->save();
        
        return redirect()->back();
    }

    public function submitQuizAnswered(Request $request, $quiz)
{
    $studentId = Session::get('student_id'); // ✅ Get from session

    if (empty($studentId)) {
        return redirect()->back()->with('error', 'Student session expired or not found.');
    }

    $request->validate([
        'answers' => 'required|array',
    ]);

    $quizId = $quiz;
    $answers = $request->input('answers');
    $score = 0;

    $questions = Question::where('quiz_id', $quizId)->get();
    foreach ($questions as $question) {
        if (isset($answers[$question->id]) && $answers[$question->id] == $question->right_option) {
            $score++;
        }
    }

    $result = new Result();
    $result->student_id = $studentId;
    $result->quiz_id = $quizId;
    $result->score = $score;

    if ($result->save()) {
        \Log::info('Result saved successfully: ', $result->toArray());
        return view('student.finishmessage');
    } else {
        \Log::error('Failed to save result');
        return redirect()->back()->with('error', 'Failed to save result');
    }
}

}
