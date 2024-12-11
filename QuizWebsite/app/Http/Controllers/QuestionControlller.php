<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz; 

class QuestionControlller extends Controller
{
    /**
     * Store a new question associated with a quiz.
     */
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string|max:255',
            'options.*' => 'required|string|max:255',
            'correct_option' => 'required|in:1,2,3,4',
            'quiz_id' => 'required|exists:quizzes,id', // Validate that the quiz exists
        ]);

        // Save the question and associate it with the quiz
        $question = new Question();
        $question->text = $request->question_text;
        $question->option1 = $request->options[1];
        $question->option2 = $request->options[2];
        $question->option3 = $request->options[3];
        $question->option4 = $request->options[4];
        $question->right_option = $request->correct_option;
        $question->quiz_id = $request->quiz_id; // Associate the question with the quiz
        $question->save();

        return redirect()->back()->with('success', 'Question added successfully!');
    }

    /**
     * Store a new quiz.
     */
    // Inside your storeQuiz controller method
    public function storeQuiz(Request $request)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Save the quiz
            $quiz = new Quiz();
            $quiz->title = $request->quiz_title;
            $quiz->userid = Auth::id(); // Use the authenticated user's ID
            $quiz->save();
    
            // Store the quiz_id in the session
          //  session(['quiz_id' => $quiz->id]);
    
            return view('Teacher'); 
        } else {
            return redirect()->route('login')->with('error', 'You need to be logged in to create a quiz.');
        }
    }
    

}
