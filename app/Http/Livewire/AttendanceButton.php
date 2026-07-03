<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceLog;
use Carbon\Carbon;

class AttendanceButton extends Component
{
    public $message = '';

    public function checkInOut()
    {
        $user = Auth::user();
        $employee = $user?->employee;

        if (! $employee) {
            $this->message = 'لا يوجد ملف موظف مرتبط بحسابك.';
            return;
        }

        $today = Carbon::today()->toDateString();

        $log = AttendanceLog::query()
            ->where('employee_id', $employee->id)
            ->where('log_date', $today)
            ->first();

        if (! $log) {
            AttendanceLog::create([
                'employee_id' => $employee->id,
                'log_date' => $today,
                'check_in' => Carbon::now(),
                'status' => 'present'
            ]);
            $this->message = 'تم تسجيل الحضور بنجاح.';
            $this->emit('attendanceUpdated');
            return;
        }

        if ($log && ! $log->check_out) {
            $log->update(['check_out' => Carbon::now()]);
            $this->message = 'تم تسجيل الانصراف بنجاح.';
            $this->emit('attendanceUpdated');
            return;
        }

        $this->message = 'تمت معالجة الحضور لهذا اليوم بالفعل.';
    }

    public function render()
    {
        return view('livewire.attendance-button');
    }
}
