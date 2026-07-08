<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 4,
        ]);

        // Create employee record
        $nameParts = explode(' ', trim($request->name), 2);
        Employee::create([
            'user_id' => $user->id,
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'national_id' => 'temp-' . $user->id,
            'email' => $request->email,
            'base_salary' => 0,
            'join_date' => now(),
        ]);

        // Generate OTP for registration
        $code = str_pad((int) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::where('email', $request->email)->delete();

        OtpCode::create([
            'email' => $request->email,
            'user_id' => $user->id,
            'code' => $code,
            'type' => 'register',
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email
        Mail::to($request->email)->send(new \App\Mail\OtpMail($code, 'register'));

        // Redirect to OTP verification page
        return redirect()->route('otp.verify.form', [
            'email' => $request->email,
        ])->with('success', 'تم إنشاء الحساب! تم إرسال رمز التحقق إلى بريدك الإلكتروني.');
    }
}