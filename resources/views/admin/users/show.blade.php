@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Gebruikersdetails
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-user mr-1.5 text-gray-400"></i>
                    Details van {{ $user->name }}
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4 space-x-3">
            <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>
                Bewerken
            </a>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar overzicht
            </a>
        </div>
    </div>

    <!-- User Details Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="h-16 w-16 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-4">
                    <span class="text-2xl font-medium">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $user->name }}
                        @if($user->is_admin)
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-shield-alt mr-1"></i> Admin
                        </span>
                        @endif
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Gebruiker ID: {{ $user->id }}
                    </p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Volledige naam
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->name }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        E-mailadres
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->email }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Status
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($user->is_admin)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Beheerder
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Gebruiker
                        </span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Aangemaakt op
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->created_at->format('d-m-Y H:i') }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Laatst bijgewerkt
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->updated_at->format('d-m-Y H:i') }}
                    </dd>
                </div>
                @if($user->email_verified_at)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        E-mail geverifieerd
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->email_verified_at->format('d-m-Y H:i') }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 border-t border-gray-200">
            <div class="flex justify-end">
                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Bewerken
                </a>
                @if(auth()->id() !== $user->id)
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i>
                        Verwijderen
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- User Sessions Section -->
    <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Actieve sessies
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Overzicht van de actieve sessies van deze gebruiker.
            </p>
        </div>
        <div class="border-t border-gray-200">
            @if($user->userSessions->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($user->userSessions as $session)
                <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-numblio-blue">
                                {{ $session->client->name ?? 'Onbekende client' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Token: {{ substr($session->access_token, 0, 15) }}...
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs text-gray-500">
                                Verloopt: {{ $session->expires_at ? $session->expires_at->format('d-m-Y H:i') : 'Onbekend' }}
                            </span>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <div class="px-4 py-5 sm:px-6 text-center text-gray-500">
                <p>Geen actieve sessies gevonden.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 