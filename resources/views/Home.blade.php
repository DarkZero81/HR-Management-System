@extends('layouts.app')
@section('title', 'Home')
@section('content')
<main class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Welcome to HR Management</h2>
        <a href="{{ route('employees.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Employee</a>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <p class="text-gray-600 mb-4">Quick overview:</p>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('employees.index') }}" class="block p-4 bg-gray-50 rounded hover:shadow cursor-pointer">
                <h3 class="text-lg font-semibold text-gray-800">Employees</h3>
                <p class="text-2xl text-blue-500">Manage your workforce</p>
            </a>
        </div>
    </div>
</main>
@endsection
