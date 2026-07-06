{{--
============================================================
مكون: الشريط الجانبي (Sidebar)
============================================================
الهيكل الرئيسي للقائمة الجانبية مع دعم القوائم المتداخلة
--}}

@props([
    'menuItems' => [],    // مصفوفة عناصر القائمة
    'twoCol' => false     // تفعيل وضع العمودين (اختياري)
])

<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">

            {{-- القائمة الأفقية (إن وجدت) --}}
            <nav class="greedys sidebar-horizantal">
                {{-- يمكنك إضافة قائمة أفقية هنا إذا أردت --}}
                @isset($horizontalMenu)
                    {{ $horizontalMenu }}
                @endisset
            </nav>

            {{-- القائمة الرأسية --}}
            <ul class="sidebar-vertical">
                @foreach($menuItems as $item)
                    @if($item->hasSubmenu())
                        {{-- قائمة منسدلة --}}
                        <x-menu.submenu :item="$item" :level="0" />
                    @else
                        {{-- عنصر عادي --}}
                        <x-menu.item :item="$item" :level="0" />
                    @endif
                @endforeach
            </ul>

        </div>
    </div>
</div>

{{-- ==========================================
وضع العمودين (Two Column) - اختياري
========================================== --}}
@if($twoCol)
<div class="two-col-bar" id="two-col-bar">
    <div class="sidebar sidebar-twocol">
        <div class="sidebar-left slimscroll">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                {{-- يمكن تعبئتها ديناميكياً حسب الحاجة --}}
            </div>
        </div>

        <div class="sidebar-right">
            <div class="tab-content" id="v-pills-tabContent">
                {{-- يمكن تعبئتها ديناميكياً حسب الحاجة --}}
            </div>
        </div>
    </div>
</div>
@endif
