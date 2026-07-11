<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    protected function ensureAuthorized(): void
    {
        if (! Auth::check()) {
            abort(401, 'Unauthorized');
        }

        $role = strtolower(optional(Auth::user()->role)->role_name ?? '');

        if (! in_array($role, ['admin', 'manager'], true)) {
            abort(403, 'Forbidden');
        }
    }

    public function index(): JsonResponse
    {
        $this->ensureAuthorized();

        $documents = Document::with('employee.user')->get();
        return response()->json(['data' => $documents], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $this->ensureAuthorized();

        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'employee_id' => ['required', 'exists:employees,id'],
                'document_type' => ['required', 'in:identity,passport,contract,health_certificate'],
                'document_number' => ['required', 'string', 'max:100', 'unique:documents,document_number'],
                'expiry_date' => ['required', 'date', 'after:today'],
                'file_path' => ['required', 'string'],
            ]);

            $document = Document::create($validated);
            return response()->json(['data' => $document], 201);
        });
    }

    public function show(int $id): JsonResponse
    {
        $this->ensureAuthorized();

        $document = Document::with('employee.user')->findOrFail($id);
        return response()->json(['data' => $document], 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $this->ensureAuthorized();

        return DB::transaction(function () use ($request, $id) {
            $document = Document::findOrFail($id);

            $validated = $request->validate([
                'employee_id' => ['sometimes', 'exists:employees,id'],
                'document_type' => ['sometimes', 'in:identity,passport,contract,health_certificate'],
                'document_number' => ['sometimes', 'string', 'max:100', 'unique:documents,document_number,' . $id],
                'expiry_date' => ['sometimes', 'date'],
                'file_path' => ['sometimes', 'string'],
            ]);

            $document->update($validated);
            return response()->json(['data' => $document], 200);
        });
    }

    public function destroy(int $id): JsonResponse
    {
        $this->ensureAuthorized();

        return DB::transaction(function () use ($id) {
            $document = Document::findOrFail($id);
            $document->delete();
            return response()->json(['message' => 'Document deleted successfully'], 200);
        });
    }

    public function getExpiringDocuments(int $days = 30): JsonResponse
    {
        $this->ensureAuthorized();

        $expiringDocuments = Document::with('employee.user')
            ->where('expiry_date', '<=', Carbon::now()->addDays($days))
            ->where('expiry_date', '>=', Carbon::now())
            ->get();

        return response()->json(['data' => $expiringDocuments], 200);
    }
}