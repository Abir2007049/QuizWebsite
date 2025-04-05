<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class ResultController extends Controller
{
    public function storeResult(Request $request)
    {
        // ✅ Get student_id from the session
        $studentId = Session::get('student_id');

        // Validate the incoming data (quiz_id and score only)
        $validated = $request->validate([
            'quiz_id' => 'required|integer',
            'score' => 'required|integer',
        ]);

        // Double-check if session value exists
        if (!$studentId) {
            return back()->with('error', 'Student session expired or not found.');
        }

        // Save the result
        $result = Result::create([
            'student_id' => $studentId,
            'quiz_id' => $validated['quiz_id'],
            'score' => $validated['score'],
        ]);

        if ($result) {
            return view('student.finishmessage');
        } else {
            return back()->with('error', 'Failed to submit the result. Please try again.');
        }
    }
}

