<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\ResultDetail;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ResultController extends Controller
{
    public function storeResult(Request $request)
{
    $validated = $request->validate([
        'answers' => 'required|array',
        'answers.*.question_id' => 'required|integer|exists:questions,id',
        'answers.*.selected_option' => 'nullable|string',
    ]);

    $studentId = Session::get('student_id');
    if (!$studentId) {
        return back()->with('error', 'Student session expired or not found.');
    }

    $score = 0;
    $quiz_id = null;

    // Prepare array to hold answers with correctness for batch save
    $answersToSave = [];

    foreach ($validated['answers'] as $answer) {
        $question = Question::find($answer['question_id']);
        if (!$question) continue;

        $quiz_id = $question->quiz_id;
        $selected = $answer['selected_option'];

        // Determine correctness
        if ($selected === null || $selected === '') {
            $isCorrect = false; // skipped
            // Optionally continue here if you don't want to save skipped answers
        } elseif ($question->right_option === $selected) {
            $score += 1;
            $isCorrect = true;
        } else {
            $score -= 0.25;
            $isCorrect = false;
        }

        $answersToSave[] = [
            'question_id' => $question->id,
            'selected_option' => $selected,
            'is_correct' => $isCorrect,
        ];
    }

    $score = max(0, $score);

    $result = Result::create([
        'student_id' => $studentId,
        'quiz_id' => $quiz_id,
        'score' => $score,
    ]);

    // Now save each answer with the computed 'is_correct'
    foreach ($answersToSave as $data) {
        ResultDetail::create([
            'result_id' => $result->id,
            'question_id' => $data['question_id'],
            'selected_option' => $data['selected_option'],
            'is_correct' => $data['is_correct'],
        ]);
    }

    // Return the result detail view (you can customize)
    return view('result_detail', [
        'details' => $result->details()->with('question')->get(),
        'total' => count($answersToSave),
        'attempted' => collect($answersToSave)->filter(fn($a) => $a['selected_option'] !== null && $a['selected_option'] !== '')->count(),
        'correct' => collect($answersToSave)->where('is_correct', true)->count(),
        'wrong' => collect($answersToSave)->where('is_correct', false)->count(),
        'skipped' => collect($answersToSave)->filter(fn($a) => $a['selected_option'] === null || $a['selected_option'] === '')->count(),
        'score' => $score,
    ]);
}

}
