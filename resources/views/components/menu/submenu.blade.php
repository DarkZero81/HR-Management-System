{{--
============================================================
مكون: القائمة المنسدلة (Submenu)
============================================================
يعرض قائمة فرعية متداخلة مع دعم مستويات متعددة
--}}

@props([
    'item' => null,
    'level' => 0
])

@if($item && $item->isVisible())
    @php
        // تحديد إذا كانت القائمة نشطة (أي عنصر فرعي نشط)
        $isActive = false;
        if($item->getSubItems()) {
            foreach($item->getSubItems() as $sub) {
                if(request()->fullUrlIs($sub->getLink()) || route_is($sub->getRoute())) {
                    $isActive = true;
                    break;
                }
                // دعم التداخل العميق
                if($sub->hasSubmenu() && $sub->getSubItems()) {
                    foreach($sub->getSubItems() as $deepSub) {
                        if(request()->fullUrlIs($deepSub->getLink()) || route_is($deepSub->getRoute())) {
                            $isActive = true;
                            break 2;
                        }
                    }
                }
            }
        }
    @endphp

    <li class="submenu {{ $level > 0 ? 'sub-submenu' : '' }}">
        {{-- عنوان القائمة الرئيسي --}}
        <a href="{{ $item->getLink() ?? ($item->getRoute() ?? '#') }}"
           class="{{ $isActive ? 'active' : '' }}
                  {{ $level > 0 ? 'sub-submenu-toggle' : '' }}">

            @if($item->getIcon())
                <i class="la la-{{ $item->getIcon() }}"></i>
            @endif

            <span>{{ $item->getLabel() }}</span>

            {{-- سهم القائمة المنسدلة --}}
            <span class="menu-arrow"></span>
        </a>

        {{-- العناصر الفرعية --}}
        <ul class="{{ $level > 0 ? 'nested-submenu' : '' }}">
            @foreach($item->getSubItems() as $subItem)
                @if($subItem->hasSubmenu())
                    {{-- إذا كان العنصر يحتوي على قائمة فرعية أخرى --}}
                    <x-menu.submenu :item="$subItem" :level="$level + 1" />
                @else
                    {{-- عنصر عادي --}}
                    <x-menu.item :item="$subItem" :level="$level + 1" :isSubmenu="true" />
                @endif
            @endforeach
        </ul>
    </li>
@endif
