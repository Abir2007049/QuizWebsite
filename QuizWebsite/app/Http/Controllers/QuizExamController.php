<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;

use Illuminate\Support\Facades\Auth;

class QuizExamController extends Controller
{
    // Show the quiz to the student
    public function takeQuiz(Request $request, $quiz_id)
    {
        $studentId = $request->query('student_id');
    
        // Fetch the quiz details
        $quiz = Quiz::findOrFail($quiz_id);
    
        // Render the quiz view with the student ID
        if (!$quiz) {
            // Handle quiz not found (return a 404 page, redirect to a different page, etc.)
            abort(404);
        }
        return view('QuizExam', [
            'quiz' => $quiz,
            'student_id' => $studentId,
        ]);
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
    
       // return redirect()->route('student.results', ['student_id' => $studentId])
                       //  ->with('success', 'Your result has been saved!');
    }
    
    

    
}
