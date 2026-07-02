<nav x-data="{ open: false }" class="bg-white/80 border-b border-slate-200/60 backdrop-blur-md shadow-sm rounded-b-[24px] px-4 sm:px-6 lg:px-8">
    <!-- قائمة الملاحة الرئيسية (Primary Menu) -->
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between h-20 items-center">

            <!-- الشق الأيمن: الشعار والروابط السريعة -->
            <div class="flex items-center gap-8">
                <!-- الشعار المستند إلى البنية الجديدة -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl text-white shadow-md shadow-blue-500/20">
                            <i data-lucide="layers" class="w-5 h-5"></i>
                        </div>
                        <span class="font-black text-lg text-slate-900 tracking-tight">HR Engine</span>
                    </a>
                </div>

                <!-- روابط التنقل السريعة للأجهزة المكتبية -->
                <div class="hidden space-x-reverse space-x-4 sm:flex items-center">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        لوحة التحكم
                    </a>
                </div>
            </div>

            <!-- الشق الأيسر: الإشعارات وملف المستخدم المنسدل -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                <!-- زر التنبيهات وحماية النظام -->
                <button class="rounded-xl border border-slate-200 bg-slate-50 p-2.5 text-slate-500 transition hover:bg-slate-100 relative">
                    <i data-lucide="bell" class="h-4 w-4"></i>
                    <span class="absolute top-1.5 right-1.5 h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                </button>

                <!-- قائمة المستخدم المنسدلة الاحترافية -->
                <x-dropdown align="left" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/50 p-1.5 transition hover:bg-slate-100 text-right focus:outline-none">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 font-bold text-white text-sm shadow-md">
                                {{ strtoupper(substr(Auth::user()->email ?? 'U', 0, 1)) }}
                            </div>
                            <div class="hidden md:block pl-2">
                                <p class="text-xs font-black text-slate-900 leading-none">{{ Auth::user()->employee->first_name ?? 'micheal' }}</p>
                                <p class="text-[10px] text-slate-400 mt-1 font-semibold">{{ optional(Auth::user()->role)->role_name ?? 'موظف' }}</p>
                            </div>
                            <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400 me-1"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- رابط تعديل البيانات الشخصية والذاتية -->
                        <x-dropdown-link :href="route('profile.edit')" class="text-right text-sm font-semibold py-2.5 flex items-center gap-2 text-slate-700 hover:bg-slate-50">
                            <i data-lucide="user-cog" class="w-4 h-4 text-slate-400"></i>
                            <span>الملف الشخصي</span>
                        </x-dropdown-link>

                        <!-- نموذج خروج آمن وموثق -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-right text-sm font-semibold py-2.5 flex items-center gap-2 text-rose-600 hover:bg-rose-50">
                                <i data-lucide="log-out" class="w-4 h-4 text-rose-400"></i>
                                <span>تسجيل الخروج</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- شق الهامبرغر للأجهزة المحمولة -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2.5 rounded-xl text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100 focus:outline-none transition duration-150 ease-in-out">
                    <i data-lucide="menu" x-show="!open" class="w-5 h-5"></i>
                    <i data-lucide="x" x-show="open" class="w-5 h-5" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- قائمة الهواتف المحمولة المستجيبة (Responsive Menu) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden pb-4 border-t border-slate-100 mt-2">
        <div class="space-y-1 pt-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl text-right font-bold text-sm">
                لوحة التحكم
            </x-responsive-nav-link>
        </div>

        <!-- خيارات الحساب للهواتف المحمولة -->
        <div class="pt-4 pb-1 border-t border-slate-100 mt-4">
            <div class="px-4 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 font-bold text-slate-800 text-sm">
                    {{ strtoupper(substr(Auth::user()->email ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="font-black text-sm text-slate-800">{{ Auth::user()->name ?? 'micheal' }}</div>
                    <div class="font-medium text-xs text-slate-400 mt-0.5">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl text-right text-sm font-semibold">
                    الملف الشخصي
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="rounded-xl text-right text-sm font-semibold text-rose-600">
                        تسجيل الخروج
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
