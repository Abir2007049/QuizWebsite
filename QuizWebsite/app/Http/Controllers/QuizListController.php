<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\User;
use Carbon\Carbon;



class QuizListController extends Controller
{
    //kkkkkk
   

public function showQuizList()
{
    if (Auth::check()) {
        // Fetch quizzes created by the logged-in teacher
        $quizzes = Quiz::where('userid', Auth::id())->get();

        // Pass the quizzes to the view
        return view('teacher\allquizes', compact('quizzes'));
    }

    // If not logged in, redirect to login page or show a message
    return redirect('/login')->with('error', 'Please log in to view your quizzes.');
}
public function showQuizDetails($id)
{
    // Fetch the quiz with its questions
    $quiz = Quiz::with('questions')->findOrFail($id);
    $quiz->start_datetime = Carbon::parse($quiz->start_datetime)->setTimezone(config('app.timezone'));

    // Check if the quiz belongs to the logged-in user
    if ($quiz->userid !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    // Pass the quiz and its questions to the view
    return view('teacher/EditQuiz', compact('quiz'));
}
public function enterRoom(Request $request)
{//pp
    // Validate the request
    $validated = $request->validate([
        'student_id' => 'required|string',
        'room_name' => 'required|string',
    ]);

    // Store the student_id and room_name in the session
    session(['student_id' => $validated['student_id']]);
    session(['room_name' => $validated['room_name']]);

    // Redirect to the quiz list page for the student with URL parameters
    return redirect()->route('quiz.listStud', [
        'student_id' => $validated['student_id'],
        'room_name' => $validated['room_name'],
    ]);
}





public function showQuizListToStudents(Request $request)
{
    // Retrieve the student ID and room name from session
    $studentId = session('student_id');
    $roomName = session('room_name');

    // Validate if the student ID and room name are available
    if (!$studentId || !$roomName) {
        return redirect()->back()->withErrors(['message' => 'Student ID or Room Name not found.']);
    }

    // Find the teacher associated with the given room name
    $teacher = User::where('room_name', $roomName)->first();

    if (!$teacher) {
        return redirect()->back()->withErrors(['room_name' => 'Room not found. Please try again.']);
    }

    // Fetch the quizzes created by the teacher
    $quizzes = Quiz::where('userid', $teacher->id)->get();

    // Format quiz start datetime correctly
    foreach ($quizzes as $quiz) {
        $quiz->start_datetime = Carbon::parse($quiz->start_datetime);
    }

    // Redirect to the view with the quizzes and student ID
    return view('student.allquizes', [
        'quizzes' => $quizzes,
        'teacher' => $teacher,
        'student_id' => $studentId,  // Pass student ID from session
    ]);
}



//     public function takeQuiz($id)
// {
//     $quiz = Quiz::findOrFail($id);
//     $questions = $quiz->questions; // Assuming a relationship exists

//     return view('welcome');//('quizzes.take', compact('quiz', 'questions'));
// }

public function destroy($id)
{
    $quiz = Quiz::findOrFail($id);  // Find quiz by ID or fail
    $quiz->delete();  // Delete the quiz

    return redirect()->route('quiz.list')->with('success', 'Quiz deleted successfully!');
}


    
}


