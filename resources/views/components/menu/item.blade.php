{{--
============================================================
مكون: عنصر القائمة (Menu Item)
============================================================
يعرض عنصراً فردياً في القائمة مع إمكانية التحكم بالظهور
--}}

@props([
    'item' => null,           // كائن العنصر
    'level' => 0,             // مستوى التداخل (للتصميم)
    'isSubmenu' => false      // هل هو داخل قائمة فرعية؟
])

@if($item && $item->isVisible())
    {{-- عنوان المجموعة (إن وجد) --}}
    @if($item->getTitle())
        <li class="menu-title {{ $level > 0 ? 'sub-menu-title' : '' }}">
            <span>{{ $item->getTitle() }}</span>
        </li>
    @endif

    {{-- عنصر القائمة --}}
    <li class="{{ request()->fullUrlIs($item->getLink()) || route_is($item->getRoute()) ? 'active' : '' }}
               {{ $level > 0 ? 'sub-menu-item' : '' }}">
        <a href="{{ $item->getLink() ?? ($item->getRoute() ?? '#') }}"
           class="{{ $level > 0 ? 'sub-menu-link' : '' }}"
           @if($item->getTarget()) target="{{ $item->getTarget() }}" @endif>

            {{-- الأيقونة --}}
            @if($item->getIcon())
                <i class="la la-{{ $item->getIcon() }}"></i>
            @endif

            {{-- النص --}}
            <span>{{ $item->getLabel() }}</span>

            {{-- إشارة العنصر النشط (اختياري) --}}
            @if(request()->fullUrlIs($item->getLink()) || route_is($item->getRoute()))
                <span class="menu-active-indicator"></span>
            @endif
        </a>
    </li>
@endif
