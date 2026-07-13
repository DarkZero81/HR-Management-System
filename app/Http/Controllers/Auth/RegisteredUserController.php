<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\OtpCode;
use App\Models\RolePermission;
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

/**
 * Controller for user registration.
 *
 * Handles:
 * - Displaying the registration form
 * - Creating new user accounts with employee role
 * - Creating corresponding employee profile
 * - Sending OTP verification email
 */
class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle a new user registration request.
     *
     * Creates the user account and employee profile,
     * then sends an OTP verification email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $employeeRole = RolePermission::firstOrCreate(
            ['role_name' => 'employee'],
            ['description' => 'Regular employee with self-service access']
        );
        $roleId = $employeeRole->id;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
            'is_active' => 1,
        ]);

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

        $code = str_pad((int) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::where('email', $request->email)->delete();

        OtpCode::create([
            'email' => $request->email,
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'type' => 'register',
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($request->email)->send(new \App\Mail\OtpMail($code, 'register'));

        return redirect()->route('otp.verify.form', [
            'email' => $request->email,
        ])->with('success', 'تم إنشاء الحساب! تم إرسال رمز التحقق إلى بريدك الإلكتروني.');
    }
}
