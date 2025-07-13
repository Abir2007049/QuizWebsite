@extends('layout')

@section('title', 'Quiz List')

@section('content')
<div class="congrats-container text-center mt-5">
    <marquee behavior="alternate" scrollamount="10">
        <h2 class="congrats-text">🎉 Well Done! You scored {{ $score }} points! 🎉</h2>
    </marquee>
    <p class="congrats-message">Keep up the great work and ace your goals! 🚀</p>
</div>

<style>
    .congrats-container {
        background: linear-gradient(135deg,rgb(24, 129, 57),rgb(41, 152, 22));
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        animation: glow 2s infinite alternate;
        color: white;
    }

    .congrats-text {
        font-size: 2.5rem;
        font-weight: bold;
        text-shadow: 2px 2px 10px rgba(255, 255, 255, 0.8);
    }

    .congrats-message {
        font-size: 1.2rem;
        margin-top: 15px;
        font-style: italic;
    }

    @keyframes glow {
        0% {
            box-shadow: 0 5px 15px rgba(106, 17, 203, 0.5);
        }
        100% {
            box-shadow: 0 5px 15px rgba(37, 117, 252, 0.5);
        }
    }
</style>
@endsection

