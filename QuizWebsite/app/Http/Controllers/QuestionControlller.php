<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz;

class QuestionControlller extends Controller
{
   
   

    /**
     * Store a new quiz.
     */
    public function storeQuiz(Request $request)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Save the quiz
            $quiz = new Quiz();
            $quiz->title = $request->quiz_title;
            $quiz->userid = Auth::id(); // Use the authenticated user's ID
            $quiz->start_datetime = now();
            $quiz->duration =60;
            $quiz->save();



            // Redirect to the Teacher view
            return redirect()->route('teacher.view')->with('success', 'New quiz has been created successfully!');


        } else {
            return redirect()->route('login')->with('error', 'You need to be logged in to create a quiz.');
        }
    }

    /**
     * Add a new question to the quiz.
     */

     public function add(Request $request)
     {
         // Validate the incoming request data
         $request->validate([
             'quiz_id' => 'required|exists:quizzes,id', // Ensure the quiz exists
             'question_text' => 'required|string|max:255', // Validate the question text
             'options' => 'required|array|min:4|max:4', // Options must be an array of 4 items
             'options.*' => 'required|string|max:255', // Each option must be a string
             'correct_option' => 'required|integer|in:1,2,3,4', // Correct option must be one of 1, 2, 3, or 4
         ]);
 
         // Find the quiz by its ID
         $quiz = Quiz::findOrFail($request->quiz_id);
 
         // Create the new question and associate it with the quiz
         $quiz->questions()->create([
             'text' => $request->question_text,
             'option1' => $request->options[1],
             'option2' => $request->options[2],
             'option3' => $request->options[3],
             'option4' => $request->options[4],
             'right_option' => $request->correct_option,
         ]);
 
         // Redirect back to the quiz show page with a success message
         return redirect()->route('quiz.details', $quiz->id)->with('success', 'Question added successfully!');
 
 
     }
   
}
