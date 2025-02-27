@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Client Bewerken
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1.5 text-gray-400"></i>
                    Bewerk een bestaande client-applicatie voor SSO verbinding
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar Clients
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center">
            <div class="flex-shrink-0 h-10 w-10 rounded bg-gray-100 flex items-center justify-center mr-3">
                <i class="fas fa-globe text-gray-400"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $client->name }}
            </h3>
            <span class="ml-3 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Actief
            </span>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('clients.update', $client) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Client ID display -->
                    <div class="sm:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Client ID
                        </label>
                        <div class="mt-1">
                            <div class="flex items-center">
                                <span class="font-mono bg-gray-100 px-3 py-2 rounded text-sm w-full">{{ $client->client_id }}</span>
                                <button type="button" class="ml-2 text-numblio-blue hover:text-numblio-lightblue" onclick="copyToClipboard('{{ $client->client_id }}')" title="Kopiëren naar klembord">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Name field -->
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Naam
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                                   class="focus:ring-numblio-blue focus:border-numblio-blue block w-full pl-10 sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Een duidelijke naam die het doel van deze client beschrijft</p>
                        @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Created at display -->
                    <div class="sm:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Aangemaakt op
                        </label>
                        <div class="mt-1">
                            <div class="inline-flex items-center px-3 py-2 rounded bg-gray-100 text-sm text-gray-800">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                {{ $client->created_at->format('d-m-Y') }}
                                <span class="ml-2 text-xs text-gray-500">{{ $client->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Redirect URIs field -->
                    <div class="sm:col-span-6">
                        <label for="redirect_uris" class="block text-sm font-medium text-gray-700">
                            Redirect URIs (één per regel)
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="fas fa-link text-gray-400"></i>
                            </div>
                            <textarea id="redirect_uris" name="redirect_uris" rows="4" required
                                      class="focus:ring-numblio-blue focus:border-numblio-blue block w-full pl-10 sm:text-sm border-gray-300 rounded-md">{{ old('redirect_uris', implode("\n", $client->redirectUrisArray)) }}</textarea>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1.5 text-gray-400"></i>
                            <span>Voer elke toegestane redirect URI op een nieuwe regel in. Deze URIs moeten beginnen met https:// (met uitzondering van localhost).</span>
                        </div>
                        <div class="mt-2 p-3 bg-gray-50 rounded-md border border-gray-200 text-xs">
                            <span class="font-medium">Let op:</span> Het wijzigen van redirect URIs kan bestaande integraties verbreken.
                        </div>
                        @error('redirect_uris')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200 mt-6">
                    <div class="flex justify-end">
                        <a href="{{ route('clients.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue">
                            Annuleren
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue">
                            <i class="fas fa-save mr-2"></i>
                            Wijzigingen Opslaan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show temporary success tooltip
            const button = event.currentTarget;
            const originalIcon = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check text-green-500"></i>';
            setTimeout(() => {
                button.innerHTML = originalIcon;
            }, 1500);
        });
    }
</script>
@endsection
