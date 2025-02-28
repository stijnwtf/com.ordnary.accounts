@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header blijft hetzelfde -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">
                Nieuwe Client
            </h2>
            <p class="mt-1 text-sm text-gray-500 flex items-center">
                <i class="fas fa-info-circle mr-1.5"></i>
                Voeg een nieuwe client-applicatie toe voor SSO verbinding
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar Clients
            </a>
        </div>
    </div>

    <!-- Form Card met verbeterde inputs -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Client Informatie
            </h3>
        </div>

        <div class="px-6 py-6">
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <div class="space-y-8">
                    <!-- Verbeterd Naam Inputveld -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-900 mb-1">
                            Naam
                        </label>
                        <div class="group relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 group-focus-within:text-numblio-blue transition-colors duration-200">
                                <i class="fas fa-tag"></i>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                                      placeholder="https://example.com/callback">{{ old('redirect_uris') }}</textarea>
                        </div>

                        <!-- Voorbeeld box -->
                        <div class="mt-4 bg-indigo-50 rounded-lg overflow-hidden">
                            <div class="p-4 border-b border-indigo-100 bg-indigo-100 bg-opacity-50">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-indigo-500 mr-2"></i>
                                    <h4 class="font-medium text-indigo-900 text-sm">Hoe werken Redirect URIs?</h4>
                                </div>
                            </div>
                            <div class="p-4">
                                <ul class="text-sm text-indigo-800 space-y-2">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-indigo-500 mt-0.5 mr-2"></i>
                                        <span>Elke URI op een nieuwe regel</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-indigo-500 mt-0.5 mr-2"></i>
                                        <span>Begin met https:// (behalve localhost)</span>
                                    </li>
                                </ul>

                                <div class="mt-3 bg-white rounded-md border border-indigo-100 overflow-hidden">
                                    <div class="px-3 py-2 bg-indigo-50 border-b border-indigo-100">
                                        <span class="text-xs font-medium text-indigo-800">Voorbeelden:</span>
                                    </div>
                                    <div class="p-3 font-mono text-xs text-gray-700 bg-opacity-50">
                                        https://example.com/callback<br>
                                        https://app.example.com/oauth/callback<br>
                                        http://localhost:3000/callback
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
                            Client Aanmaken
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Voeg deze stijlen toe aan je CSS */
    input:focus::placeholder,
    textarea:focus::placeholder {
        @apply text-numblio-blue opacity-70;
    }

    .group:focus-within .ring-1 {
        @apply ring-2;
    }

    /* Subtiele animatie bij focus */
    input:focus,
    textarea:focus {
        @apply transform scale-[1.01] transition-transform duration-100;
    }
</style>
@endsection
