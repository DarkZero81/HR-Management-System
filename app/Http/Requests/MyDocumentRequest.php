<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document_type' => ['required', 'in:identity,passport,contract,health_certificate'],
            'document_number' => ['required', 'string', 'max:100'],
            'expiry_date' => ['required', 'date', 'after:today'],
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'document_type.required' => 'نوع الوثيقة مطلوب',
            'expiry_date.after' => 'تاريخ الانتهاء يجب أن يكون في المستقبل',
        ];
    }
}