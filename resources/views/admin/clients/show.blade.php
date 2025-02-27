@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Client Details
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1.5 text-gray-400"></i>
                    Details en instellingen van de client-applicatie
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4 space-x-3">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar Clients
            </a>
            <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>
                Bewerken
            </a>
        </div>
    </div>

    <!-- Client Info Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-id-card mr-2 text-gray-400"></i>Algemene Informatie
            </h3>
        </div>

        <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Naam</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->name }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Aangemaakt op</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                            {{ $client->created_at->format('d-m-Y') }}
                            <span class="ml-2 text-xs text-gray-400">{{ $client->created_at->format('H:i') }}</span>
                        </div>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Client ID</dt>
                    <dd class="mt-1 text-sm">
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">{{ $client->client_id }}</span>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Client Secret</dt>
                    <dd class="mt-1 text-sm">
                        <div class="flex items-center">
                            <span id="clientSecret" class="font-mono bg-gray-100 px-2 py-1 rounded text-xs mr-2">••••••••••••••••••••••••••••••</span>
                            <button type="button" onclick="toggleSecret()" class="text-numblio-blue hover:text-numblio-lightblue focus:outline-none text-sm">
                                <i class="fas fa-eye"></i>
                                <span class="ml-1">Tonen</span>
                            </button>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Redirect URIs Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-link mr-2 text-gray-400"></i>Redirect URIs
            </h3>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($client->redirectUrisArray as $uri)
                <li class="py-3 flex items-center text-sm">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span class="font-mono text-gray-600">{{ $uri }}</span>
                </li>
                @empty
                <li class="py-4">
                    <div class="flex justify-center text-gray-500">
                        <p>Geen redirect URIs geconfigureerd</p>
                    </div>
                </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="mt-6 bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-red-600">
                <i class="fas fa-exclamation-triangle mr-2"></i>Gevarenzone
            </h3>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-trash-alt text-red-500 text-lg"></i>
                </div>
                <div class="ml-3 w-full">
                    <h3 class="text-sm font-medium text-gray-900">Client verwijderen</h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <p>Als je deze client verwijdert, worden alle SSO koppelingen verbroken. Deze actie kan niet ongedaan worden gemaakt.</p>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('clients.destroy', $client) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Weet je zeker dat je deze client wilt verwijderen?')"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Client verwijderen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSecret() {
        const secretEl = document.getElementById('clientSecret');
        if (secretEl.textContent.includes('•')) {
            secretEl.textContent = '{{ $client->client_secret }}';
            secretEl.nextElementSibling.innerHTML = '<i class="fas fa-eye-slash"></i><span class="ml-1">Verbergen</span>';
        } else {
            secretEl.textContent = '••••••••••••••••••••••••••••••';
            secretEl.nextElementSibling.innerHTML = '<i class="fas fa-eye"></i><span class="ml-1">Tonen</span>';
        }
    }
</script>
@endsection
