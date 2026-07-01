@extends('layouts.app')
@section('title', 'Documents')
@section('content')
<h2 class="text-2xl font-bold mb-4">Documents</h2>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full">
        <thead><tr class="bg-gray-50"><th class="px-4 py-2">Employee</th><th class="px-4 py-2">Type</th><th class="px-4 py-2">Number</th><th class="px-4 py-2">Expiry</th></tr></thead>
        <tbody>
            @foreach($documents as $doc)
            <tr>
                <td class="px-4 py-2">{{ $doc->employee->first_name ?? '-' }} {{ $doc->employee->last_name ?? '' }}</td>
                <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</td>
                <td class="px-4 py-2">{{ $doc->document_number }}</td>
                <td class="px-4 py-2">{{ $doc->expiry_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $documents->links() }}
</div>
@endsection
