<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    public function create(): View
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('documents.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'document_type' => ['required', 'in:identity,passport,contract,health_certificate'],
            'document_number' => ['required', 'string', 'max:100', 'unique:documents,document_number'],
            'expiry_date' => ['required', 'date', 'after:today'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $filePath = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'employee_id' => $validated['employee_id'],
            'document_type' => $validated['document_type'],
            'document_number' => $validated['document_number'],
            'expiry_date' => $validated['expiry_date'],
            'file_path' => $filePath,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'create',
            'table_name' => 'documents',
            'record_id' => $document->id,
            'new_values' => $document->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('documents.index')->with('success', 'تم رفع الوثيقة بنجاح.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        $documentData = $document->toArray();

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'delete',
            'table_name' => 'documents',
            'record_id' => $document->id,
            'old_values' => $documentData,
            'performed_at' => now(),
        ]);

        return redirect()->route('documents.index')->with('success', 'تم حذف الوثيقة بنجاح.');
    }
}
