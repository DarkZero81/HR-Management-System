<div class="mt-4">
    <form wire:submit.prevent="upload" class="flex flex-col gap-3">
        <div class="grid gap-3 sm:grid-cols-2">
            <select wire:model="document_type" class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none">
                <option value="identity">هوية</option>
                <option value="contract">عقد</option>
                <option value="certificate">شهادة</option>
                <option value="other">أخرى</option>
            </select>
            <input wire:model="document_number" type="text" placeholder="رقم الوثيقة (اختياري)" class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none" />
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <input wire:model="expiry_date" type="date" class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none" />
            <input wire:model="file" type="file" class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm outline-none" />
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-2xl bg-cyan-500 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-600 transition">رفع</button>
            @if($message)
                <p class="text-sm text-green-600 font-semibold">{{ $message }}</p>
            @endif
        </div>
    </form>
</div>
