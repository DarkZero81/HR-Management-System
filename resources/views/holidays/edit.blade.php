@extends('layouts.app')
@section('title', 'Edit Holiday')
@section('content')
<h2 class="text-2xl font-bold mb-4">Edit Holiday</h2>
<form method="POST" action="{{ route('holidays.update', $holiday) }}" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block mb-1">Holiday Name</label><input type="text" name="holiday_name" value="{{ old('holiday_name', $holiday->holiday_name) }}" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">Start Date</label><input type="date" name="start_date" value="{{ old('start_date', $holiday->start_date) }}" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">End Date</label><input type="date" name="end_date" value="{{ old('end_date', $holiday->end_date) }}" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">Recurring</label><input type="checkbox" name="is_recurring" value="1" {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}></div>
    </div>
    <div class="flex gap-2 mt-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('holidays.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
    </div>
</form>
@endsection
