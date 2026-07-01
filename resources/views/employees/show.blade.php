@extends('layouts.app')
@section('title', 'Employee Details')
@section('content')
<h2 class="text-2xl font-bold mb-4">Employee Details</h2>
<div class="bg-white p-6 rounded shadow">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div><strong>Name:</strong> {{ $employee->first_name }} {{ $employee->last_name }}</div>
        <div><strong>National ID:</strong> {{ $employee->national_id }}</div>
        <div><strong>Phone:</strong> {{ $employee->phone ?? '-' }}</div>
        <div><strong>Base Salary:</strong> ${{ number_format($employee->base_salary, 2) }}</div>
        <div><strong>Shift:</strong> {{ $employee->shift->shift_name ?? '-' }}</div>
        <div><strong>Join Date:</strong> {{ $employee->join_date }}</div>
        <div><strong>Vacation Balance:</strong> {{ $employee->vacation_balance }}</div>
        <div><strong>Performance Score:</strong> {{ $employee->performance_score }}</div>
    </div>
    <a href="{{ route('employees.index') }}" class="text-blue-500 mt-4 inline-block">Back to Employees</a>
</div>
@endsection