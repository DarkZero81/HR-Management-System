@extends('layouts.app')
@section('title', 'Devices')
@section('content')
<h2 class="text-2xl font-bold mb-4">Attendance Devices</h2>
<a href="" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add Device</a>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full">
        <thead><tr class="bg-gray-50"><th class="px-4 py-2">Name</th><th class="px-4 py-2">IP Address</th><th class="px-4 py-2">Status</th></tr></thead>
        <tbody>
            @foreach($devices as $device)
            <tr>
                <td class="px-4 py-2">{{ $device->device_name }}</td>
                <td class="px-4 py-2">{{ $device->ip_address }}</td>
                <td class="px-4 py-2">{{ ucfirst($device->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $devices->links() }}
</div>
@endsection
