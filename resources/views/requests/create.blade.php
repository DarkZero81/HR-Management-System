@extends('layouts.app')
@section('title', 'New Request')
@section('content')
<h2 class="text-2xl font-bold mb-4">New HR Request</h2>
<form method="POST" action="{{ route('my.requests.store') }}" class="bg-white p-6 rounded shadow">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block mb-1">Type</label><select name="transaction_type" class="border p-2 w-full" required>
            @foreach($transaction_types as $type)
            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
            @endforeach
        </select></div>
        <div><label class="block mb-1">Start Date/Time</label><input type="datetime-local" name="start_date_time" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">End Date/Time</label><input type="datetime-local" name="end_date_time" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">Description</label><textarea name="description" class="border p-2 w-full"></textarea></div>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Submit</button>
</form>
@endsection
