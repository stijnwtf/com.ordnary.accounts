@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
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

    <!-- Form Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg divide-y divide-gray-200">
        <div class="px-4 py-4 sm:px-6 flex items-center">
            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-numblio-blue bg-opacity-10 flex items-center justify-center mr-3">
                <i class="fas fa-plus-circle text-numblio-blue"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Client Informatie</h3>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Naam -->
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                        <label for="name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            Naam <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="focus:ring-numblio-blue focus:border-numblio-blue block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                                       placeholder="Bijvoorbeeld: Webshop Integratie">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Een duidelijke naam die het doel van deze client beschrijft</p>
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Redirect URIs -->
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                        <label for="redirect_uris" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                            Redirect URIs <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                    <i class="fas fa-link text-gray-400"></i>
                                </div>
                                <textarea id="redirect_uris" name="redirect_uris" rows="3" required
                                          class="focus:ring-numblio-blue focus:border-numblio-blue block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                                          placeholder="https://example.com/callback">{{ old('redirect_uris') }}</textarea>
                            </div>

                            <div class="mt-3 bg-blue-50 rounded-md p-3 border border-blue-100">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Instructies</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li>Voer elke URI in op een nieuwe regel</li>
                                                <li>URIs moeten beginnen met https:// (behalve localhost)</li>
                                                <li>Geldige voorbeelden:</li>
                                            </ul>
                                            <div class="mt-2 bg-white p-2 rounded border border-blue-100 font-mono text-xs text-gray-600">
                                                https://example.com/callback<br>
                                                https://app.example.com/oauth/callback<br>
                                                http://localhost:3000/callback
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('redirect_uris')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200 mt-8">
                    <div class="flex justify-end">
                        <a href="{{ route('clients.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue">
                            Annuleren
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Client Aanmaken
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
