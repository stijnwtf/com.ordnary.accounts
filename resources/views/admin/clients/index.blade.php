@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Clients</h2>
        <p class="text-sm text-gray-600 mt-2">Beheer alle client-applicaties die verbinding maken via SSO.</p>
    </div>

    <div class="flex justify-end mb-6">
        <a href="{{ route('clients.create') }}" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <i class="fas fa-plus mr-2"></i>Nieuwe Client
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <table class="w-full">
            <thead>
            <tr class="bg-gray-50 text-left">
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Client ID</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Aangemaakt op</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @foreach($clients as $client)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->client_id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->created_at->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-3">
                        <a href="{{ route('clients.show', $client) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye mr-1"></i>Details
                        </a>
                        <a href="{{ route('clients.edit', $client) }}" class="text-green-600 hover:text-green-800">
                            <i class="fas fa-edit mr-1"></i>Bewerken
                        </a>
                        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('Weet je zeker dat je deze client wilt verwijderen?')">
                                <i class="fas fa-trash-alt mr-1"></i>Verwijderen
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach

            @if(count($clients) == 0)
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                    <i class="fas fa-info-circle text-gray-400 text-xl mb-3"></i>
                    <p>Er zijn nog geen clients toegevoegd.</p>
                </td>
            </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
