<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Question;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Quiz;




class BoardController extends Controller
{
    public function showboard($id)
    {
        // Fetch the results for the specific quiz (by quiz_id)
        // Eager load details and their questions for showing details inline
        $results = Result::with('details.question')
            ->where('quiz_id', $id)
            ->orderBy('score', 'desc')
            ->get();
         $quiz = Quiz::findOrFail($id);
        // Pass the results to the view
        return view('teacher.leaderboard', compact('quiz','results'));
    }

    //use App\Models\Result;

    public function performanceGraph($id)
    {
        $results = Result::where('quiz_id', $id)->get();

        // Get total number of questions in this quiz
        $totalQuestions = Question::where('quiz_id', $id)->count();
        if ($totalQuestions == 0) {
            return back()->with('error', 'No questions found for this quiz.');
        }

        // Initialize percentage-based buckets
        $buckets = [
            '100%' => 0,
            '>80%' => 0,
            '>70%' => 0,
            '>60%' => 0,
            '>40%' => 0,
            '<=40%' => 0,
        ];

        foreach ($results as $result) {
            $percentage = ($result->score / $totalQuestions) * 100;

            if ($percentage == 100) {
                $buckets['100%']++;
            } elseif ($percentage > 80) {
                $buckets['>80%']++;
            } elseif ($percentage > 70) {
                $buckets['>70%']++;
            } elseif ($percentage > 60) {
                $buckets['>60%']++;
            } elseif ($percentage > 40) {
                $buckets['>40%']++;
            } else {
                $buckets['<=40%']++;
            }
        }

        return view('teacher.quiz_performance', [
            'quizId' => $id,
            'buckets' => $buckets,
        ]);
    }



   public function export($id)
{   
    $quiz = Quiz::findOrFail($id);

    if (auth()->user()->id == $quiz->userid) {

        $results = Result::where('quiz_id', $id)
            ->orderBy('score', 'desc')
            ->get();

        // Add rank (sequential, no tie handling)
        $rank = 1;
        foreach ($results as $result) {
            $result->rank = $rank++;
        }

        $pdf = Pdf::loadView('teacher/resultpdf', compact('quiz', 'results'));
        return $pdf->download("quiz-{$quiz->id}-leaderboard.pdf");

    }
}
 public function clear(Quiz $quiz)
    {
        // delete all results of this quiz
        Result::where('quiz_id', $quiz->id)->delete();

        return redirect()->back()->with('success', '✅ All leaderboard records have been deleted.');
    }

}
