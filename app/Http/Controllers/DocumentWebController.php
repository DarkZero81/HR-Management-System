<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Http\Requests\MyDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\AuditLog;
use App\Models\Document;
use App\Models\Employee;
use App\Http\Controllers\Traits\Auditable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller for document management.
 *
 * Handles:
 * - CRUD operations for employee documents
 * - Document filtering by type, status, date range
 * - Personal document upload for employees (My Documents)
 * - Document expiry tracking and notifications
 * - Audit logging via Auditable trait
 */
class DocumentWebController extends Controller
{
    use Auditable;

    /**
     * Apply common document filters to the query.
     *
     * Filters by: document type, expiry status, date range, employee
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $isMyPage
     * @return void
     */
    protected function applyDocumentFilters($query, Request $request, bool $isMyPage = false): void
    {
        $query->when($request->filled('document_type'), function ($q) use ($request) {
            $q->where('document_type', $request->document_type);
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            switch ($request->status) {
                case 'active':
                    $q->where('expiry_date', '>', now());
                    break;
                case 'expiring':
                    $q->where('expiry_date', '<=', now()->addMonths(3))
                      ->where('expiry_date', '>', now());
                    break;
                case 'expired':
                    $q->where('expiry_date', '<', now());
                    break;
            }
        });

        $query->when($request->filled('date_from'), function ($q) use ($request) {
            $q->whereDate('created_at', '>=', $request->date_from);
        });

        $query->when($request->filled('date_to'), function ($q) use ($request) {
            $q->whereDate('created_at', '<=', $request->date_to);
        });

        if (!$isMyPage) {
            $query->when($request->filled('employee_id'), function ($q) use ($request) {
                $q->where('employee_id', $request->employee_id);
            });

            $query->when($request->filled('my_documents'), function ($q) {
                $employeeId = Auth::user()?->employee?->id;
                if ($employeeId) {
                    $q->where('employee_id', $employeeId);
                } else {
                    $q->where('employee_id', 0);
                }
            });
        }
    }

    /**
     * Display a listing of all documents (admin view).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $baseQuery = Document::with('employee.user')->orderBy('expiry_date');

        $this->applyDocumentFilters($baseQuery, $request, false);

        $documents = (clone $baseQuery)->paginate(10)->appends($request->query());

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('expiry_date', '>', now())->count(),
            'expiring' => (clone $baseQuery)
                ->where('expiry_date', '<=', now()->addMonths(3))
                ->where('expiry_date', '>', now())
                ->count(),
            'expired' => (clone $baseQuery)->where('expiry_date', '<', now())->count(),
        ];

        $expiringDocuments = (clone $baseQuery)
            ->where('expiry_date', '<=', now()->addMonths(3))
            ->where('expiry_date', '>', now())
            ->paginate(5)
            ->appends($request->query());

        $employees = Employee::orderBy('first_name')->get();

        $myMode = false;

        return view('documents.index', compact('documents', 'stats', 'expiringDocuments', 'employees', 'myMode'));
    }

    /**
     * Redirect to my documents page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function myFiles(): RedirectResponse
    {
        return redirect()->route('my.documents.index');
    }

    /**
     * Display the authenticated employee's personal documents.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function myDocuments(Request $request): View
    {
        $employeeId = Auth::user()?->employee?->id;

        $baseQuery = Document::query()
            ->where('employee_id', $employeeId)
            ->with('employee.user');

        $this->applyDocumentFilters($baseQuery, $request, true);

        $documents = (clone $baseQuery)
            ->orderBy('expiry_date')
            ->paginate(10)
            ->appends($request->query());

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('expiry_date', '>', now())->count(),
            'expiring' => (clone $baseQuery)
                ->where('expiry_date', '<=', now()->addMonths(3))
                ->where('expiry_date', '>', now())
                ->count(),
            'expired' => (clone $baseQuery)->where('expiry_date', '<', now())->count(),
        ];

        $expiringDocuments = (clone $baseQuery)
            ->where('expiry_date', '<=', now()->addMonths(3))
            ->where('expiry_date', '>', now())
            ->paginate(5)
            ->appends($request->query());

        return view('documents.index', compact('documents', 'stats', 'expiringDocuments'))
            ->with('myMode', true);
    }

    /**
     * Display the specified document.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\View\View
     */
    public function show(Document $document): View
    {
        $document->load('employee.user');

        return view('documents.show', compact('document'));
    }

