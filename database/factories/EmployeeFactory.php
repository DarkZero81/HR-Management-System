<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            // توليد حساب مستخدم تلقائياً وربطه بالموظف كمفتاح أجنبي
            'user_id' => User::factory(),
            // ربط الموظف بوردية عشوائية من الـ Shifts المتوفرة بقاعدة البيانات
            'shift_id' => Shift::inRandomOrder()->first()?->id ?? Shift::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'national_id' => $this->faker->unique()->numerify('01#########'), // محاكاة الرقم الوطني السوري
            'phone' => $this->faker->phoneNumber(),
            'base_salary' => $this->faker->randomFloat(2, 500000, 2500000), // رواتب افتراضية منطقية للعملة المحلية
            'bank_account_iban' => $this->faker->iban('SY'),
            'join_date' => $this->faker->date('Y-m-d', '-1 years'), // تاريخ تعيين خلال السنة الماضية
            'vacation_balance' => 21,
            'performance_score' => $this->faker->randomFloat(2, 1, 5), // تقييم من 1 إلى 5
        ];
    }
}
