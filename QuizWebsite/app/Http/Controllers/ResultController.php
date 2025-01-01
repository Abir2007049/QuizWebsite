<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function storeResult(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'quiz_id' => 'required|integer',
            'score' => 'required|integer',
        ]);
        \Log::info('Validated Data:', $validated);
    
        // Save the result
        $result = Result::create([
            'student_id' => $validated['student_id'],
            'quiz_id' => $validated['quiz_id'],
            'score' => $validated['score'],
        ]);
    
        if ($result) {
            return view('student.finishmessage');
        } else {
            return back()->with('error', 'Failed to submit the result. Please try again.');
        }
    }
    

    public function showResult($student_id)
    {
        // Fetch the results for the student
        $results = Result::where('student_id', $student_id)->get();
        return view('results.show', compact('results'));
    }
}
