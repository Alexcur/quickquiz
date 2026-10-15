<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    

class VerificationController extends Controller
{
    // "Check your inbox" page
    public function notice(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify-email');
    }

    // The person clicked the link in the email
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill(); // marks the email as verified

        return redirect()->route('dashboard');
    }

    // The "Resend email" button
    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
        // "Use a different email": remove the unconfirmed account and start again
    public function change(Request $request)
    {
        $user = $request->user();

        // Safety: a confirmed account is never deleted here
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('register');
    }
}