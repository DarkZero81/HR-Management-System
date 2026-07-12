<?php
    $user = auth()->user();
    $userRole = optional($user->role)->role_name ?? 'موظف';
    $isAdminRole = in_array($userRole, ['admin', 'manager'], true);
    $latestRequests = \App\Models\HrTransaction::query()
        ->where('employee_id', $user?->employee?->id)
        ->latest()
        ->take(5)
        ->get();
    $unreadNotifications = $latestRequests->where('status', 'pending')->count();
?>

<header class="hidden lg:flex items-center justify-between h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-white/5 px-6 sticky top-0 z-40">
    
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-teal-400 flex items-center justify-center shadow-lg">
                <i data-lucide="briefcase" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h1 class="font-bold text-sm text-slate-800 dark:text-white leading-tight">HR Engine</h1>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 leading-tight">نظام الموارد البشرية</p>
            </div>
        </div>
        <div class="h-6 w-px bg-slate-200 dark:bg-white/10"></div>
        <div class="text-sm font-semibold text-slate-600 dark:text-slate-300">
            <?php echo $__env->yieldContent('header-title', ''); ?>
        </div>
    </div>

    
    <div class="flex items-center gap-2">
        
        <button id="themeToggleDesktop" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="تبديل المظهر">
            <i data-lucide="sun" class="w-5 h-5 text-amber-500 theme-icon-dark hidden"></i>
            <i data-lucide="moon" class="w-5 h-5 text-slate-600 theme-icon-light hidden"></i>
        </button>

        
        <div class="relative">
            <button id="notificationsToggleDesktop" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors relative" title="الإشعارات">
                <i data-lucide="bell" class="w-5 h-5 text-slate-600 dark:text-slate-300"></i>
                <?php if($unreadNotifications > 0): ?>
                    <span class="absolute -top-0.5 -left-0.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center"><?php echo e($unreadNotifications); ?></span>
                <?php endif; ?>
            </button>
            <div id="notificationsDropdownDesktop" class="hidden absolute top-full left-0 mt-2 w-80 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50">
                <div class="p-3 border-b border-slate-100 dark:border-white/10 flex items-center justify-between">
                    <p class="text-sm font-bold text-slate-800 dark:text-white">آخر الطلبات</p>
                    <a href="<?php echo e(route('my.requests.index')); ?>" class="text-xs text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 font-medium">عرض الكل</a>
                </div>
                <div class="max-h-72 overflow-y-auto">
                    <?php $__empty_1 = true; $__currentLoopData = $latestRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('my.requests.index')); ?>" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border-b border-slate-100 dark:border-white/5 last:border-0">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-800 dark:text-white"><?php echo e(match($req->transaction_type) { 'leave' => 'إجازة', 'permission' => 'إذن', 'promotion' => 'ترقية', 'penalty' => 'جزاء', 'transfer' => 'نقل', default => $req->transaction_type }); ?></span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full <?php echo e($req->status === 'pending' ? 'bg-amber-100 text-amber-700' : ($req->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700')); ?>"><?php echo e($req->status); ?></span>
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1"><?php echo e($req->start_date_time?->format('Y-m-d') ?? '—'); ?></p>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="px-4 py-6 text-center text-slate-400 dark:text-slate-500 text-sm">لا توجد طلبات حالياً</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="relative">
            <button id="profileToggleDesktop" class="flex items-center gap-3 p-1.5 pr-3 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                    <span class="text-xs font-black text-white"><?php echo e(strtoupper(substr($user->email ?? 'U', 0, 1))); ?></span>
                </div>
                <div class="text-right hidden xl:block">
                    <p class="text-sm font-bold text-slate-800 dark:text-white leading-tight"><?php echo e($user->name ?? $user->email); ?></p>
                    <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 leading-tight"><?php echo e($userRole); ?></p>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 hidden xl:block"></i>
            </button>
            <div id="profileDropdownDesktop" class="hidden absolute top-full left-0 mt-2 w-56 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50">
                <div class="p-3 border-b border-slate-100 dark:border-white/10">
                    <p class="text-sm font-bold text-slate-800 dark:text-white">الملف الشخصي</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500"><?php echo e($user->email); ?></p>
                </div>
                <div class="p-2">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors text-slate-700 dark:text-slate-200">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                        <span class="text-sm">بيانات الحساب</span>
                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-1">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors text-red-600 dark:text-red-400">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span class="text-sm">تسجيل الخروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\Users\DELL\Documents\Almonkez\employer_mange\resources\views/components/layout/header.blade.php ENDPATH**/ ?>