<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;

class BoardController extends Controller
{
    public function showboard(Request $request, $quizId)
    {
        $leaderboard = Result::where('quiz_id', $quizId)
            ->orderBy('score', 'desc') 
            ->limit(50)
            ->get();

     
        return view('teacher\leaderboard', compact('leaderboard'));
    }
}
