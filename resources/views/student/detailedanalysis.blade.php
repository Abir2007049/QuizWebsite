@extends('layout')

@section('title', 'Detailed Quiz Result')

@section('content')
<div class="container py-5" style="background-color: #0f172a; min-height: 100vh; color: white;">

    <h1 class="mb-6 text-center text-4xl font-bold">📊 Detailed Quiz Result</h1>

    {{-- Summary --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 text-center bg-gray-800 p-6 rounded-lg shadow-lg">
        <div>
            <h2 class="text-lg font-semibold">Total Questions</h2>
            <p class="text-lime-400 text-xl">{{ $total }}</p>
        </div>
        <div>
            <h2 class="text-lg font-semibold">Attempted</h2>
            <p class="text-yellow-300 text-xl">{{ $attempted }}</p>
        </div>
        <div>
            <h2 class="text-lg font-semibold">Skipped</h2>
            <p class="text-gray-400 text-xl">{{ $skipped }}</p>
        </div>
        <div>
            <h2 class="text-lg font-semibold">Final Score</h2>
            <p class="text-blue-400 text-xl">{{ number_format($score, 2) }}</p>
        </div>
    </div>

    {{-- Per-question analysis --}}
    <h2 class="text-2xl font-semibold mb-4">📋 Per Question Analysis</h2>
    <div class="space-y-4">
        @foreach ($details as $index => $detail)
            @php
                if ($detail->selected_option == 0) {
                    $bgColor = 'bg-gray-700'; // skipped
                } elseif ($detail->is_correct) {
                    $bgColor = 'bg-green-800'; // correct
                } else {
                    $bgColor = 'bg-amber-700'; // wrong (soft amber)
                }
            @endphp

            <div class="p-4 rounded-lg {{ $bgColor }} shadow-md">
                {{-- Question Number --}}
                <p class="text-sm text-gray-300 mb-1">Question {{ $index + 1 }}</p>

                {{-- Question Text / Image --}}
                @if ($detail->question->text)
                    <p class="font-bold text-white mb-2">Q: {{ $detail->question->text }}</p>
                @elseif ($detail->question->image)
                    <img src="{{ asset('storage/' . $detail->question->image) }}" 
                         alt="Question Image" 
                         class="rounded-lg mb-2 max-w-md border border-gray-500">
                @else
                    <p class="italic text-gray-400 mb-2">No question content available.</p>
                @endif

                {{-- User's Answer --}}
                <p>
                    <strong>Your Answer:</strong>
                    @if ($detail->selected_option != 0)
                        @php
                            $selectedOptionText = $detail->question->{'option' . $detail->selected_option} ?? 'N/A';
                        @endphp
                        <span class="{{ $detail->is_correct ? 'text-green-300' : 'text-yellow-200' }} font-semibold">
                            {{ $selectedOptionText }}
                        </span>
                    @else
                        <span class="text-gray-300 italic">Skipped</span>
                    @endif
                </p>

                {{-- Correct Answer (if wrong or skipped) --}}
                @if (!$detail->is_correct)
                    <p>
                        <strong>Correct Answer:</strong>
                        @php
                            $correctOptionText = $detail->question->{'option' . $detail->question->right_option} ?? 'N/A';
                        @endphp
                        <span class="text-lime-300">{{ $correctOptionText }}</span>
                    </p>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
