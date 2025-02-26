@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('clients.index') }}" class="text-blue-600 hover:underline">
            &larr; Terug naar Clients
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Client Bewerken: {{ $client->name }}</h1>

            <form action="{{ route('clients.update', $client) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Naam</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="redirect_uris" class="block text-gray-700 text-sm font-bold mb-2">Redirect URIs (één per regel)</label>
                    <textarea name="redirect_uris" id="redirect_uris" rows="4" required
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('redirect_uris', implode("\n", $client->redirectUrisArray)) }}</textarea>
                    <p class="text-gray-600 text-xs mt-1">Voer elke toegestane redirect URI op een nieuwe regel in. Bijvoorbeeld: https://example.com/callback</p>
                    @error('redirect_uris')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Wijzigingen Opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
