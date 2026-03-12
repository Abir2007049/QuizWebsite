<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use Carbon\Carbon;
use App\Events\QuizStatusUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuizViolationMail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;



class QuizExamController extends Controller
{
    //  Show the quiz to the student
    public function takeQuiz(Request $request, $quiz_id)
    {
        $studentId = session('student_id');
        Log::info('Student ID from session: ' . $studentId);

        if (!$studentId) {
            return redirect()->route('quiz.listStud')->with('error', 'Please enter the quiz room first.');
        }

        $quiz = Quiz::with('questions')->findOrFail($quiz_id);

        $startDatetime = Carbon::parse($quiz->start_datetime)->setTimezone('Asia/Dhaka');
        $duration = $quiz->duration;
        $finishDateTime = $startDatetime->copy()->addMinutes($duration);
        $current = Carbon::now('Asia/Dhaka');

        if ($current->lt($startDatetime)) {
            return back()->with('error', 'The quiz has not started yet.');
        }

        if ($current->gt($finishDateTime)) {
            return back()->with('error', 'The quiz has already ended.');
        }

        //  Prepare questions array for frontend
     $questions = $quiz->questions->map(function ($q) {
    return [
        'id' =>$q->id,
        'text' => $q->text,
        'image' => $q->image
            ? asset('storage/' . ltrim($q->image, '/'))
            : null,

        'option1' => $q->option1,
        'option2' => $q->option2,
        'option3' => $q->option3,
        'option4' => $q->option4,
        'duration' => (int) $q->duration,
        // 'right_option' removed here — do not send it
    ];
});




        return view('student.questions', [
            'quiz' => $quiz,
            'duration' => $current->diffInSeconds($finishDateTime, false),
            'student_id' => $studentId,
            'questions' => $questions,
        ]);
    }

    // Set start time to now and calculate total quiz time from question durations
    public function startNow(Request $request, $id)
    {
       $current = (Carbon::now('Asia/Dhaka'));

        $quiz = Quiz::findOrFail($id);
        $quiz_id=$id;
        $totalDuration = $quiz->questions->sum('duration') ;
        
        $quiz->start_datetime = $current;
        $quiz->duration = $totalDuration;
        $quiz->save();

        event(new QuizStatusUpdated($quiz_id, Auth::user()->room_name, $current));
        
        return redirect()->route('quiz.list');
    }

    public function submitQuizAnswered(Request $request, $quiz, $student)
    {
        // Keep compatibility with existing route and reuse result submission endpoint.
        session(['student_id' => $student]);

        $payload = [
            'answers' => $request->input('answers', []),
        ];

        $resultRequest = Request::create('/store-result', 'POST', $payload);
        $resultRequest->setLaravelSession($request->session());

        return app(ResultController::class)->storeResult($resultRequest);
    }

    public function sendViolationEmail(Request $request)
    {
        if ($request->input('state') === 'hidden') {
            $quiz = Quiz::with('teacher')->find($request->input('quiz_id'));

            if ($quiz && $quiz->teacher) {
                $studentId = session('student_id');

                try {
                    Mail::to($quiz->teacher->email)->send(new QuizViolationMail($studentId));
                } catch (TransportExceptionInterface $e) {
                    Log::error('Quiz violation email transport failed.', [
                        'quiz_id' => $request->input('quiz_id'),
                        'teacher_email' => $quiz->teacher->email,
                        'student_id' => $studentId,
                        'message' => $e->getMessage(),
                    ]);

                    return response()->json([
                        'status' => 'Email failed.',
                        'message' => 'Email service is currently unavailable.',
                    ], 503);
                }

                return response()->json(['status' => 'Email sent.']);
            }

            Log::warning('Quiz or teacher not found for violation email.', [
                'quiz_id' => $request->input('quiz_id'),
            ]);
        }

        return response()->json(['status' => 'Logged but no email sent.']);
    }

   

}