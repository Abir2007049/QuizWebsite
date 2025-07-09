<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use Carbon\Carbon;
use App\Events\QuizStatusUpdated;
use Illuminate\Support\Facades\Auth;


class Schedule_Controller extends Controller {
    public function schedule(Request $request, $quiz_id)
{
    // Validate the incoming data
    $request->validate([
       'start_datetime' => 'nullable|date',
      // 'duration' => 'nullable|integer|min:1',

    ]);

    // Find the quiz
    $quiz = Quiz::findOrFail($quiz_id);

    // Store the start time as local time
    $startDatetime = Carbon::createFromFormat('Y-m-d H:i', $request->start_datetime, config('app.timezone'));

    // Save start time and duration
    $quiz->start_datetime = $startDatetime;
    $totalDuration = $quiz->questions->sum('duration') / 60;
    $quiz->duration=$totalDuration;

      event(new QuizStatusUpdated($quiz_id, Auth::user()->room_name, $startDatetime));

    // Save the quiz
    $quiz->save();

    // Redirect back with success message
    return redirect()->route('quiz.list');
       
  }

}

