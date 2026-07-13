<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest for updating an existing employee document.
 *
 * Validates document update input including:
 * - Employee association
 * - Document type and number (unique except current record)
 * - Expiry date (must be in the future)
 * - Optional file upload (PDF, JPG, PNG, max 5MB)
 */
class UpdateDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id'     => ['required', 'exists:employees,id'],
            'document_type'   => ['required', 'in:identity,passport,contract,health_certificate'],
            'document_number' => ['required', 'string', 'max:100', 'unique:documents,document_number,' . $this->route('document')->id],
            'expiry_date'     => ['required', 'date', 'after:today'],
            'file'            => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
