@props(['item'])

@php
    $userRole = optional(auth()->user()->role)->role_name;
    $hasAccess = in_array('all', $item->roles) || in_array($userRole, $item->roles);
    $isActive = $item->route ? request()->routeIs($item->route . '*') : false;
@endphp

@if($hasAccess)
    <li>
        <a href="{{ $item->route ? route($item->route) : '#' }}"
           class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition duration-200 {{ $isActive ? 'bg-gradient-to-l from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <i data-lucide="{{ $item->icon }}" class="h-4 w-4"></i>
            <span>{{ $item->label }}</span>
        </a>
    </li>
@endif
