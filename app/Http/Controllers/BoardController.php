<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;

class BoardController extends Controller
{
    public function showboard($id)
{
    // Fetch the results for the specific quiz (by quiz_id)
    $results = Result::where('quiz_id', $id)
                     ->orderBy('score', 'desc')
                     ->get();

    // Pass the results to the view
    return view('teacher.leaderboard', compact('results'));
}

}
