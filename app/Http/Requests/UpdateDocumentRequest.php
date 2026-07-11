<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
