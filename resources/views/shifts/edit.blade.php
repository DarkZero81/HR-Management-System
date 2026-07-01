@extends('layouts.app')
@section('title', 'Edit Shift')
@section('content')
<h2 class="text-2xl font-bold mb-4">Edit Shift</h2>
<form method="POST" action="{{ route('shifts.update', $shift) }}" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block mb-1">Shift Name</label><input type="text" name="shift_name" value="{{ old('shift_name', $shift->shift_name) }}" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">Start Time</label><input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">End Time</label><input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">Grace Period (minutes)</label><input type="number" name="grace_period_minutes" value="{{ old('grace_period_minutes', $shift->grace_period_minutes) }}" class="border p-2 w-full"></div>
    </div>
    <div class="flex gap-2 mt-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('shifts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
    </div>
</form>
@endsection
