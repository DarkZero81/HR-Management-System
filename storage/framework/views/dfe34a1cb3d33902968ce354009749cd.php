<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', config('app.name', 'HR Engine')); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.468.0/lucide.min.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen antialiased" dir="rtl">
    <div class="flex min-h-screen w-full flex-col">
        <div class="flex flex-1 items-center justify-center p-4">
            <div class="w-full max-w-md text-center">
                <div class="rounded-[32px] border border-slate-200/60 bg-white/70 p-8 glass-shell backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/60">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-rose-500 to-amber-400 mb-6 shadow-lg mx-auto">
                        <i data-lucide="alert-octagon" class="w-10 h-10 text-white"></i>
                    </div>
                    <h1 class="mt-3 text-3xl font-bold text-slate-800"><?php echo $__env->yieldContent('code', '404'); ?></h1>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-slate-200 mb-2"><?php echo $__env->yieldContent('message', 'الصفحة غير موجودة'); ?></h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">عذراً، لم نتمكن من العثور على الصفحة التي تبحث عنها. قد تكون محذوفة أو تم نقلها أو غير متاحة حالياً.</p>
                    <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-l from-cyan-500 to-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition-all hover:opacity-95">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        العودة للرئيسية
                    </a>
                </div>
                <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-6">
                    © <?php echo e(date('Y')); ?> HR Engine. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            } catch (e) {
                console.error('lucide icons init failed', e);
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\DELL\Documents\Almonkez\employer_mange\resources\views/errors/404.blade.php ENDPATH**/ ?>