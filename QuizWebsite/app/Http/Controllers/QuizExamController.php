<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use Carbon\Carbon;


use Illuminate\Support\Facades\Auth;

class QuizExamController extends Controller
{
    // Show the quiz to the student
    public function takeQuiz(Request $request, $quiz_id)
{
    $studentId = $request->query('student_id');

    $quiz = Quiz::findOrFail($quiz_id);

    // Parse and adjust times to Asia/Dhaka
    $startDatetime = \Carbon\Carbon::parse($quiz->start_datetime)->setTimezone('Asia/Dhaka');
    $duration = $quiz->duration;
    $finishDateTime = $startDatetime->copy()->addMinutes($duration);
    $current = Carbon::now('Asia/Dhaka');

    // Debugging (temporarily add this)
    // dd([
    //     'Start Time (Dhaka)' => $startDatetime,
    //     'Finish Time (Dhaka)' => $finishDateTime,
    //     'Current Time (Dhaka)' => $current,
    //     'Duration (Minutes)' => $duration,
    //     'Is Current Before Start?' => $current->lt($startDatetime),
    //     'Is Current After End?' => $current->gt($finishDateTime),
    // ]);

    // Ensure quiz is active
    
    if ($current->lt($startDatetime)) {
        return back()->with('error', 'The quiz has not started yet.');
    }

    if ($current->gt($finishDateTime)) {
        return back()->with('error', 'The quiz has already ended.');
    }

    return view('student.questions', [
        'quiz' => $quiz,
        'student_id' => $studentId,
        'duration' => $current->diffInSeconds($finishDateTime, false),
    ]);
}
public function startNow(Request $request, $id)
{
    // Get the current time in the desired timezone
    $current = Carbon::now('Asia/Dhaka');

    // Find the quiz by ID
    $quiz = Quiz::findOrFail($id); // Use $id as the parameter name is $id

    // Update the start_datetime with the current time
    $quiz->start_datetime = $current;

    // Save the updated quiz
    $quiz->save();

    // Redirect back with a success message
   
}



    // Handle quiz submission
    public function submitQuizAnswered(Request $request, $quiz, $student)
    {
       
        $quizId = $quiz;      // This is the quiz ID from the URL
        $studentId = $student;
    
        if (empty($studentId)) {
            return redirect()->back()->with('error', 'Student ID cannot be null');
        }
    
        // Process answers
        $answers = $request->input('answers');
        $score = 0;
    
        // Retrieve the correct options for the quiz
        $questions = Question::where('quiz_id', $quizId)->get();
        foreach ($questions as $question) {
            if (isset($answers[$question->id]) && $answers[$question->id] == $question->right_option) {
                $score++;
            }
        }
    
        // Insert the result into the database
        $result = new Result();
        $result->student_id = $studentId;
        $result->quiz_id = $quizId;
        $result->score = $score;
        $result->save();
        return view('student\finishmessage');
    
       // return redirect()->route('student.results', ['student_id' => $studentId])
                       //  ->with('success', 'Your result has been saved!');
    }
    
    

    
}
