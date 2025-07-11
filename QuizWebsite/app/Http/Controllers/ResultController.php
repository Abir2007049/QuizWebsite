<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ResultController extends Controller
{
    public function storeResult(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
           
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer|exists:questions,id',
            'answers.*.selected_option' => 'required|string',
        ]);

        $studentId = Session::get('student_id');

        

        if (!$studentId) {
            return back()->with('error', 'Student session expired or not found.');
        }

        $score = 0;

        $quiz_id;

        // ✅ Calculate the score
        foreach ($validated['answers'] as $answer) {
            $question = Question::find($answer['question_id']);
            $quiz_id=$question->quiz_id;

            if ($question && $question->right_option === $answer['selected_option']) {
                $score++;
            }
        }
         

        // ✅ Save result
        $result = Result::create([
            'student_id' => $studentId,
            'quiz_id' => $quiz_id,
            'score' => $score,
        ]);

       

        return view('student.finishmessage', compact('score'));
    }
}

