@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header blijft hetzelfde -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">
                Client Bewerken
            </h2>
            <p class="mt-1 text-sm text-gray-500 flex items-center">
                <i class="fas fa-info-circle mr-1.5"></i>
                Bewerk een bestaande client-applicatie voor SSO verbinding
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar Clients
            </a>
        </div>
    </div>

    <!-- Client Card met verbeterde inputs -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <!-- Client header -->
        <div class="px-6 py-5 border-b border-gray-200 flex items-center">
            <div class="h-10 w-10 flex-shrink-0 bg-gradient-to-br from-numblio-blue to-numblio-lightblue rounded-lg flex items-center justify-center text-white">
                <i class="fas fa-globe"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    {{ $client->name }}
                    <div class="ml-3 px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800 inline-flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                        Actief
                    </div>
                </h3>
                <p class="text-sm text-gray-500">
                    <i class="far fa-calendar-alt mr-1.5 text-gray-400"></i>
                    Aangemaakt op {{ $client->created_at->format('d-m-Y') }} om {{ $client->created_at->format('H:i') }}
                </p>
            </div>
        </div>

        <div class="px-6 py-6">
            <form action="{{ route('clients.update', $client) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-8">
                    <!-- Client ID met kopieerfunctie -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">
                            Client ID
                        </label>
                        <div class="flex">
                            <div class="flex-grow relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                    <i class="fas fa-fingerprint"></i>
                                </div>
                                <input type="text" value="{{ $client->client_id }}" readonly
                                       class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-0 ring-1 ring-gray-200 rounded-l-lg text-gray-700 font-mono text-sm focus:outline-none focus:bg-gray-50">
                            </div>
                            <button type="button" onclick="copyToClipboard('{{ $client->client_id }}')"
                                    class="flex items-center justify-center px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-r-lg border-0 ring-1 ring-gray-200 transition-colors duration-200"
                                    id="copy-button">
                                <i class="fas fa-copy"></i>
                                <span class="ml-2 font-medium">Kopiëren</span>
                            </button>
                        </div>
                    </div>

                    <!-- Verbeterd Naam Inputveld -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-900 mb-1">
                            Naam
                        </label>
                        <div class="group relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 group-focus-within:text-numblio-blue transition-colors duration-200">
                                <i class="fas fa-tag"></i>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                                   class="block w-full pl-11 pr-4 py-3 border-0 ring-1 ring-gray-300 rounded-lg text-gray-900 shadow-sm focus:ring-2 focus:ring-numblio-blue focus:outline-none transition-all duration-200"
                                   placeholder="Voer een naam in voor de client">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none opacity-0 group-focus-within:opacity-100 transition-opacity duration-200">
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Een duidelijke naam die het doel van deze client beschrijft</p>
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Verbeterd Redirect URIs Textareafield -->
                    <div>
                        <label for="redirect_uris" class="block text-sm font-medium text-gray-900 mb-1">
                            Redirect URIs
                        </label>
                        <div class="group relative">
                            <div class="absolute top-3 left-4 flex items-start pointer-events-none text-gray-500 group-focus-within:text-numblio-blue transition-colors duration-200">
                                <i class="fas fa-link"></i>
                            </div>
                            <textarea id="redirect_uris" name="redirect_uris" rows="4" required
                                      class="block w-full pl-11 pr-4 py-3 border-0 ring-1 ring-gray-300 rounded-lg text-gray-900 shadow-sm focus:ring-2 focus:ring-numblio-blue resize-y focus:outline-none transition-all duration-200"
                                      placeholder="https://example.com/callback">{{ old('redirect_uris', implode("\n", $client->redirectUrisArray)) }}</textarea>
                        </div>

                        <!-- Waarschuwing box -->
                        <div class="mt-4 rounded-lg overflow-hidden bg-amber-50 border border-amber-100">
                            <div class="p-4 flex items-start">
                                <div class="flex-shrink-0 w-5 h-5 text-amber-500">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-amber-800">Wees voorzichtig bij het bewerken</h4>
                                    <div class="mt-2 text-sm text-amber-700">
                                        <p>Het wijzigen van redirect URIs kan bestaande integraties verbreken. Zorg ervoor dat elke URI op een nieuwe regel staat en begint met https:// (behalve localhost).</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('redirect_uris')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 mt-8">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('clients.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-numblio-blue transition-colors duration-200">
                            Annuleren
                        </a>
                        <button type="submit" class="px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-numblio-blue transition-colors duration-200">
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
            // Verander knop tijdelijk
            const button = document.getElementById('copy-button');
            const originalHTML = button.innerHTML;

            // Animeer de knop
            button.classList.add('bg-green-100', 'text-green-700', 'ring-green-200');
            button.innerHTML = '<i class="fas fa-check"></i><span class="ml-2 font-medium">Gekopieerd!</span>';

            // Herstel na 2 seconden
            setTimeout(() => {
                button.classList.remove('bg-green-100', 'text-green-700', 'ring-green-200');
                button.innerHTML = originalHTML;
            }, 2000);

            // Toon een toast notification
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-md flex items-center transform transition-all duration-300 translate-y-2 opacity-0';
            toast.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-2"></i> Client ID gekopieerd naar klembord';
            document.body.appendChild(toast);

            // Animeer toast in
            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 10);

            // Animeer toast uit
            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        });
    }
</script>

<style>
    /* Extra CSS voor mooiere inputs */
    input, textarea {
        transition: all 0.2s ease;
    }

    input:focus,
    textarea:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    /* Subtiele highlight effect bij hover */
    input:hover,
    textarea:hover {
        @apply ring-gray-400;
    }

    /* Animatie voor het checkmark icoon */
    .group:focus-within .opacity-0 {
        animation: fadeIn 0.3s ease forwards;
    }

    @keyframes fadeIn {
        0% { opacity: 0; transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>
@endsection
