<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class DocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $isQuickUpload = $this->hasFile('document');

        if ($isQuickUpload) {
            return [
                'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            ];
        }

        return [
            'employee_id'     => ['required', 'exists:employees,id'],
            'document_type'   => ['required', 'in:identity,passport,contract,health_certificate'],
            'document_number' => ['required', 'string', 'max:100', 'unique:documents,document_number'],
            'expiry_date'     => ['required', 'date'],
            'file'            => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
