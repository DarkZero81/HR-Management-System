<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $documentTypes = ['identity', 'passport', 'contract', 'health_certificate'];

        foreach ($employees as $employee) {
            $numDocuments = fake()->numberBetween(1, 2);
            for ($i = 0; $i < $numDocuments; $i++) {
                Document::create([
                    'employee_id' => $employee->id,
                    'document_type' => $documentTypes[array_rand($documentTypes)],
                    'document_number' => 'DOC' . fake()->unique()->numberBetween(10000, 99999),
                    'expiry_date' => fake()->dateTimeBetween('now', '+5 years')->format('Y-m-d'),
                    'file_path' => 'documents/employee_' . $employee->id . '_' . ($i + 1) . '.pdf',
                ]);
            }
        }
    }
}