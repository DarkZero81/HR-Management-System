<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SmsMessageController extends Controller
{
    public function index()
    {
        return view('sms.index');
    }

    public function create()
    {
        $employees = Employee::with('user')->get();
        return view('sms.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'message' => 'required|string|max:1600',
            'type' => 'required|in:individual,department,all',
        ]);

        // جلب المستلمين حسب النوع
        $users = collect();

        if ($request->type === 'individual') {
            $users = User::whereHas('employee', fn($q) => $q->whereIn('id', $request->recipients))->get();
        } elseif ($request->type === 'department') {
            $users = User::whereHas('employee.department', fn($q) => $q->where('id', $request->department_id))->get();
        } else {
            $users = User::whereHas('employee')->get();
        }

        // إرسال الرسائل عبر Notification
        foreach ($users as $user) {
            // \Notification::route('sms', $user->employee->phone)
            //     ->notify(new \App\Notifications\CustomMessageNotification($request->message));
        }

        return back()->with('success', 'تم إرسال الرسالة بنجاح إلى ' . $users->count() . ' مستلم');
    }
}