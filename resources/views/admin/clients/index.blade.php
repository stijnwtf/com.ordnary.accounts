@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Clients</h1>
        <a href="{{ route('clients.create') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            Nieuwe Client
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aangemaakt op</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @foreach($clients as $client)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $client->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $client->client_id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $client->created_at->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <a href="{{ route('clients.show', $client) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                        Details
                    </a>
                    <a href="{{ route('clients.edit', $client) }}" class="text-green-600 hover:text-green-900 mr-3">
                        Bewerken
                    </a>
                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Weet je zeker dat je deze client wilt verwijderen?')">
                            Verwijderen
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
