@extends('layouts.app')
@section('title', 'Holidays')
@section('content')
<h2 class="text-2xl font-bold mb-4">Holidays</h2>
<a href="{{ route('holidays.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add Holiday</a>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full">
        <thead><tr class="bg-gray-50"><th class="px-4 py-2">Name</th><th class="px-4 py-2">Start</th><th class="px-4 py-2">End</th><th class="px-4 py-2">Recurring</th></tr></thead>
        <tbody>
            @foreach($holidays as $holiday)
            <tr>
                <td class="px-4 py-2">{{ $holiday->holiday_name }}</td>
                <td class="px-4 py-2">{{ $holiday->start_date }}</td>
                <td class="px-4 py-2">{{ $holiday->end_date }}</td>
                <td class="px-4 py-2">{{ $holiday->is_recurring ? 'Yes' : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $holidays->links() }}
</div>
@endsection
