<?php $__env->startSection('title', 'تعديل الملف الشخصي'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6 px-4 py-4" dir="rtl">
    <div class="border-b border-white/5 pb-4">
        <p class="text-xs font-black uppercase tracking-[0.35em] text-blue-400 dark:text-cyan-400">الملف الشخصي</p>
        <h1 class="text-3xl font-bold text-slate-800 mt-0.5">تعديل بياناتك الشخصية</h1>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">تحديث معلومات حسابك والبيانات الشخصية.</p>
    </div>

    <?php if(session('status') === 'profile-updated'): ?>
        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            تم تحديث الملف الشخصي بنجاح
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data" class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-5 shadow-2xl backdrop-blur-md">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PATCH'); ?>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="name" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الاسم <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="<?php echo e(old('name', $user->name)); ?>" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-rose-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">البريد الإلكتروني <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="email" value="<?php echo e(old('email', $user->email)); ?>" required class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-rose-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">رقم الهاتف</label>
                <input type="text" name="phone" id="phone" value="<?php echo e(old('phone', $user->employee->phone ?? '')); ?>" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all" placeholder="07xxxxxxxxx">
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">العنوان</label>
                <input type="text" name="address" id="address" value="<?php echo e(old('address', $user->employee->address ?? '')); ?>" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all" placeholder="المدينة / المنطقة">
            </div>

            <div class="sm:col-span-2">
                <label for="avatar" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">الصورة الشخصية</label>
                <input type="file" name="avatar" id="avatar" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-4 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-300 dark:hover:file:bg-slate-700 file:cursor-pointer">
                <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-rose-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-slate-200 dark:border-white/5 pt-4">
            <a href="<?php echo e(route('dashboard')); ?>" class="form-cancel-btn rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-white/5 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 transition active:scale-95">إلغاء</a>
            <button type="submit" class="rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 hover:opacity-95 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-600/10 transition active:scale-95 cursor-pointer inline-flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                حفظ التغييرات
            </button>
        </div>
    </form>

    <div class="employee-form-card rounded-[28px] border border-white/10 dark:border-white/5 p-6 space-y-4 shadow-2xl backdrop-blur-md">
        <h2 class="text-lg font-black text-slate-800 dark:text-white">تغيير كلمة المرور</h2>
        <p class="text-sm text-slate-500">اترك الحقول فارغة إذا كنت لا تريد تغيير كلمة المرور.</p>
        <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <input type="hidden" name="update_password" value="1">

            <div>
                <label for="current_password" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">كلمة المرور الحالية</label>
                <input type="password" name="current_password" id="current_password" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-rose-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="new_password" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">كلمة المرور الجديدة</label>
                    <input type="password" name="new_password" id="new_password" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                    <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-rose-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">تأكيد كلمة المرور الجديدة</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 px-3 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all">
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 px-4 py-2.5 text-xs font-bold shadow-lg shadow-amber-500/25 transition active:scale-95 cursor-pointer inline-flex items-center gap-2">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    تحديث كلمة المرور
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Documents\Almonkez\employer_mange\resources\views/profile/edit.blade.php ENDPATH**/ ?>