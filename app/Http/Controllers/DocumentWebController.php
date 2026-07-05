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
use Illuminate\Validation\ValidationException;

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

        return view('documents.index', compact('documents'));
    }

    public function create(): View
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('documents.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        \Log::info('Document upload attempt', [
            'has_file_document' => $request->hasFile('document'),
            'has_file_file' => $request->hasFile('file'),
            'all_files' => array_keys($request->allFiles()),
            'user_id' => Auth::id(),
            'employee_id' => Auth::user()?->employee?->id,
        ]);

        try {
            // 📤 حالة الموظف: رفع سريع عبر حقل الملف "document"
            if ($request->hasFile('document')) {
                $request->validate([
                    'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
                ]);

                $employee = Auth::user()?->employee;

                if (!$employee) {
                    \Log::warning('Document upload failed: no employee linked', ['user_id' => Auth::id()]);
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
                    'user_id'      => Auth::id(),
                    'action_type'  => 'create',
                    'table_name'   => 'documents',
                    'record_id'    => $document->id,
                    'old_values'   => null,
                    'new_values'   => json_encode($document->fresh()->toArray(), JSON_UNESCAPED_UNICODE),
                    'performed_at' => now(),
                ]);

                \Log::info('Document uploaded successfully', ['document_id' => $document->id]);
                return redirect()->route('my.documents')->with('success', 'تم رفع وحفظ الوثيقة السريعة بنجاح في سجل مستنداتك.');
            }

            // 📋 حالة الإدارة: رفع عبر النموذج القديم مع التحقق الكامل
            $validated = $request->validate([
                'employee_id'     => ['required', 'exists:employees,id'],
                'document_type'   => ['required', 'in:identity,passport,contract,health_certificate'],
                'document_number' => ['required', 'string', 'max:100', 'unique:documents,document_number'],
                'expiry_date'     => ['required', 'date', 'after:today'],
                'file'            => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            ]);

            $filePath = $request->file('file')->store('documents', 'public');

            $document = Document::create([
                'employee_id'     => $validated['employee_id'],
                'document_type'   => $validated['document_type'],
                'document_number' => $validated['document_number'],
                'expiry_date'     => $validated['expiry_date'],
                'file_path'       => $filePath,
            ]);

            AuditLog::create([
                'user_id'      => Auth::id(),
                'action_type'  => 'create',
                'table_name'   => 'documents',
                'record_id'    => $document->id,
                'old_values'   => null,
                'new_values'   => json_encode($document->fresh()->toArray(), JSON_UNESCAPED_UNICODE),
                'performed_at' => now(),
            ]);

            return redirect()->route('documents.index')->with('success', 'تم رفع الوثيقة بنجاح.');
        } catch (ValidationException $e) {
            \Log::warning('Document upload validation failed', ['errors' => $e->errors()]);
            if ($request->hasFile('document')) {
                return redirect()->route('my.documents')->withErrors($e->errors())->withInput();
            }
            return redirect()->route('documents.index')->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            \Log::error('Document upload failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            if ($request->hasFile('document')) {
                return redirect()->route('my.documents')->with('error', 'حدث خطأ غير متوقع أثناء رفع المستند. يرجى المحاولة مرة أخرى.')->withInput();
            }

            return redirect()->route('documents.index')->with('error', 'حدث خطأ غير متوقع أثناء رفع المستند. يرجى المحاولة مرة أخرى.')->withInput();
        }
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
            'old_values'   => json_encode($documentData, JSON_UNESCAPED_UNICODE),
            'new_values'   => null,
            'performed_at' => now(),
        ]);

        return redirect()->route('documents.index')->with('success', 'تم حذف الوثيقة بنجاح.');
    }
}