    /**
     * Display the specified document (personal view with authorization).
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\View\View
     */
    public function myShow(Document $document): View
    {
        $this->authorizeEdit($document);

        $document->load('employee.user');

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for creating a new personal document.
     *
     * @return \Illuminate\View\View
     */
    public function myCreate(): View
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('documents.create', compact('employees'))->with('myMode', true);
    }

    /**
     * Check if the authenticated user can edit the given document.
     *
     * @param  \App\Models\Document  $document
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function authorizeEdit(Document $document): void
    {
        $user = Auth::user();
        $employee = $user?->employee;

        if (! $employee || $document->employee_id !== $employee->id) {
            $isAdmin = in_array(strtolower(optional($user->role)->role_name ?? ''), ['admin', 'manager'], true);
            if (! $isAdmin) {
                abort(403, 'غير مصرح لك بالوصول إلى هذه الوثيقة.');
            }
        }
    }

    /**
     * Show the form for editing the specified personal document.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\View\View
     */
    public function myEdit(Document $document): View
    {
        $this->authorizeEdit($document);

        $employees = Employee::orderBy('first_name')->get();

        return view('documents.edit', [
            'document' => $document,
            'employees' => $employees,
            'myMode' => true,
        ]);
    }

    /**
     * Update the specified personal document.
     *
     * @param  \App\Http\Requests\UpdateDocumentRequest  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function myUpdate(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $this->authorizeEdit($document);

        $validated = $request->validated();
        $oldValues = $document->toArray();

        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('documents', 'public');
        }
        unset($validated['file']);

        $document->update($validated);

        $this->audit('update', $document, $oldValues);

        return redirect()->route('my.documents.index')->with('success', 'تم تحديث بيانات الوثيقة بنجاح.');
    }

    /**
     * Store a new personal document uploaded by the employee.
     *
     * @param  \App\Http\Requests\MyDocumentRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

        $this->audit('create', $document);

        return redirect()->route('my.documents.index')->with('success', 'تم رفع الوثيقة بنجاح.');
    }

    /**
     * Show the form for creating a new document (admin view).
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('documents.create', compact('employees'))->with('myMode', false);
    }

    /**
     * Store a newly created document (admin view).
     *
     * @param  \App\Http\Requests\DocumentRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DocumentRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('documents', 'public');
            $data['file_path'] = $filePath;
        }

        $employee = $user?->employee;

        if (!$employee) {
            return redirect()->route('my.documents.index')->with('error', 'عذراً، لا يوجد ملف وظيفي مرتبط بحسابك الحالي لرفع المستندات إليه.');
        }

        if ($data['employee_id'] != $employee->id && !in_array(strtolower(optional($user->role)->role_name ?? ''), ['admin', 'manager'], true)) {
            return redirect()->route('my.documents.index')->with('error', 'غير مصرح لك برفع وثائق لموظفين آخرين.');
        }

        $document = Document::create($data);

        $this->audit('create', $document);

        return redirect()->route('documents.index')->with('success', 'تم رفع الوثيقة بنجاح.');
    }

    /**
     * Show the form for editing the specified document (admin view).
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\View\View
     */
    public function edit(Document $document): View
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('documents.edit', compact('document', 'employees'))->with('myMode', false);
    }

    /**
     * Update the specified document (admin view).
     *
     * @param  \App\Http\Requests\UpdateDocumentRequest  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $validated = $request->validated();

        $oldValues = $document->toArray();

        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('documents', 'public');
        }
        unset($validated['file']);

        $document->update($validated);

        $this->audit('update', $document, $oldValues);

        return redirect()->route('documents.index')->with('success', 'تم تحديث بيانات الوثيقة بنجاح.');
    }

    /**
     * Remove the specified document from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Document $document): RedirectResponse
    {
        $user = Auth::user();
        $employee = $user?->employee;
        $isAdmin = in_array(strtolower(optional($user->role)->role_name ?? ''), ['admin', 'manager'], true);

        if (! $isAdmin) {
            if (! $employee || $document->employee_id !== $employee->id) {
                abort(403, 'غير مصرح لك بحذف هذه الوثيقة.');
            }
            return $this->deleteDocument($document, 'my.documents.index');
        }

        return $this->deleteDocument($document, 'documents.index');
    }

    /**
     * Delete the document and its file from storage.
     *
     * @param  \App\Models\Document  $document
     * @param  string  $redirectRoute
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function deleteDocument(Document $document, string $redirectRoute): RedirectResponse
    {
        $documentData = $document->toArray();

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        $this->audit('delete', $document, $documentData);

        return redirect()->route($redirectRoute)->with('success', 'تم حذف الوثيقة بنجاح.');
    }
}
