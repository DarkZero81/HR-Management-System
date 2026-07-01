<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function index(): JsonResponse
    {
        $documents = Document::with('employee')->get();
        return response()->json(['data' => $documents], 200);
    }

    public function store(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'employee_id' => ['required', 'exists:employees,id'],
                'document_type' => ['required', 'in:identity,passport,contract,health_certificate'],
                'document_number' => ['required', 'string', 'max:100', 'unique:documents'],
                'expiry_date' => ['required', 'date', 'after:today'],
                'file_path' => ['required', 'string'],
            ]);

            $document = Document::create($validated);
            return response()->json(['data' => $document], 201);
        });
    }

    public function show(int $id): JsonResponse
    {
        $document = Document::with('employee')->findOrFail($id);
        return response()->json(['data' => $document], 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
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
        return DB::transaction(function () use ($id) {
            $document = Document::findOrFail($id);
            $document->delete();
            return response()->json(['message' => 'Document deleted successfully'], 200);
        });
    }

    public function getExpiringDocuments(int $days = 30): JsonResponse
    {
        $expiringDocuments = Document::with('employee')
            ->where('expiry_date', '<=', Carbon::now()->addDays($days))
            ->where('expiry_date', '>=', Carbon::now())
            ->get();

        return response()->json(['data' => $expiringDocuments], 200);
    }
}