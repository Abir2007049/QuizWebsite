<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use Carbon\Carbon;

class QuizExamController extends Controller
{
    // ✅ Show the quiz to the student
    public function takeQuiz(Request $request, $quiz_id)
    {
        $studentId = session('student_id');
        Log::info('Student ID from session: ' . $studentId);

        if (!$studentId) {
            return redirect()->route('quiz.listStud')->with('error', 'Please enter the quiz room first.');
        }

        $quiz = Quiz::with('questions')->findOrFail($quiz_id);

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

        // ✅ Prepare questions array for frontend
        $questions = $quiz->questions->map(function ($q) {
            return [
                'image' => $q->image
    ? asset('storage/' . ltrim($q->image, '/'))
    : null,




                'option1' => $q->option1,
                'option2' => $q->option2,
                'option3' => $q->option3,
                'option4' => $q->option4,
                'right_option' => $q->right_option,
                'duration' => (int) $q->duration, // ✅ force int for JS
            ];
        });

        return view('student.questions', [
            'quiz' => $quiz,
            'duration' => $current->diffInSeconds($finishDateTime, false),
            'student_id' => $studentId,
            'questions' => $questions,
        ]);
    }

    // ✅ Set start time to now and calculate total quiz time from question durations
    public function startNow(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $totalDuration = $quiz->questions->sum('duration') / 60;

        $quiz->start_datetime = Carbon::now('Asia/Dhaka');
        $quiz->duration = $totalDuration;
        $quiz->save();

        return redirect()->back()->with('success', 'Quiz started now.');
    }

    // ✅ Store submitted answers and score
    public function submitQuizAnswered(Request $request, $quiz)
{
    $studentId = session('student_id');

    if (!$studentId) {
        return redirect()->back()->with('error', 'Student session expired or not found.');
    }

    $request->validate([
        'score' => 'required|integer',
    ]);

    $result = new Result();
    $result->student_id = $studentId;
    $result->quiz_id = $quiz;
    $result->score = $request->score;

    if ($result->save()) {
        \Log::info('Result saved successfully: ', $result->toArray());
        return view('student.finishmessage');
    } else {
        \Log::error('Failed to save result');
        return redirect()->back()->with('error', 'Failed to save result.');
    }
}

}
