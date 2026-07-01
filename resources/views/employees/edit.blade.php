@extends('layouts.app')
@section('title', 'Edit Employee')
@section('content')
<h2 class="text-2xl font-bold mb-4">Edit Employee</h2>
<form method="POST" action="{{ route('employees.update', $employee) }}" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block mb-1">First Name</label><input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">Last Name</label><input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">National ID</label><input type="text" name="national_id" value="{{ old('national_id', $employee->national_id) }}" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">Phone</label><input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="border p-2 w-full"></div>
        <div><label class="block mb-1">Base Salary</label><input type="number" step="0.01" name="base_salary" value="{{ old('base_salary', $employee->base_salary) }}" class="border p-2 w-full" required></div>
        <div><label class="block mb-1">Join Date</label><input type="date" name="join_date" value="{{ old('join_date', $employee->join_date) }}" class="border p-2 w-full" required></div>
    </div>
    <div class="flex gap-2 mt-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('employees.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
    </div>
</form>
@endsection
