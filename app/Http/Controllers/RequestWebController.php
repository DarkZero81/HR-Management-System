<?php

namespace App\Http\Controllers;

use App\Models\HrTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestWebController extends Controller
{
    public function index(): View
    {
        $transactions = HrTransaction::with(['employee.user', 'approver'])->paginate(15);
        return view('requests.index', compact('transactions'));
    }

    public function create(): View
    {
        return view('requests.create', ['transaction_types' => ['leave', 'permission', 'promotion', 'penalty', 'transfer']]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_type' => ['required', 'in:leave,permission,promotion,penalty,transfer'],
            'start_date_time' => ['required', 'date'],
            'end_date_time' => ['required', 'date', 'after:start_date_time'],
            'description' => ['nullable', 'string'],
        ]);

        HrTransaction::create($validated);
        return redirect()->route('requests.index')->with('success', 'Request submitted');
    }
}
