<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'document_type' => fake()->randomElement(['identity', 'passport', 'contract', 'health_certificate']),
            'document_number' => 'DOC' . fake()->unique()->numberBetween(10000, 99999),
            'expiry_date' => fake()->dateTimeBetween('now', '+5 years')->format('Y-m-d'),
            'file_path' => 'documents/doc_' . fake()->unique()->numberBetween(1, 1000) . '.pdf',
        ];
    }
}