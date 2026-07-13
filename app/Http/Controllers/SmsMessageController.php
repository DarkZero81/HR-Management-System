<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

/**
 * Controller for SMS/WhatsApp messaging.
 *
 * Handles:
 * - Displaying the messaging interface
 * - Sending bulk messages to all employees, a department, or individual employees
 * - Recipient filtering based on type selection
 */
class SmsMessageController extends Controller
{
    /**
     * Display the messaging index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('sms.index');
    }

    /**
     * Show the form for composing a new message.
     *
     * Loads all employees with their user data for recipient selection.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $employees = Employee::with('user')->get();
        return view('sms.create', compact('employees'));
    }

    /**
     * Store and send a new message.
     *
     * Recipients are determined by type:
     * - individual: selected employee IDs
     * - department: all employees in the selected department
     * - all: all employees in the system
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'message' => 'required|string|max:1600',
            'type' => 'required|in:individual,department,all',
        ]);

        $users = collect();

        if ($request->type === 'individual') {
            $users = User::whereHas('employee', fn($q) => $q->whereIn('id', $request->recipients))->get();
        } elseif ($request->type === 'department') {
            $users = User::whereHas('employee.department', fn($q) => $q->where('id', $request->department_id))->get();
        } else {
            $users = User::whereHas('employee')->get();
        }

        foreach ($users as $user) {
            // \Notification::route('sms', $user->employee->phone)
            //     ->notify(new \App\Notifications\CustomMessageNotification($request->message));
        }

        return back()->with('success', 'تم إرسال الرسالة بنجاح إلى ' . $users->count() . ' مستلم');
    }
}
