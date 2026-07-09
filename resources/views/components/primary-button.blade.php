<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-l from-cyan-500 to-blue-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg shadow-blue-500/20 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all active:scale-[0.99]']) }}>
    {{ $slot }}
</button>
