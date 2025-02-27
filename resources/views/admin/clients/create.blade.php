@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Nieuwe Client
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1.5 text-gray-400"></i>
                    Voeg een nieuwe client-applicatie toe voor SSO verbinding
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
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-plus-circle mr-2 text-gray-400"></i>Client Informatie
            </h3>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Naam
                        </label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="redirect_uris" class="block text-sm font-medium text-gray-700">
                            Redirect URIs (één per regel)
                        </label>
                        <div class="mt-1">
                            <textarea id="redirect_uris" name="redirect_uris" rows="4" required
                                      class="focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">{{ old('redirect_uris') }}</textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Voer elke toegestane redirect URI op een nieuwe regel in. Bijvoorbeeld: https://example.com/callback</p>
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
                            Client Aanmaken
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
