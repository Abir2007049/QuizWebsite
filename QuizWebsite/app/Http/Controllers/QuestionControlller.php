<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz;
use Carbon\Carbon;

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
            $quiz->start_datetime = Carbon::now();

            $quiz->duration =-1;
            $quiz->save();



            // Redirect to the Teacher view
            return redirect()->route('teacher.view')->with('success', 'New quiz has been created successfully!');


        } else {
            return redirect()->route('login')->with('error', 'You need to be logged in to create a quiz.');
        }
    }



    public function edittoupdate($id)
{
    $question = Question::findOrFail($id);
    return view('teacher/question_update', compact('question'));
}


public function update(Request $request, $id)
{
    $question = Question::findOrFail($id);

    $request->validate([
        'text' => 'required|string|max:255',
        'option1' => 'required|string|max:255',
        'option2' => 'required|string|max:255',
        'option3' => 'required|string|max:255',
        'option4' => 'required|string|max:255',
        'right_option' => 'required|integer|in:1,2,3,4',
        'duration' => 'required|numeric|min:1',
    ]);

    $question->update($request->only(['text', 'option1', 'option2', 'option3', 'option4', 'right_option', 'duration']));

    $quizid = $question->quiz_id;
    $quiz = Quiz::with('questions')->findOrFail($quizid);
    return view('teacher/EditQuiz', compact('quiz'));
    
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
             'correct_option' => 'required|integer|in:1,2,3,4', 
             'duration' => 'required|integer|min:1',
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
             'duration' => $request->duration,
         ]);
 
         // Redirect back to the quiz show page with a success message
         return redirect()->route('quiz.details', $quiz->id)->with('success', 'Question added successfully!');
 
 
     }
     public function destroyQuestion($id)
{
    $question = Question::findOrFail($id);
    $question->delete();

    return redirect()->route('quiz.details', $question->quiz_id)->with('success', 'Question deleted successfully!');
}

   
}
