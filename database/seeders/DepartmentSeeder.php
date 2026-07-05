<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'الموارد البشرية (HR)', 'description' => 'إدارة شؤون الموظفين، التوظيف، ومسيرات الرواتب.'],
            ['name' => 'تقنية المعلومات (IT)', 'description' => 'الدعم الفني، إدارة الخوادم، وتطوير الأنظمة البرمجية.'],
            ['name' => 'الحسابات والمالية', 'description' => 'إدارة الميزانيات، الحسابات البنكية، والتقارير المالية للشركة.'],
            ['name' => 'التسويق والمبيعات', 'description' => 'الحملات الإعلانية، جلب العملاء، وإدارة المبيعات المباشرة.'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(['name' => $department['name']], $department);
        }
    }
}
