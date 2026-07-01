@extends('layouts.app')
@section('title', 'Requests')
@section('content')
<h2 class="text-2xl font-bold mb-4">HR Requests</h2>
<a href="{{ route('requests.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">New Request</a>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full">
        <thead><tr class="bg-gray-50"><th class="px-4 py-2">Employee</th><th class="px-4 py-2">Type</th><th class="px-4 py-2">Start</th><th class="px-4 py-2">End</th><th class="px-4 py-2">Status</th></tr></thead>
        <tbody>
            @foreach($transactions as $txn)
            <tr>
                <td class="px-4 py-2">{{ $txn->employee->user->name ?? '-' }}</td>
                <td class="px-4 py-2">{{ ucfirst($txn->transaction_type) }}</td>
                <td class="px-4 py-2">{{ $txn->start_date_time }}</td>
                <td class="px-4 py-2">{{ $txn->end_date_time }}</td>
                <td class="px-4 py-2">{{ ucfirst($txn->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $transactions->links() }}
</div>
@endsection
