<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Http\Requests\MyDocumentRequest;
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
        $documents = Document::with('employee')->orderBy('expiry_date')->paginate(8);
        return view('documents.index', compact('documents'));
    }

    public function myDocuments(): View
    {
        $employeeId = Auth::user()?->employee?->id;
        $documents = Document::query()
            ->where('employee_id', $employeeId)
            ->orderBy('expiry_date')
            ->paginate(12);

        return view('documents.my_index', compact('documents'));
    }

    public function myCreate(): View
    {
        return view('documents.my_create');
    }

    public function storeMy(MyDocumentRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user?->employee;

        if (! $employee) {
            return back()->with('error', 'لا يوجد ملف موظف مرتبط بحسابك.');
        }

        $filePath = $request->file('document')->store('documents', 'public');

        $document = Document::create([
            'employee_id' => $employee->id,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'expiry_date' => $request->expiry_date,
            'file_path' => $filePath,
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action_type' => 'create',
            'table_name' => 'documents',
            'record_id' => $document->id,
            'new_values' => $document->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('my.documents')->with('success', 'تم رفع الوثيقة بنجاح.');
    }

    public function create(): View
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('documents.create', compact('employees'));
    }

    public function store(DocumentRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($request->hasFile('document')) {
            $employee = $user?->employee;

            if (!$employee) {
                return redirect()->route('my.documents')->with('error', 'عذراً، لا يوجد ملف وظيفي مرتبط بحسابك الحالي لرفع المستندات إليه.');
            }

            $filePath = $request->file('document')->store('documents', 'public');

            $document = Document::create([
                'employee_id'     => $employee->id,
                'document_type'   => 'contract',
                'document_number' => 'QUICK-' . strtoupper(uniqid()),
                'expiry_date'     => now()->addYear()->format('Y-m-d'),
                'file_path'       => $filePath,
            ]);

            AuditLog::create([
                'user_id'      => $user->id,
                'action_type'  => 'create',
                'table_name'   => 'documents',
                'record_id'    => $document->id,
                'old_values'   => null,
                'new_values'   => $document->fresh()->toArray(),
                'performed_at' => now(),
            ]);

            return redirect()->route('my.documents')->with('success', 'تم رفع وحفظ الوثيقة بنجاح في سجل مستنداتك.');
        }

        $filePath = $request->file('file')->store('documents', 'public');
        $data['file_path'] = $filePath;

        $document = Document::create($data);

        AuditLog::create([
            'user_id'      => $user->id,
            'action_type'  => 'create',
            'table_name'   => 'documents',
            'record_id'    => $document->id,
            'old_values'   => null,
            'new_values'   => $document->fresh()->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('documents.index')->with('success', 'تم رفع الوثيقة بنجاح.');
    }

    public function edit(Document $document): View
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('documents.edit', compact('document', 'employees'));
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id'     => ['required', 'exists:employees,id'],
            'document_type'   => ['required', 'in:identity,passport,contract,health_certificate'],
            'document_number' => ['required', 'string', 'max:100', 'unique:documents,document_number,' . $document->id],
            'expiry_date'     => ['required', 'date'],
            'file'            => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $oldValues = $document->toArray();

        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('documents', 'public');
        }
        unset($validated['file']);

        $document->update($validated);

        AuditLog::create([
            'user_id'      => Auth::id(),
            'action_type'  => 'update',
            'table_name'   => 'documents',
            'record_id'    => $document->id,
            'old_values'   => $oldValues,
            'new_values'   => $document->fresh()->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('documents.index')->with('success', 'تم تحديث بيانات الوثيقة بنجاح.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        $documentData = $document->toArray();

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        AuditLog::create([
            'user_id'      => Auth::id(),
            'action_type'  => 'delete',
            'table_name'   => 'documents',
            'record_id'    => $document->id,
            'old_values'   => $documentData,
            'new_values'   => null,
            'performed_at' => now(),
        ]);

        return redirect()->route('documents.index')->with('success', 'تم حذف الوثيقة بنجاح.');
    }
}
