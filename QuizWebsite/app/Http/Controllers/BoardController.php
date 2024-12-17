<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;

class BoardController extends Controller
{
    public function showboard(Request $request, $quizId)
    {
        $leaderboard = Result::where('quiz_id', $quizId)
            ->orderBy('score', 'desc') // Order by score in descending order
            ->limit(50) // Get top 10 scores
            ->get();

        // Return the leaderboard to the view (you can return a view or JSON based on your needs)
        return view('leaderboard', compact('leaderboard'));
    }
}
