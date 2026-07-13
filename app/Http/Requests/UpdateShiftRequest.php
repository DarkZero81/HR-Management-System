<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest for updating an existing shift.
 *
 * Validates shift update input with the same rules as creation.
 */
class UpdateShiftRequest extends FormRequest
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
            'shift_name' => ['required', 'string', 'max:100'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'grace_period_minutes' => ['nullable', 'integer', 'min:0'],
            'is_overnight' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Custom validation: ensure end_time is after start_time.
     *
     * For overnight shifts, end_time can be earlier than start_time (crosses midnight).
     * For regular shifts, end_time must be strictly after start_time.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('start_time') && $this->filled('end_time')) {
                $start = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);
                $end = \Carbon\Carbon::createFromFormat('H:i', $this->end_time);

                if ($this->boolean('is_overnight')) {
                    // Overnight shift: end_time can be before start_time (e.g., 22:00 to 06:00)
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
