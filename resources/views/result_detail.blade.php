@extends('layout')

@section('title', 'Detailed Quiz Result')

@section('content')
<div class="container py-5 text-white" style="background-color: #0f172a; min-height: 100vh;">
    <div class="text-center mb-5">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-6">📊 Detailed Quiz Result</h1>
        <p class="text-sm text-gray-300 mt-2">Review your performance and improve!</p>
    </div>

    <div class="bg-gray-800 p-6 rounded-xl shadow-lg mb-6">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center">
            <div>
                <h2 class="text-xl font-bold text-white">Total Questions</h2>
                <p class="text-lime-400 text-lg">{{ $total }}</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">Attempted</h2>
                <p class="text-yellow-300 text-lg">{{ $attempted }}</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">Correct</h2>
                <p class="text-green-400 text-lg">{{ $correct }}</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">Wrong</h2>
                <p class="text-red-400 text-lg">{{ $wrong }}</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">Skipped</h2>
                <p class="text-gray-400 text-lg">{{ $skipped }}</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">Final Score</h2>
                <p class="text-blue-300 text-lg">{{ $score }}</p>
            </div>
        </div>
    </div>

    <h2 class="text-2xl text-white font-semibold mb-4">📋 Per-Question Feedback</h2>
    <div class="space-y-4">
        @foreach($details as $detail)
            <div class="p-4 rounded-lg @if($detail->is_correct) bg-green-800 @elseif($detail->selected_option) bg-red-800 @else bg-gray-700 @endif">
                <p class="font-bold text-white mb-2">
                    Q: {{ $detail->question->question_text }}
                </p>
                <p class="text-white">
                    Your Answer:
                    @if($detail->selected_option)
                        <span class="@if($detail->is_correct) text-green-300 @else text-red-300 @endif font-semibold">
                            {{ $detail->selected_option }}
                        </span>
                    @else
                        <span class="text-gray-300 italic">Skipped</span>
                    @endif
                </p>
                @unless($detail->is_correct)
                <p class="text-lime-300">
                    Correct Answer: <strong>{{ $detail->question->right_option }}</strong>
                </p>
                @endunless
            </div>
        @endforeach
    </div>
</div>
@endsection
