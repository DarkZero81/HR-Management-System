@extends('layouts.app')
@section('title', 'Shifts')
@section('content')
<h2 class="text-2xl font-bold mb-4">Shifts</h2>
<a href="{{ route('shifts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add Shift</a>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full">
        <thead><tr class="bg-gray-50"><th class="px-4 py-2">Name</th><th class="px-4 py-2">Start</th><th class="px-4 py-2">End</th><th class="px-4 py-2">Grace (min)</th></tr></thead>
        <tbody>
            @foreach($shifts as $shift)
            <tr>
                <td class="px-4 py-2">{{ $shift->shift_name }}</td>
                <td class="px-4 py-2">{{ $shift->start_time }}</td>
                <td class="px-4 py-2">{{ $shift->end_time }}</td>
                <td class="px-4 py-2">{{ $shift->grace_period_minutes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $shifts->links() }}
</div>
@endsection
