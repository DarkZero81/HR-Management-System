@extends('layouts.app')
@section('title', 'Employees')
@section('content')
<h2 class="text-2xl font-bold mb-4">Employees</h2>
<a href="{{ route('employees.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add Employee</a>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full">
        <thead><tr class="bg-gray-50"><th class="px-4 py-2">Name</th><th class="px-4 py-2">Shift</th><th class="px-4 py-2">Salary</th><th class="px-4 py-2">Actions</th></tr></thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td class="px-4 py-2">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td class="px-4 py-2">{{ $employee->shift->shift_name ?? '-' }}</td>
                <td class="px-4 py-2">${{ number_format($employee->base_salary, 2) }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('employees.show', $employee) }}" class="text-blue-500">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $employees->links() }}
</div>
@endsection