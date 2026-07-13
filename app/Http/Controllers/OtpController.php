<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Controller for OTP-based authentication.
 *
 * Handles:
 * - Sending OTP codes to user email
 * - Verifying OTP codes
 * - Resending OTP codes with rate limiting
 * - Account lockout after multiple failed attempts
 * - Welcome email after successful login
 */
class OtpController extends Controller
{
    /**
     * Get maximum allowed OTP verification attempts.
     *
     * @return int
     */
    private function getMaxAttempts(): int
    {
        return (int) config('auth.otp_max_attempts', 5);
    }

    /**
     * Get lockout duration in minutes after exceeding max attempts.
     *
     * @return int
     */
    private function getLockoutMinutes(): int
    {
        return (int) config('auth.otp_lockout_minutes', 15);
    }

    /**
     * Check if the given email is currently locked out.
     *
     * @param  string  $email
     * @return bool
     */
    private function isLockedOut(string $email): bool
    {
        $key = "otp_lockout:{$email}";

        if (!Cache::has($key)) {
            return false;
        }

        $ttl = Cache::get($key);
        if (!$ttl || $ttl <= now()->timestamp) {
            Cache::forget($key);

            return false;
        }

        return true;
    }

    /**
     * Increment failed OTP attempts for the given email.
     * Locks out the account if max attempts are reached.
     *
     * @param  string  $email
     * @return void
     */
    private function incrementFailedAttempts(string $email): void
    {
        $maxAttempts = $this->getMaxAttempts();
        $lockoutMinutes = $this->getLockoutMinutes();

        $attempts = Cache::get("otp_attempts:{$email}", 0);
        $attempts++;

        Cache::put("otp_attempts:{$email}", $attempts, now()->addMinutes($lockoutMinutes));

        if ($attempts >= $maxAttempts) {
            Cache::put("otp_lockout:{$email}", now()->addMinutes($lockoutMinutes)->timestamp, now()->addMinutes($lockoutMinutes));
            Cache::forget("otp_attempts:{$email}");
        }
    }

    /**
     * Clear failed attempts and lockout for the given email.
     *
     * @param  string  $email
     * @return void
     */
    private function clearFailedAttempts(string $email): void
    {
        Cache::forget("otp_attempts:{$email}");
        Cache::forget("otp_lockout:{$email}");
    }

    /**
     * Get remaining lockout time in seconds for the given email.
     *
     * @param  string  $email
     * @return int
     */
    private function getRemainingLockoutSeconds(string $email): int
    {
        $lockoutTimestamp = Cache::get("otp_lockout:{$email}");

        if (!$lockoutTimestamp) {
            return 0;
        }

        $remaining = $lockoutTimestamp - now()->timestamp;

        return max(0, $remaining);
    }

    /**
     * Display the OTP login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.otp-login');
    }

    /**
     * Send an OTP code to the user's email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        if ($this->isLockedOut($request->email)) {
            $remainingSeconds = $this->getRemainingLockoutSeconds($request->email);
            $minutes = (int) ceil($remainingSeconds / 60);

            return back()->with('error', "تم قفل الحساب مؤقتاً بسبب المحاولات الفاشلة المتكررة. يرجى الانتظار {$minutes} دقيقة قبل المحاولة مرة أخرى.");
        }

        $user = User::where('email', $request->email)
            ->orWhereHas('employee', fn($q) => $q->where('phone', $request->email))
            ->first();

        if (!$user) {
            return back()->with('error', 'البريد الإلكتروني غير مسجل');
        }

        $code = str_pad((int) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::where('email', $request->email)->delete();

        OtpCode::create([
            'email' => $request->email,
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'type' => 'login',
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($request->email)->send(new \App\Mail\OtpMail($code, 'login', $request->email));

        return redirect()->route('otp.verify.form', ['email' => $request->email])
            ->with('success', 'تم إرسال الرمز إلى بريدك الإلكتروني');
    }

    /**
     * Display the OTP verification form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showVerifyForm(Request $request)
    {
        $email = $request->email;

        return view('auth.otp-verify', compact('email'));
    }

    /**
     * Verify the OTP code and log the user in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'code' => 'required|string|size:6',
        ]);

        if ($this->isLockedOut($request->email)) {
            $remainingSeconds = $this->getRemainingLockoutSeconds($request->email);
            $minutes = (int) ceil($remainingSeconds / 60);

            return back()->with('error', "تم قفل الحساب مؤقتاً بسبب المحاولات الفاشلة المتكررة. يرجى الانتظار {$minutes} دقيقة قبل المحاولة مرة أخرى.");
        }

        $otp = OtpCode::where('email', $request->email)
            ->where('expires_at', '>', now())
            ->whereNull('used_at')
            ->whereIn('type', ['login', 'register'])
            ->latest()
            ->first();

        if (!$otp || !Hash::check($request->code, $otp->code)) {
            $this->incrementFailedAttempts($request->email);

            $remainingAttempts = max(0, $this->getMaxAttempts() - (Cache::get("otp_attempts:{$request->email}", 0) + 1));

            if ($remainingAttempts > 0) {
                return back()->with('error', "الرمز غير صالح أو منتهي الصلاحية. تبقى لك {$remainingAttempts} محاولة/محاولات.");
            }

            return back()->with('error', 'الرمز غير صالح أو منتهي الصلاحية.');
        }

        $this->clearFailedAttempts($request->email);

        $otp->update(['used_at' => now()]);

        $user = User::findOrFail($otp->user_id);

        if ((int) $user->is_active !== 1) {
            return redirect()->route('login')->with('error', 'تم تعطيل حسابك. يرجى التواصل مع مدير النظام.');
        }

        Auth::login($user);

        $this->sendWelcomeMessage($user);

        return redirect()->intended('dashboard')
            ->with('success', 'مرحباً بك! تم تسجيل دخولك بنجاح.');
    }

    /**
     * Resend the OTP code to the user's email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)
            ->orWhereHas('employee', fn($q) => $q->where('phone', $request->email))
            ->first();

        if (!$user) {
            return back()->with('error', 'البريد الإلكتروني غير مسجل');
        }

        $recentResends = OtpCode::where('email', $request->email)
            ->where('created_at', '>=', now()->subMinute())
            ->count();

        if ($recentResends >= 2) {
            $lastResend = OtpCode::where('email', $request->email)
                ->latest()
                ->first();

            $waitTime = $lastResend ? now()->diffInSeconds($lastResend->created_at->addMinute()) : 60;

            return back()->with('error', "لقد تجاوزت الحد المسموح من محاولات إعادة الإرسال. يرجى الانتظار {$waitTime} ثانية قبل المحاولة مرة أخرى.");
        }

        $code = str_pad((int) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::where('email', $request->email)->delete();

        OtpCode::create([
            'email' => $request->email,
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'type' => 'login',
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($request->email)->send(new \App\Mail\OtpMail($code, 'login', $request->email));

        return back()->with('success', 'تم إعادة إرسال الرمز');
    }

    /**
     * Send a welcome email to the newly logged-in user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    private function sendWelcomeMessage(User $user): void
    {
        $employee = $user->employee;
        if ($employee && $employee->email) {
            Mail::to($employee->email)->send(new \App\Mail\WelcomeMail($user->name, $employee->email));
        }
    }
}
