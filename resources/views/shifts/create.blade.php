@extends('layouts.app')
@section('title', 'Create Shift')
@section('content')
<h2 class="text-2xl font-bold mb-4">Create Shift</h2>
<form method="POST" action="{{ route('shifts.store') }}" class="bg-white p-6 rounded shadow">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block mb-1">Shift Name</label><input type="text" name="shift_name" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">Start Time</label><input type="time" name="start_time" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">End Time</label><input type="time" name="end_time" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">Grace Period (minutes)</label><input type="number" name="grace_period_minutes" class="border p-2 w-full" value="0"></div>
    </div>
    <div class="flex gap-2 mt-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        <a href="{{ route('shifts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
    </div>
</form>
@endsection
