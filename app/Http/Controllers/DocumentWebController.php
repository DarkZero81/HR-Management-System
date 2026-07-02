<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DocumentWebController extends Controller
{
    public function index(): View
    {
        $documents = Document::with('employee')->orderBy('expiry_date')->paginate(15);
        return view('documents.index', compact('documents'));
    }

    public function myDocuments(): View
    {
        $employeeId = Auth::user()?->employee?->id;
        $documents = Document::query()
            ->where('employee_id', $employeeId)
            ->orderBy('expiry_date')
            ->paginate(12);

        return view('documents.index', compact('documents'));
    }
}
