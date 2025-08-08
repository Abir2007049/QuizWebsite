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
            <p class="text-blue-300 text-xl">{{ $skipped }}</p>
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
                $selectedOption = (int) $detail->selected_option;
                $correctOption = (int) $detail->question->right_option;

                // Determine background color for the whole box
                if ($selectedOption === 0) {
                    $bgColor = 'bg-blue-900'; // Skipped
                } elseif ($selectedOption === $correctOption) {
                    $bgColor = 'bg-green-900'; // Correct
                } else {
                    $bgColor = 'bg-red-900'; // Wrong
                }

                // Extract text versions of selected and correct options
                $selectedText = $selectedOption !== 0
                    ? ($detail->question->{'option' . $selectedOption} ?? 'N/A')
                    : null;

                $correctText = $detail->question->{'option' . $correctOption} ?? 'N/A';
            @endphp

            <div class="p-4 rounded-lg shadow-md {{ $bgColor }}">
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
                    @if ($selectedText)
                        <span class="font-semibold text-white">{{ $selectedText }}</span>
                    @else
                        <span class="text-gray-300 italic">Skipped</span>
                    @endif
                </p>

                {{-- Correct Answer (if wrong or skipped) --}}
                @if ($selectedOption !== $correctOption)
                    <p>
                        <strong>Correct Answer:</strong>
                        <span class="text-lime-300">{{ $correctText }}</span>
                    </p>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
