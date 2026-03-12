<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\MailtrapApiMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;



class ForgotPasswordController extends Controller
{
    public function __construct(private MailtrapApiMailer $mailtrapApiMailer)
    {
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validate the email input
        $request->validate(['email' => 'required|email']);

        $email = $request->input('email');

        if ($this->mailtrapApiMailer->isConfigured()) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                return back()->withErrors(['email' => __(
                    Password::INVALID_USER
                )]);
            }

            try {
                $token = Password::broker()->createToken($user);
                $resetUrl = route('password.reset', [
                    'token' => $token,
                    'email' => $user->email,
                ]);

                $html = view('emails.password_reset', [
                    'email' => $user->email,
                    'resetUrl' => $resetUrl,
                ])->render();

                $this->mailtrapApiMailer->send(
                    $user->email,
                    'Reset Your Password',
                    $html,
                    null,
                    $user->name
                );

                return back()->with('status', __(
                    Password::RESET_LINK_SENT
                ));
            } catch (Throwable $e) {
                Log::error('Password reset email API send failed.', [
                    'email' => $email,
                    'message' => $e->getMessage(),
                ]);

                return back()->withErrors([
                    'email' => 'Email service is currently unavailable. Please try again shortly.',
                ]);
            }
        }

        try {
            // Attempt to send the password reset link to the given email
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (TransportExceptionInterface $e) {
            Log::error('Password reset email transport failed.', [
                'email' => $email,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'email' => 'Email service is currently unavailable. Please try again shortly.',
            ]);
        }

        // Check the status and return response accordingly
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }

    
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
