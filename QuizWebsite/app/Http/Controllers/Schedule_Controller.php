<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;

class Schedule_Controller extends Controller{
public function schedule(Request $request, $quiz_id)
{
    // Validate the incoming data
    $request->validate([
        'start_datetime' => 'required|date',
        'duration' => 'required|integer|min:1',
    ]);

    // Find the quiz by ID
    $quiz = Quiz::findOrFail($quiz_id);

    // Save the scheduled start time and duration
    $quiz->start_datetime = $request->start_datetime;
    $quiz->duration = $request->duration; // Store the duration in minutes
    $quiz->save();

    // Redirect back with success message
   // return redirect()->route('quiz.details', $quiz->id)
                  //   ->with('success', 'Quiz scheduled successfully!');
}

}
