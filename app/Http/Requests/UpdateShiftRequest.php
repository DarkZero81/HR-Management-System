<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shift_name' => ['required', 'string', 'max:100'],
            'start_time' => ['required', 'date_format:H:i:s,H:i'],
            'end_time' => ['required', 'date_format:H:i:s,H:i'],
            'grace_period_minutes' => ['nullable', 'integer', 'min:0'],
            'is_overnight' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('start_time') && $this->filled('end_time')) {
                $start = \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time);
                $end = \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time);

                if ($this->boolean('is_overnight')) {
                    if ($end->lessThan($start)) {
                        $end->addDay();
                    }
                } elseif ($end->lessThanOrEqualTo($start)) {
                    $validator->errors()->add('end_time', 'وقت النهاية يجب أن يكون بعد وقت البداية.');
                }
            }
        });
    }
}
