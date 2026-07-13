<?php $__env->startSection('title', 'تعديل سجل حضور'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.35em] text-slate-400">الدوام والحضور</p>
            <h1 class="text-3xl font-bold text-slate-800">تعديل سجل الحضور</h1>
            <p class="text-sm text-slate-400 mt-1">صحح بيانات السجل يدوياً عند الحاجة.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <form method="POST" action="<?php echo e(route('attendance.update', ['attendance' => $log->id])); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">الموظف</label>
                        <input type="text" value="<?php echo e($log->employee?->full_name ?? '—'); ?>" disabled
                            class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">التاريخ</label>
                        <input type="text" value="<?php echo e($log->log_date); ?>" disabled
                            class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">وقت الدخول (HH:MM)</label>
                        <input type="text" name="check_in" value="<?php echo e($log->check_in ? \Carbon\Carbon::parse($log->check_in)->format('H:i') : ''); ?>" placeholder="09:00"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">وقت الخروج (HH:MM)</label>
                        <input type="text" name="check_out" value="<?php echo e($log->check_out ? \Carbon\Carbon::parse($log->check_out)->format('H:i') : ''); ?>" placeholder="17:00"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">دقائق التأخير</label>
                        <input type="number" name="late_minutes" value="<?php echo e($log->late_minutes); ?>" min="0"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">دقائق إضافية</label>
                        <input type="number" name="overtime_minutes" value="<?php echo e($log->overtime_minutes); ?>" min="0"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">الحالة</label>
                        <select name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                            <?php $__currentLoopData = ['present'=>'حاضر','late'=>'متأخر','absent'=>'غائب','holiday'=>'إجازة']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php echo e($log->status == $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">جهاز البصمة</label>
                        <select name="device_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-800">
                            <option value="">— بدون جهاز —</option>
                            <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($device->id); ?>" <?php echo e($log->device_id == $device->id ? 'selected' : ''); ?>><?php echo e($device->device_name); ?> (<?php echo e($device->ip_address); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-teal-400 hover:to-teal-500 text-white font-semibold rounded-xl shadow-lg transition-all">
                        حفظ التعديلات
                    </button>
                    <a href="<?php echo e(route('attendance.index')); ?>" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-all">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Documents\Almonkez\employer_mange\resources\views/attendance/edit.blade.php ENDPATH**/ ?>