@extends('layout')

@section('custom_header')
<header class="bg-gray-900 shadow z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-indigo-400 tracking-tight">📘 EduHub</h1>
        <nav class="space-x-6 text-gray-300 font-medium">
            <a href="{{ url('/') }}" class="hover:text-indigo-400 transition">Home</a>
            <a href="{{ route('quiz.listStud') }}" class="hover:text-indigo-400 transition">Quizzes</a>
        </nav>
    </div>
</header>
@endsection

@section('title', 'Leaderboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4 py-12 text-white relative overflow-hidden">

    <!-- Background blurry shapes -->
    <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-indigo-700 opacity-20 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-[400px] h-[400px] bg-purple-600 opacity-20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto space-y-8 relative z-10">

        <h2 class="text-4xl font-extrabold leading-tight mb-6 text-white">
            Leaderboard for Quiz: <span class="text-indigo-400">{{ $quiz->title }}</span>
        </h2>

        <a href="{{ url('/leaderboard/export/' . $quiz->id) }}" 
           class="inline-block px-4 py-2 bg-indigo-600 rounded text-white hover:bg-indigo-700 transition">
           ⬇ Export PDF
        </a>

        <div class="bg-gray-900 bg-opacity-80 backdrop-blur-md border border-indigo-700 rounded-3xl shadow-2xl p-6 overflow-x-auto mt-4">

            <table class="min-w-full table-auto border-collapse border border-indigo-700 text-white">
                <thead>
                    <tr class="bg-indigo-600 font-semibold tracking-wide">
                        <th class="border border-indigo-600 px-6 py-4 text-left text-lg rounded-tl-3xl">Rank</th>
                        <th class="border border-indigo-600 px-6 py-4 text-left text-lg">Student ID</th>
                        <th class="border border-indigo-600 px-6 py-4 text-left text-lg">Score</th>
                        <th class="border border-indigo-600 px-6 py-4 text-left text-lg rounded-tr-3xl">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $entry)
                        <tr 
                            @class([
                                'bg-gray-800 bg-opacity-30' => $index % 2 == 0,
                                'bg-gray-900 bg-opacity-20' => $index % 2 != 0,
                                'hover:bg-purple-700/40 transition-colors duration-300 cursor-pointer'
                            ])
                            data-detail-target="{{ $entry->id }}"
                            role="button"
                            tabindex="0"
                        >
                            <td class="border border-indigo-600 px-6 py-3 font-bold text-lg text-purple-300 select-none">{{ $index + 1 }}</td>
                            <td class="border border-indigo-600 px-6 py-3 font-semibold select-none">{{ $entry->student_id }}</td>
                            <td class="border border-indigo-600 px-6 py-3 font-bold text-green-400 select-none">{{ $entry->score }}</td>
                            <td class="border border-indigo-600 px-6 py-3">
                                <button 
                                    type="button"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition"
                                    data-toggle-detail="{{ $entry->id }}"
                                    aria-expanded="false"
                                    aria-controls="details-{{ $entry->id }}"
                                >
                                    View Details
                                </button>
                            </td>
                        </tr>

                        <tr id="details-{{ $entry->id }}" class="hidden bg-gray-900 bg-opacity-70" aria-hidden="true">
                            <td colspan="4" class="p-4 text-sm text-gray-300">
                                <strong>Details for student {{ $entry->student_id }}:</strong>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach($entry->details as $detail)
                                        <li class="mb-2">
                                            <span class="font-semibold">Question ID:</span> {{ $detail->question_id }}<br>
                                            <span class="font-semibold">Selected Option:</span> {{ $detail->selected_option ?? 'Skipped' }}<br>
                                            <span class="font-semibold">Correct:</span>
                                            <span class="{{ $detail->is_correct ? 'text-green-400' : 'text-red-500' }}">
                                                {{ $detail->is_correct ? 'Yes' : 'No' }}
                                            </span><br>
                                            <span class="font-semibold">Question Text:</span> {{ $detail->question->question_text ?? 'N/A' }}
                                        </li>
                                        <hr class="border-gray-700" />
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        {{-- Pagination placeholder --}}
        <div class="flex justify-center space-x-6 mt-8">
            <a href="#" class="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold shadow-lg transition-transform hover:scale-105">
                Previous
            </a>
            <a href="#" class="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold shadow-lg transition-transform hover:scale-105">
                Next
            </a>
        </div>

    </div>
</div>
@endsection

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle details on row or button click
        document.querySelectorAll('[data-detail-target]').forEach(row => {
            row.addEventListener('click', () => {
                const id = row.getAttribute('data-detail-target');
                const detailsRow = document.getElementById('details-' + id);
                if (detailsRow) {
                    const isHidden = detailsRow.classList.toggle('hidden');
                    // Update aria-expanded on button
                    const btn = row.querySelector(`[data-toggle-detail="${id}"]`);
                    if (btn) {
                        btn.setAttribute('aria-expanded', !isHidden);
                    }
                    // Update aria-hidden on details row
                    detailsRow.setAttribute('aria-hidden', isHidden);
                }
            });
        });

        // Prevent button clicks from triggering row click twice
        document.querySelectorAll('[data-toggle-detail]').forEach(button => {
            button.addEventListener('click', event => {
                event.stopPropagation();
                const id = button.getAttribute('data-toggle-detail');
                const detailsRow = document.getElementById('details-' + id);
                if (detailsRow) {
                    const isHidden = detailsRow.classList.toggle('hidden');
                    button.setAttribute('aria-expanded', !isHidden);
                    detailsRow.setAttribute('aria-hidden', isHidden);
                }
            });
        });
    });
</script>
@endpush
@endonce
