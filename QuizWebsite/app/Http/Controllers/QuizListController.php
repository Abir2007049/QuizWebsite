<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\User;


class QuizListController extends Controller
{
    //
   

public function showQuizList()
{
    if (Auth::check()) {
        // Fetch quizzes created by the logged-in teacher
        $quizzes = Quiz::where('userid', Auth::id())->get();

        // Pass the quizzes to the view
        return view('QuizList', compact('quizzes'));
    }

    // If not logged in, redirect to login page or show a message
    return redirect('/login')->with('error', 'Please log in to view your quizzes.');
}
public function showQuizDetails($id)
{
    // Fetch the quiz with its questions
    $quiz = Quiz::with('questions')->findOrFail($id);

    // Check if the quiz belongs to the logged-in user
    if ($quiz->userid !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    // Pass the quiz and its questions to the view
    return view('ShowQuizToTeacher', compact('quiz'));
}
    
}

