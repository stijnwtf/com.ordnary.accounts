@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Clients
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1.5 text-gray-400"></i>
                    Beheer client-applicaties die verbinding maken via SSO
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4">
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Nieuwe Client
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
    <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-100 shadow-sm animate-fadeIn">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <span class="sr-only">Dismiss</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Clients Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <!-- Card Header w/ search and filter options -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex flex-wrap justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-list-ul mr-2 text-gray-400"></i>Overzicht
            </h3>
            <div class="mt-2 sm:mt-0">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" class="focus:ring-numblio-blue focus:border-numblio-blue block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2" placeholder="Zoek clients...">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Naam
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Client ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aangemaakt op
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acties
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($clients as $client)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $client->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    ID: {{ $client->client_id }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">{{ $client->client_id }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Actief
                            </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                            {{ $client->created_at->format('d-m-Y') }}
                            <span class="ml-2 text-xs text-gray-400">{{ $client->created_at->format('H:i') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('clients.show', $client) }}" class="text-numblio-blue hover:text-numblio-lightblue transition-colors duration-150 focus:outline-none focus:underline" title="Details bekijken">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('clients.edit', $client) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors duration-150 focus:outline-none focus:underline" title="Client bewerken">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-150 focus:outline-none focus:underline"
                                        onclick="return confirm('Weet je zeker dat je deze client wilt verwijderen?')"
                                        title="Client verwijderen">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if(count($clients) == 0)
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <div class="rounded-full bg-gray-100 p-3 mb-4">
                                <i class="fas fa-users text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-lg font-medium text-gray-500 mb-1">Geen clients gevonden</p>
                            <p class="text-sm text-gray-400 mb-4">Je hebt nog geen clients toegevoegd aan je account</p>
                            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                                <i class="fas fa-plus mr-1.5"></i>
                                Client toevoegen
                            </a>
                        </div>
                    </td>
                </tr>
                @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <nav class="flex items-center justify-between" aria-label="Pagination">
                <div class="hidden sm:block">
                    <p class="text-sm text-gray-700">
                        Toont <span class="font-medium">1</span> tot <span class="font-medium">{{ count($clients) }}</span> van <span class="font-medium">{{ count($clients) }}</span> resultaten
                    </p>
                </div>
                <div class="flex-1 flex justify-between sm:justify-end">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Vorige
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Volgende
                    </a>
                </div>
            </nav>
        </div>
    </div>
</div>
@endsection
