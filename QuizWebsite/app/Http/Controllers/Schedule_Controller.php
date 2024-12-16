<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use Carbon\Carbon;

class Schedule_Controller extends Controller{
public function schedule(Request $request, $quiz_id)
{
    // Validate the incoming data
    $request->validate([
        'start_datetime' => 'required|date',
        'duration' => 'required|integer|min:1',
    ]);

    // Find the quiz
    $quiz = Quiz::findOrFail($quiz_id);

    // Parse the start datetime (assuming it's in 'Y-m-d H:i' format)
   // $startDatetime = Carbon::createFromFormat('Y-m-d H:i', $request->start_datetime);

    // Store the start time and duration
    $quiz->start_datetime = $request->start_datetime;
    $quiz->duration = $request->duration;

    // Save the updated quiz
    $quiz->save();


    // Redirect back with success message
   // return redirect()->route('quiz.details', $quiz->id)
                  //   ->with('success', 'Quiz scheduled successfully!');
}

}
