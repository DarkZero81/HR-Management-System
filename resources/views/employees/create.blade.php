@extends('layouts.app')
@section('title', 'Add Employee')
@section('content')
<h2 class="text-2xl font-bold mb-4">Add Employee</h2>
<form method="POST" action="{{ route('employees.store') }}" class="bg-white p-6 rounded shadow">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block">First Name</label><input type="text" name="first_name" class="border p-2 w-full" required></div>
        <div><label class="block">Last Name</label><input type="text" name="last_name" class="border p-2 w-full" required></div>
        <div><label class="block">National ID</label><input type="text" name="national_id" class="border p-2 w-full" required></div>
        <div><label class="block">Phone</label><input type="text" name="phone" class="border p-2 w-full"></div>
        <div><label class="block">Base Salary</label><input type="number" step="0.01" name="base_salary" class="border p-2 w-full" required></div>
        <div><label class="block">Join Date</label><input type="date" name="join_date" class="border p-2 w-full" required></div>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Save</button>
</form>
@endsection