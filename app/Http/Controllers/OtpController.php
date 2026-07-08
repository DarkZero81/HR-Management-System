<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.otp-login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::whereHas('employee', fn($q) => $q->where('email', $request->email))
            ->orWhere('email', $request->email)
            ->first();

        if (!$user) {
            return back()->with('error', 'البريد الإلكتروني غير مسجل');
        }

        $code = str_pad((int) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        OtpCode::where('email', $request->email)->delete();

        OtpCode::create([
            'email' => $request->email,
            'user_id' => $user->id,
            'code' => $code,
            'type' => 'login',
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($request->email)->send(new \App\Mail\OtpMail($code, 'login', $request->email));

        return redirect()->route('otp.verify.form', ['email' => $request->email])
            ->with('success', 'تم إرسال الرمز إلى بريدك الإلكتروني');
    }

    public function showVerifyForm(Request $request)
    {
        $email = $request->email;
        
        return view('auth.otp-verify', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'code' => 'required|string|size:6',
        ]);

        $otp = OtpCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->whereNull('used_at')
            ->whereIn('type', ['login', 'register'])
            ->first();

        if (!$otp) {
            return back()->with('error', 'الرمز غير صالح أو منتهي الصلاحية');
        }

        $otp->update(['used_at' => now()]);
        
        $user = User::findOrFail($otp->user_id);
        Auth::login($user);

        $this->sendWelcomeMessage($user);

        return redirect()->intended('dashboard')
            ->with('success', 'مرحباً بك! تم تسجيل دخولك بنجاح.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::whereHas('employee', fn($q) => $q->where('email', $request->email))
            ->orWhere('email', $request->email)
            ->first();

        if (!$user) {
            return back()->with('error', 'البريد الإلكتروني غير مسجل');
        }

        $code = str_pad((int) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        OtpCode::where('email', $request->email)->delete();

        OtpCode::create([
            'email' => $request->email,
            'user_id' => $user->id,
            'code' => $code,
            'type' => 'login',
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($request->email)->send(new \App\Mail\OtpMail($code, 'login', $request->email));

        return back()->with('success', 'تم إعادة إرسال الرمز');
    }

    private function sendWelcomeMessage(User $user): void
    {
        $employee = $user->employee;
        if ($employee && $employee->email) {
            Mail::to($employee->email)->send(new \App\Mail\WelcomeMail($user->name, $employee->email));
        }
    }
}