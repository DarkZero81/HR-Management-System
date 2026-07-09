@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 focus:border-blue-500 focus:ring-blue-500/20 rounded-xl shadow-sm bg-slate-50']) }}>
