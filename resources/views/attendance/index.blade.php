@extends('layouts.app')
@section('title', 'Attendance')
@section('content')
<h2 class="text-2xl font-bold mb-4">Attendance</h2>
<a href="" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Check In</a>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full">
        <thead><tr class="bg-gray-50"><th class="px-4 py-2">Employee</th><th class="px-4 py-2">Date</th><th class="px-4 py-2">Check In</th><th class="px-4 py-2">Check Out</th><th class="px-4 py-2">Status</th></tr></thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td class="px-4 py-2">{{ $log->employee->user->name ?? '-' }}</td>
                <td class="px-4 py-2">{{ $log->log_date }}</td>
                <td class="px-4 py-2">{{ $log->check_in?->format('H:i') ?? '-' }}</td>
                <td class="px-4 py-2">{{ $log->check_out?->format('H:i') ?? '-' }}</td>
                <td class="px-4 py-2">{{ ucfirst($log->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $logs->links() }}
</div>
@endsection
