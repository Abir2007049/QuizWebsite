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
            'answers.*.selected_option' => 'nullable|string', // nullable for skipped
        ]);

        $studentId = Session::get('student_id');

        if (!$studentId) {
            return back()->with('error', 'Student session expired or not found.');
        }

        $score = 0;
        $quiz_id = null;

        // ✅ Calculate the score
        foreach ($validated['answers'] as $answer) {
            $question = Question::find($answer['question_id']);

            if (!$question) {
                continue;
            }

            $quiz_id = $question->quiz_id;
            $selected = $answer['selected_option'];

            if ($selected === null || $selected === '') {
                // Skipped question – no penalty
                continue;
            }

            if ($question->right_option === $selected) {
                $score += 1;
            } else {
                $score -= 0.25;
            }
        }

        // ✅ Prevent negative score
        $score = max(0, $score);

        // ✅ Save result
        $result = Result::create([
            'student_id' => $studentId,
            'quiz_id' => $quiz_id,
            'score' => $score,
        ]);

        // ✅ Show finish message
        return view('student.finishmessage', compact('score'));
    }
}
 