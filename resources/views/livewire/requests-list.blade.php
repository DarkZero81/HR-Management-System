<div class="space-y-4">
    @forelse($transactions as $tx)
        <div class="rounded-[20px] border border-slate-200 bg-white p-4 flex items-start justify-between">
            <div>
                <p class="text-sm font-bold text-slate-900">{{ $tx->transaction_type }} — {{ $tx->employee->full_name ?? '—' }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ $tx->description }}</p>
                <p class="text-[11px] text-slate-400 mt-2">من: {{ $tx->start_date_time?->format('Y-m-d') ?? '—' }} حتى {{ $tx->end_date_time?->format('Y-m-d') ?? '—' }}</p>
            </div>
            <div class="text-left">
                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $tx->status }}</span>
                @if($tx->status === 'pending')
                    <button wire:click="cancel({{ $tx->id }})" class="mt-3 block rounded-lg bg-rose-500 px-3 py-2 text-xs text-white">إلغاء</button>
                @endif
            </div>
        </div>
    @empty
        <p class="text-sm text-slate-500">لا توجد طلبات حالياً.</p>
    @endforelse

    <div class="mt-4">{{ $transactions->links() }}</div>
</div>
