<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\HrTransaction;

class RequestsList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    protected $listeners = [
        'documentUploaded' => '$refresh',
        'attendanceUpdated' => '$refresh',
    ];

    public function cancel($id)
    {
        $user = Auth::user();
        $employee = $user?->employee;

        if (! $employee) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'لا يوجد ملف موظف مرتبط.']);
            return;
        }

        $tx = HrTransaction::where('id', $id)->where('employee_id', $employee->id)->first();

        if (! $tx || $tx->status !== 'pending') {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'لا يمكن إلغاء هذا الطلب.']);
            return;
        }

        $tx->update(['status' => 'cancelled']);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'تم إلغاء الطلب بنجاح.']);
        $this->resetPage();
    }

    public function render()
    {
        $employee = Auth::user()?->employee;
        $transactions = HrTransaction::with('employee')
            ->when($employee, fn($q) => $q->where('employee_id', $employee->id))
            ->latest()
            ->paginate(8);

        return view('livewire.requests-list', compact('transactions'));
    }
}
