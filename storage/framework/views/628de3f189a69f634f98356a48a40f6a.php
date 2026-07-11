<?php $__env->startSection('title', 'تسجيل الدخول'); ?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('login.submit')); ?>" class="space-y-5">
        <?php echo csrf_field(); ?>

        <div class="text-center mb-6">
            <h2 class="text-xl font-black text-slate-900 dark:text-white">تسجيل الدخول</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">مرحباً بك مجدداً! يرجى تسجيل الدخول للمتابعة</p>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">البريد الإلكتروني</label>
                <div class="relative">
                    <i data-lucide="mail" class="absolute right-3 top-3 h-4 w-4 text-slate-400"></i>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username"
                        class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 pr-10 pl-4 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all"
                        placeholder="name@example.com">
                </div>
                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('email'),'class' => 'text-rose-500 text-xs mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('email')),'class' => 'text-rose-500 text-xs mt-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-2">كلمة المرور</label>
                <div class="relative">
                    <i data-lucide="lock" class="absolute right-3 top-3 h-4 w-4 text-slate-400"></i>
                    <input type="password" name="password" id="loginPassword" required autocomplete="current-password"
                        class="employee-form-input w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-950/60 text-sm text-slate-800 dark:text-slate-200 pr-10 pl-10 py-2.5 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/10 transition-all"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword('loginPassword', this)" class="absolute left-3 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                    </button>
                </div>
                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('password'),'class' => 'text-rose-500 text-xs mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('password')),'class' => 'text-rose-500 text-xs mt-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-cyan-500 focus:ring-cyan-500">
                <span class="text-sm text-slate-600 dark:text-slate-400">تذكرني</span>
            </label>
            <a href="<?php echo e(route('password.request')); ?>" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                نسيت كلمة المرور؟
            </a>
        </div>

        <button type="submit" class="w-full rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition-all hover:opacity-95 active:scale-[0.99] cursor-pointer">
            تسجيل الدخول
        </button>

        <p class="text-center text-sm text-slate-600 dark:text-slate-400">
            لا تملك حساباً؟
         <?php if(auth()->check() && in_array(optional(auth()->user()->role)->role_name, ['admin', 'manager'], true)): ?>
                <a href="<?php echo e(route('register')); ?>" class="font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                إنشاء حساب جديد
            </a>
         <?php else: ?>
            <p class="font-bold text-slate-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors px-4 text-xs mt-0">تواصل مع مدير النظام او المدير المسؤول ليقوم بانشاء حساب لك.</p>
         <?php endif; ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Documents\Almonkez\employer_mange\resources\views/auth/login.blade.php ENDPATH**/ ?>