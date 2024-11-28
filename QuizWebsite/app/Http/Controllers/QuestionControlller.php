<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question; // Add this import at the top


class QuestionControlller extends Controller
{
    //
    public function StoreQuestion(Request $request)
    {
        // Validate the input
        $request->validate([
            'quiz_title' => 'required|string|max:255',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.options.*' => 'required|string|max:255',
            'questions.*.correct_option' => 'required|in:1,2,3,4',
        ]);
    
        // // Create a new Quiz
        // $quiz = new Quiz();
        // $quiz->title = $request->quiz_title;
        // $quiz->save();
    
        // Store questions and options
        foreach ($request->questions as $questionData) {
            $question = new Question();
           // $question->quiz_id = $quiz->id; // Assuming each question belongs to a quiz
           // $question->text = $questionData['text'];
            $question->option1 = $questionData['options'][1];
            $question->option2 = $questionData['options'][2];
            $question->option3 = $questionData['options'][3];
            $question->option4 = $questionData['options'][4];
            $question->right_option = $questionData['correct_option'];
            $question->save();
        }
    
        return view('Teacher');//redirect()->route('quizList')->with('success', 'Quiz created successfully!');
    }
    
    
}
