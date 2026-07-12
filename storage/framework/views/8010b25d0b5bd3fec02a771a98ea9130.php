<?php $__env->startSection('title', 'حضوري الشخصي'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6 my-4">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الدوام والحضور</p>
                <h1 class="text-3xl font-bold text-slate-800">سجل حضوري الشخصي</h1>
                <p class="text-sm text-slate-400 mt-1">عرض سجل حضورك وانصرافك المسجل عبر أجهزة البصمة.</p>
            </div>
            <a href="<?php echo e(route('profile.edit')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-all border border-white/10">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="hidden sm:inline">الملف الشخصي</span>
            </a>
        </div>

        <?php
            $hasCheckedIn = $todayLog?->check_in !== null;
            $hasCheckedOut = $todayLog?->check_out !== null;
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br mb-4 from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/25">
                        <i data-lucide="log-in" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">تسجيل الدخول</h3>
                        <p class="text-xs text-slate-500">بداية الدوام الرسمي</p>
                    </div>
                </div>

                <form action="<?php echo e(route('my.attendance.checkin')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="employee_id" value="<?php echo e(auth()->user()?->employee?->id); ?>">
                    <div>
                        <label for="device_id" class="block text-sm font-semibold text-slate-700 mb-6">جهاز البصمة (اختياري)</label>
                        <select name="device_id" id="device_id"
                            class="w-full rounded-xl border-slate-200 bg-white px-8 py-2.5 text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            <option value="">— بدون جهاز —</option>
                            <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($device->id); ?>"><?php echo e($device->device_name); ?> (<?php echo e($device->ip_address); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button type="submit" <?php if($hasCheckedIn): ?> disabled <?php endif; ?>
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold shadow-lg transition-all <?php if(!$hasCheckedIn): ?> bg-gradient-to-r from-blue-500 to-teal-400 hover:from-blue-600 hover:to-teal-500 text-white shadow-blue-500/25 <?php else: ?> bg-slate-100 text-slate-500 cursor-not-allowed <?php endif; ?>">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        <?php echo e($hasCheckedIn ? 'تم تسجيل الدخول ' . $todayLog->check_in?->format('H:i') : 'تسجيل الدخول الآن'); ?>

                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/25">
                        <i data-lucide="log-out" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">تسجيل الخروج</h3>
                        <p class="text-xs text-slate-500">نهاية الدوام الرسمي</p>
                    </div>
                </div>

                <form action="<?php echo e(route('my.attendance.checkout')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="employee_id" value="<?php echo e(auth()->user()?->employee?->id); ?>">
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">حالة اليوم</span>
                            <span class="font-semibold text-slate-700">
                                <?php if($todayLog): ?>
                                    <?php
                                        $statusLabel = match($todayLog->status) {
                                            'present' => 'حاضر',
                                            'late' => 'متأخر',
                                            'absent' => 'غائب',
                                            'holiday' => 'إجازة',
                                            default => $todayLog->status
                                        };
                                        $statusColor = match($todayLog->status) {
                                            'present' => 'text-emerald-600 bg-emerald-100',
                                            'late' => 'text-amber-600 bg-amber-100',
                                            'absent' => 'text-rose-600 bg-rose-100',
                                            'holiday' => 'text-blue-600 bg-blue-100',
                                            default => 'text-slate-600 bg-slate-100'
                                        };
                                    ?>
                                    <span class="inline-flex items-center px-4 py-0.5 rounded-full text-xs font-semibold <?php echo e($statusColor); ?>">
                                        <?php echo e($statusLabel); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-400">لم يسجل بعد</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">وقت الدخول</span>
                            <span class="font-semibold text-slate-700"><?php echo e($todayLog?->check_in?->format('H:i') ?? '—'); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">وقت الخروج</span>
                            <span class="font-semibold text-slate-700"><?php echo e($todayLog?->check_out?->format('H:i') ?? '—'); ?></span>
                        </div>
                    </div>
                    <button type="submit" <?php if(!$hasCheckedIn || $hasCheckedOut): ?> disabled <?php endif; ?>
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold shadow-lg transition-all <?php if($hasCheckedIn && !$hasCheckedOut): ?> bg-gradient-to-r from-amber-500 to-orange-400 hover:from-amber-600 hover:to-orange-500 text-white shadow-amber-500/25 <?php else: ?> bg-slate-100 text-slate-500 cursor-not-allowed <?php endif; ?>">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <?php if($hasCheckedOut): ?>
                            تم تسجيل الخروج <?php echo e($todayLog->check_out?->format('H:i')); ?>

                        <?php elseif(!$hasCheckedIn): ?>
                            سجل الدخول أولاً
                        <?php else: ?>
                            تسجيل الخروج الآن
                        <?php endif; ?>
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <form method="GET" action="<?php echo e(route('my.attendance')); ?>" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">الشهر</label>
                    <input type="month" name="month" value="<?php echo e(request('month', now()->format('Y-m'))); ?>"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-teal-400 hover:to-teal-500 text-white font-semibold rounded-xl shadow-lg transition-all whitespace-nowrap">
                        <i data-lucide="search" class="w-4 h-4 inline-block ml-1"></i>
                        عرض
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i data-lucide="calendar" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-slate-800"><?php echo e($stats['total']); ?></p>
                <p class="text-sm text-slate-500 mt-2">إجمالي السجلات</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-emerald-600"><?php echo e($stats['present']); ?></p>
                <p class="text-sm text-slate-500 mt-2">أيام حضور</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i data-lucide="alarm-clock" class="w-6 h-6 text-amber-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-amber-600"><?php echo e($stats['late']); ?></p>
                <p class="text-sm text-slate-500 mt-2">أيام تأخير</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-6 h-6 text-rose-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-rose-600"><?php echo e($stats['absent']); ?></p>
                <p class="text-sm text-slate-500 mt-2">أيام غياب</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">التاريخ</th>
                            <th class="px-6 py-4">وقت الحضور</th>
                            <th class="px-6 py-4">وقت الانصراف</th>
                            <th class="px-6 py-4">الجهاز</th>
                            <th class="px-6 py-4">دقائق التأخير</th>
                            <th class="px-6 py-4">دقائق إضافي</th>
                            <th class="px-6 py-4">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium text-slate-800"><?php echo e(\Illuminate\Support\Carbon::parse($log->log_date)->format('Y-m-d')); ?></td>
                                <td class="px-6 py-4 text-slate-600"><?php echo e($log->check_in ? \Illuminate\Support\Carbon::parse($log->check_in)->format('H:i') : '-'); ?></td>
                                <td class="px-6 py-4 text-slate-600"><?php echo e($log->check_out ? \Illuminate\Support\Carbon::parse($log->check_out)->format('H:i') : '-'); ?></td>
                                <td class="px-6 py-4 text-slate-600"><?php echo e($log->device->device_name ?? '-'); ?></td>
                                <td class="px-6 py-4 text-slate-600"><?php echo e($log->late_minutes ?? 0); ?></td>
                                <td class="px-6 py-4 text-slate-600"><?php echo e($log->overtime_minutes ?? 0); ?></td>
                                <td class="px-6 py-4">
                                    <?php ($statusClasses = [
                                        'present' => 'bg-emerald-100 text-emerald-700',
                                        'late' => 'bg-amber-100 text-amber-700',
                                        'absent' => 'bg-rose-100 text-rose-700',
                                        'holiday' => 'bg-blue-100 text-blue-700',
                                    ][$log->status] ?? 'bg-slate-100 text-slate-700'); ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?php echo e($statusClasses); ?>">
                                        <?php echo e(match($log->status) { 'present' => 'حاضر', 'late' => 'متأخر', 'absent' => 'غائب', 'holiday' => 'إجازة', default => $log->status }); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400">لا يوجد سجل حضور بعد.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($logs->hasPages()): ?>
                <div class="px-6 py-4 border-t border-slate-100">
                    <?php echo e($logs->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Documents\Almonkez\employer_mange\resources\views/attendance/my_index.blade.php ENDPATH**/ ?>