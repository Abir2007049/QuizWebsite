<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Store the result submitted by the student.
     */
    public function storeResult($studentId, $quizId, $score)
    {
        // Save the result to the database
        $result = Result::create([
            'student_id' => $studentId,
            'quiz_id' => $quizId,
            'score' => $score,
        ]);

        // Optionally return some response or redirect
        return true;
    }

    /**
     * Show the result for the student.
     */
    public function showResult($student_id)
    {
        // Fetch the results for the given student_id
        $results = Result::where('student_id', $student_id)->get();

        // Pass the results to the view
        return view('result.show', compact('results'));
    }
}
