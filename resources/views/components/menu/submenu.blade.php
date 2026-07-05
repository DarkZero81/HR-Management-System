@props(['item'])

@php
    $userRole = optional(auth()->user()->role)->role_name;
    $hasAccess = in_array($userRole, $item->roles);

    // التحقق إذا كان أحد الأزرار الداخلية مفتوح حالياً لجعل القائمة مفتوحة تلقائياً
    $isSubmenuActive = false;
    if ($item->submenu) {
        foreach($item->submenu as $sub) {
            if (sub->route && request()->routeIs($sub->route . '*')) {
                $isSubmenuActive = true;
                break;
            }
        }
    }
@endphp

@if($hasAccess && $item->submenu)
    <li class="submenu-wrapper">
        <!-- زر فتح وإغلاق القائمة المنسدلة -->
        <button onclick="toggleSubmenu(this)" class="w-full flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-bold text-slate-400 hover:bg-white/5 hover:text-white transition">
            <div class="flex items-center gap-3">
                <i data-lucide="{{ $item->icon }}" class="h-4 w-4"></i>
                <span>{{ $item->label }}</span>
            </div>
            <!-- سهم ينقلب للأسفل أو الأعلى عند الفتح والإغلاق -->
            <i data-lucide="chevron-down" class="h-3 w-3 transition-transform duration-200 {{ $isSubmenuActive ? 'rotate-180' : '' }}"></i>
        </button>

        <!-- الأزرار الداخلية التابعة للقائمة المنسدلة -->
        <ul class="mt-1 mr-4 space-y-1 pr-2 border-r border-white/5 {{ $isSubmenuActive ? 'block' : 'hidden' }}">
            @foreach($item->submenu as $subItem)
                <x-menu.item :item="$subItem" />
            @endforeach
        </ul>
    </li>
@endif
