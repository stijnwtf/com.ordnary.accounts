@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('clients.index') }}" class="text-blue-600 hover:underline">
            &larr; Terug naar Clients
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">{{ $client->name }}</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Client Details</h2>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Client ID</p>
                        <p class="font-mono bg-gray-100 p-2 rounded">{{ $client->client_id }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Client Secret</p>
                        <p class="font-mono bg-gray-100 p-2 rounded">{{ $client->client_secret }}</p>
                    </div>

                    <div class="mb-4">
                        <form action="{{ route('clients.regenerate-credentials', $client) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600" onclick="return confirm('Weet je zeker dat je de credentials wilt vernieuwen? Bestaande integraties zullen niet meer werken.')">
                                Regenereer Credentials
                            </button>
                        </form>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold mb-2">Toegestane Redirect URIs</h2>
                    <div class="bg-gray-100 p-3 rounded">
                        @foreach($client->redirectUrisArray as $uri)
                        <div class="mb-1 font-mono text-sm">{{ $uri }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6 flex space-x-3">
                <a href="{{ route('clients.edit', $client) }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                    Bewerken
                </a>
                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700" onclick="return confirm('Weet je zeker dat je deze client wilt verwijderen?')">
                        Verwijderen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
