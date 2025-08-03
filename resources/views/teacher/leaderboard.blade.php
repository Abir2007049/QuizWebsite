@extends('layout')

@section('title', 'Leaderboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4 py-12 text-white relative overflow-hidden">

    {{-- Background glowing blobs --}}
    <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-indigo-700 opacity-20 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-[400px] h-[400px] bg-purple-600 opacity-20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto space-y-8 relative z-10">

        <h2 class="text-4xl md:text-4xl font-extrabold text-white leading-tight mb-6">
            Leaderboard
        </h2>

        <div class="bg-gray-900 bg-opacity-80 backdrop-blur-md border border-indigo-700 rounded-3xl shadow-2xl p-6 overflow-x-auto">
            <table class="min-w-full table-auto border-collapse border border-indigo-700 text-white">
                <thead>
                    <tr class="bg-gradient-to-r from-indigo-700 via-purple-800 to-pink-700 font-semibold tracking-wide">
                        <th class="border border-indigo-600 px-6 py-4 text-left text-lg">Rank</th>
                        <th class="border border-indigo-600 px-6 py-4 text-left text-lg">Student ID</th>
                        <th class="border border-indigo-600 px-6 py-4 text-left text-lg">Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $entry)
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-800 bg-opacity-30' : 'bg-gray-900 bg-opacity-20' }} hover:bg-purple-700/40 transition-colors duration-300">
                            <td class="border border-indigo-600 px-6 py-3 font-bold text-lg text-purple-300">{{ $index + 1 }}</td>
                            <td class="border border-indigo-600 px-6 py-3 font-semibold">{{ $entry->student_id }}</td>
                            <td class="border border-indigo-600 px-6 py-3 font-bold text-green-400">{{ $entry->score }}</td>
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
