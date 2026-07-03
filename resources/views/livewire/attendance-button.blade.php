<div class="flex items-center gap-3">
    <div>
        <button wire:click="checkInOut" class="rounded-lg bg-gradient-to-l from-cyan-500 to-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:scale-[1.01] transition transform">
            تسجيل الدوام
        </button>
        @if($message)
            <p class="mt-2 text-xs text-green-600 font-semibold">{{ $message }}</p>
        @endif
    </div>
</div>
