@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Sessiedetails
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-key mr-1.5 text-gray-400"></i>
                    Details van sessie #{{ $session->id }}
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4 space-x-3">
            <form action="{{ route('sessions.destroy', $session) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze sessie wilt beëindigen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>
                    Sessie beëindigen
                </button>
            </form>
            <a href="{{ route('sessions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar overzicht
            </a>
        </div>
    </div>

    <!-- Session Details Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-4">
                    <span class="text-lg font-medium">{{ $session->user ? substr($session->user->name, 0, 1) : '?' }}</span>
                </div>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $session->user ? $session->user->name : 'Onbekende gebruiker' }}
                        @if(!$session->expires_at)
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Onbekend
                        </span>
                        @elseif($session->expires_at->isPast())
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Verlopen
                        </span>
                        @else
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Actief
                        </span>
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1.5 text-gray-400"></i>
                        Aangemaakt op {{ $session->created_at->format('d-m-Y') }} om {{ $session->created_at->format('H:i') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Sessie ID
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $session->id }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Gebruiker
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($session->user)
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-3">
                                <span class="text-xs font-medium">{{ substr($session->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="font-medium">{{ $session->user->name }}</div>
                                <div class="text-gray-500">{{ $session->user->email }}</div>
                            </div>
                            <a href="{{ route('users.show', $session->user) }}" class="ml-3 text-numblio-blue hover:text-numblio-lightblue">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        @else
                        <span class="text-gray-500">Onbekend</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Client
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($session->client)
                        <div class="flex items-center">
                            <div>
                                <div class="font-medium">{{ $session->client->name }}</div>
                                <div class="text-gray-500">Client ID: {{ $session->client_id }}</div>
                            </div>
                            <a href="{{ route('clients.show', $session->client) }}" class="ml-3 text-numblio-blue hover:text-numblio-lightblue">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        @else
                        <span class="text-gray-500">Onbekend (ID: {{ $session->client_id }})</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Token
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center">
                            <code class="bg-gray-100 px-2 py-1 rounded font-mono text-sm break-all">{{ $session->token }}</code>
                            <button type="button" onclick="copyToClipboard('{{ $session->token }}')" class="ml-3 text-numblio-blue hover:text-numblio-lightblue">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Aangemaakt op
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $session->created_at->format('d-m-Y H:i:s') }}
                        <span class="text-gray-500">({{ $session->created_at->diffForHumans() }})</span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Laatst bijgewerkt
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $session->updated_at->format('d-m-Y H:i:s') }}
                        <span class="text-gray-500">({{ $session->updated_at->diffForHumans() }})</span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Verloopt op
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($session->expires_at)
                        {{ $session->expires_at->format('d-m-Y H:i:s') }}
                        <span class="text-gray-500">({{ $session->expires_at->diffForHumans() }})</span>
                        
                        @if($session->expires_at->isPast())
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Verlopen
                        </span>
                        @else
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Actief
                        </span>
                        @endif
                        @else
                        <span class="text-gray-500">Onbekend</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 border-t border-gray-200">
            <form action="{{ route('sessions.destroy', $session) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze sessie wilt beëindigen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>
                    Sessie beëindigen
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        
        // Toon een melding
        alert('Token gekopieerd naar klembord');
    }
</script>
@endsection 