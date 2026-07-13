<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest for uploading a new personal document by the employee.
 *
 * Validates personal document upload input including:
 * - Document type (identity, passport, contract, health_certificate)
 * - Document number (must be unique)
 * - Expiry date (must be in the future)
 * - Document file (required, PDF/JPG/PNG, max 5MB)
 */
class MyDocumentRequest extends FormRequest
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
            'document_type' => ['required', 'in:identity,passport,contract,health_certificate'],
            'document_number' => ['required', 'string', 'max:100', 'unique:documents,document_number'],
            'expiry_date' => ['required', 'date', 'after:today'],
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    /**
     * Custom validation messages in Arabic.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'document_type.required' => 'نوع الوثيقة مطلوب',
            'expiry_date.after' => 'تاريخ الانتهاء يجب أن يكون في المستقبل',
        ];
    }
}
