<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\User;


class QuizListController extends Controller
{
    //kkkkkk
   

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

public function showQuizListToStudents(Request $request)
    {
        // Validate the room name
        $request->validate([
            'room_name' => 'required|string',
        ]);

        // Find the teacher with the given room name
        $teacher = User::where('room_name', $request->room_name)->first();

        if (!$teacher) {
            return redirect()->back()->withErrors(['room_name' => 'Room not found. Please try again.']);
        }

        // Fetch the quizzes created by the teacher
        $quizzes = Quiz::where('userid', $teacher->id)->get();

        // Redirect to a view with the quizzes
        return view('QuizListForStudents', [
            'quizzes' => $quizzes,
            'teacher' => $teacher,
        ]);
    }
    public function takeQuiz($id)
{
    $quiz = Quiz::findOrFail($id);
    $questions = $quiz->questions; // Assuming a relationship exists

    return view('welcome');//('quizzes.take', compact('quiz', 'questions'));
}


    
}


