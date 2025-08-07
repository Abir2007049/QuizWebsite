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
        'answers.*.selected_option' => 'nullable|integer',
    ]);

    $studentId = Session::get('student_id');
    if (!$studentId) {
        return back()->with('error', 'Student session expired or not found.');
    }

    $score = 0;
    $quiz_id = null;
    $answersToSave = [];

    foreach ($validated['answers'] as $answer) {
        $question = Question::find($answer['question_id']);
        if (!$question) continue;

        $quiz_id = $question->quiz_id;
        $selected = $answer['selected_option'];

       $selected = (int) $answer['selected_option'];
        $rightOption = (int) $question->right_option;

if ($selected === 0) {
    // skipped, do nothing
} elseif ($rightOption === $selected) {
    $score += 1;
} else {
    $score -= 0.25;
}
        $answersToSave[] = [
            'question_id' => $question->id,
            'selected_option' => $selected,
        ];
    }

    $score = max(0, $score);

    $result = Result::create([
        'student_id' => $studentId,
        'quiz_id' => $quiz_id,
        'score' => $score,
    ]);

    foreach ($answersToSave as $data) {
        ResultDetail::create([
            'result_id' => $result->id,
            'question_id' => $data['question_id'],
            'selected_option' => $data['selected_option'],
        ]);
    }

    // ✅ For testing: print all result details created (with question)
    $details = ResultDetail::where('result_id', $result->id)
        ->with('question')
        ->get();

    // Return as JSON for testing/debugging:
   return view('student.detailedanalysis', [
        'details' => $details,
        'total' => count($answersToSave),
        'attempted' => collect($answersToSave)->where('selected_option', '!=', 0)->count(),
        'skipped' => collect($answersToSave)->where('selected_option', 0)->count(),
        'score' => $score,
    ]);
}



}
