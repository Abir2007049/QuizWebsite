<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

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
    try {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_type' => 'required|in:text,image',
            'question_text' => 'nullable|required_if:question_type,text|string|max:255',
            // Phone cameras often upload HEIC/HEIF; allow those too.
            'question_image' => 'nullable|required_if:question_type,image|file|mimetypes:image/jpeg,image/png,image/jpg,image/webp,image/heic,image/heif|max:8192',
            'options' => 'required|array|min:2|max:4',
            'options.*' => 'required|string|max:255',
            'correct_option' => 'required|integer',
            'duration' => 'required|integer|min:1',
        ]);

        $quiz = Quiz::findOrFail($validated['quiz_id']);

        $options = array_pad($validated['options'], 4, null); // fill missing options with null

        $questionData = [
            'option1' => $options[0],
            'option2' => $options[1],
            'option3' => $options[2],
            'option4' => $options[3],
            'right_option' => $validated['correct_option'],
            'duration' => $validated['duration'],
        ];

        if ($validated['question_type'] === 'text') {
            $questionData['text'] = $validated['question_text'];
            $questionData['image'] = null;
        } elseif ($validated['question_type'] === 'image') {
            if (!$request->hasFile('question_image')) {
                return response()->json(['message' => 'Please select an image file.'], 422);
            }

            $path = $request->file('question_image')->store('questions', 'public');
            $questionData['image'] = $path;
            $questionData['text'] = null;
        }

        $question = $quiz->questions()->create($questionData);

        return response()->json($question);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    }
}

   public function destroyQuestion($id)
{
    $question = Question::find($id);

    if (!$question) {
        return response()->json(['success' => false, 'message' => 'Question not found'], 404);
    }

    $question->delete();

    return response()->json(['success' => true, 'message' => 'Deleted successfully']);
}

public function submitStudent(Request $request)
{
    $validated = $request->validate([
        'student_id' => 'required|string',
        'room_name' => 'required|string',
    ]);

    session([
        'student_id' => $validated['student_id'],
        'room_name' => $validated['room_name'],
    ]);

    return redirect()->route('quiz.listStud');
}



   
}
