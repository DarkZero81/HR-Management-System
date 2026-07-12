<?php $__env->startSection('title', 'إدارة الإجازات'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 mb-4">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الإجازات</p>
            <h1 class="text-3xl font-bold text-slate-800">قائمة الإجازات</h1>
            <p class="text-sm text-slate-400 mt-1">إدارة الإجازات الرسمية والتقويم السنوي.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('holidays.calendar')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span class="hidden sm:inline">التقويم</span>
            </a>
            <a href="<?php echo e(route('profile.edit')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="hidden sm:inline">الملف الشخصي</span>
            </a>
            <a href="<?php echo e(route('holidays.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white rounded-xl font-semibold shadow-lg transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline">إضافة إجازة</span>
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($holidays->count()); ?></p>
                    <p class="text-xs text-slate-500">إجازات مسجلة</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="repeat" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($holidays->where('is_recurring', true)->count()); ?></p>
                    <p class="text-xs text-slate-500">متكررة سنوياً</p>
                </div>
            </div>
        </div>
        <div class="employee-form-card rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-violet-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e(\App\Models\Holiday::where('start_date', '>=', now())->count()); ?></p>
                    <p class="text-xs text-slate-500">قادمة</p>
                </div>
            </div>
        </div>
  <!-- يوم لنهاية السنة -->
<div class="employee-form-card rounded-2xl p-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
            <i data-lucide="sun" class="w-5 h-5 text-amber-600"></i>
        </div>
        <div>
            <?php
                $today = now()->startOfDay();
                $endOfYear = \Carbon\Carbon::create(now()->year, 12, 31)->startOfDay();
                // استخدام intval لضمان رقم صحيح بدون فواصل
                $daysToEndOfYear = intval($today->diffInDays($endOfYear));
            ?>
            <p class="text-2xl font-black text-slate-800"><?php echo e($daysToEndOfYear); ?></p>
            <p class="text-xs text-slate-500">يوم لنهاية السنة</p>
        </div>
    </div>
</div>

<!-- يوم للعطلة القادمة -->
<div class="employee-form-card rounded-2xl p-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-cyan-100 flex items-center justify-center">
            <i data-lucide="party-popper" class="w-5 h-5 text-cyan-600"></i>
        </div>
        <div>
            <?php
                $nextHoliday = \App\Models\Holiday::where('start_date', '>=', now()->toDateTimeString())
                    ->orderBy('start_date')
                    ->first();

                // تحويل الناتج لرقم صحيح
                $daysToNextHoliday = $nextHoliday
                    ? intval($today->diffInDays(\Carbon\Carbon::parse($nextHoliday->start_date)->startOfDay()))
                    : 0;
            ?>
            <p class="text-2xl font-black text-slate-800"><?php echo e($daysToNextHoliday); ?></p>
            <p class="text-xs text-slate-500">يوم للعطلة القادمة</p>
        </div>
    </div>
</div>

<!-- أيام دوام متبقية -->
<div class="employee-form-card rounded-2xl p-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
            <i data-lucide="briefcase" class="w-5 h-5 text-rose-600"></i>
        </div>
        <div>
            <?php
                $upcomingHolidaysDates = \App\Models\Holiday::where('start_date', '>=', $today)
                    ->where('start_date', '<=', $endOfYear)
                    ->get()
                    ->pluck('start_date')
                    ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
                    ->toArray();

                // حساب الأيام المفلترة وتحويلها مباشرة لرقم صحيح
                $workingDaysLeft = intval($today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($upcomingHolidaysDates) {
                    // استثناء يومي الجمعة والسبت كإجازة أسبوعية، واستثناء العطلات الرسمية القادمة
                    return !$date->isFriday() && !$date->isSaturday() && !in_array($date->format('Y-m-d'), $upcomingHolidaysDates);
                }, $endOfYear));
            ?>
            <p class="text-2xl font-black text-slate-800"><?php echo e($workingDaysLeft); ?></p>
            <p class="text-xs text-slate-500">أيام دوام متبقية</p>
        </div>
    </div>
</div>

    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">اسم الإجازة</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ البداية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">تاريخ النهاية</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">متكررة</th>
                        <th class="px-6 py-4 text-slate-600 text-right font-medium">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $holidays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $holiday): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-800 font-semibold"><?php echo e($holiday->holiday_name); ?></td>
                            <td class="px-6 py-4 text-slate-600"><?php echo e($holiday->start_date?->format('Y-m-d') ?? '—'); ?></td>
                            <td class="px-6 py-4 text-slate-600"><?php echo e($holiday->end_date?->format('Y-m-d') ?? '—'); ?></td>
                            <td class="px-6 py-4">
                                <?php if($holiday->is_recurring): ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">نعم</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">لا</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="<?php echo e(route('holidays.edit', $holiday)); ?>" class="p-2 rounded-xl bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 transition-colors" title="تعديل">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="<?php echo e(route('holidays.destroy', $holiday)); ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الإجازة؟')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="p-2 rounded-xl bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 transition-colors" title="حذف">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">لا توجد إجازات مسجلة حتى الآن.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
            <?php echo e($holidays->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Documents\Almonkez\employer_mange\resources\views/holidays/index.blade.php ENDPATH**/ ?>