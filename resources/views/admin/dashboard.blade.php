@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Dashboard
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-chart-line mr-1.5 text-gray-400"></i>
                    Overzicht en statistieken
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4 space-x-3">
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-user-plus mr-2"></i>
                Nieuwe gebruiker
            </a>
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Nieuwe client
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 mb-8">
        <!-- Total Users -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-numblio-blue rounded-md p-3">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Totaal gebruikers
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $stats['total_users'] }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('users.index') }}" class="font-medium text-numblio-blue hover:text-numblio-lightblue">
                        Bekijk alle gebruikers <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Admin Users -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-600 rounded-md p-3">
                        <i class="fas fa-user-shield text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Beheerders
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $stats['admin_users'] }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('users.index') }}?is_admin=1" class="font-medium text-numblio-blue hover:text-numblio-lightblue">
                        Bekijk beheerders <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Clients -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-600 rounded-md p-3">
                        <i class="fas fa-building text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Totaal clients
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $stats['total_clients'] }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('clients.index') }}" class="font-medium text-numblio-blue hover:text-numblio-lightblue">
                        Bekijk alle clients <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <i class="fas fa-key text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Actieve sessies
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $stats['total_active_sessions'] }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('sessions.index') }}" class="font-medium text-numblio-blue hover:text-numblio-lightblue">
                        Bekijk alle sessies <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Expired Sessions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-600 rounded-md p-3">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Verlopen sessies
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $stats['total_expired_sessions'] }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <div class="text-sm">
                    <form action="{{ route('sessions.destroy-expired') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="font-medium text-red-600 hover:text-red-800">
                            Opruimen <i class="fas fa-trash ml-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2 mb-8">
        <!-- Recent Users -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-user-clock mr-2 text-gray-400"></i>
                    Recente gebruikers
                </h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($recentUsers as $user)
                <li>
                    <a href="{{ route('users.show', $user) }}" class="block hover:bg-gray-50 transition duration-150">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-4 flex-shrink-0">
                                    <span class="text-sm font-medium">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-numblio-blue truncate">
                                        {{ $user->name }}
                                        @if($user->is_admin)
                                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-shield-alt mr-1"></i> Admin
                                    </span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $user->email }}
                                    </p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $user->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                @empty
                <li class="px-4 py-5 sm:px-6 text-center text-gray-500">
                    Geen recente gebruikers gevonden
                </li>
                @endforelse
            </ul>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 border-t border-gray-200">
                <div class="text-sm">
                    <a href="{{ route('users.index') }}" class="font-medium text-numblio-blue hover:text-numblio-lightblue transition duration-150">
                        Bekijk alle gebruikers <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Clients -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-building mr-2 text-gray-400"></i>
                    Recente cliënten
                </h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($recentClients as $client)
                <li>
                    <a href="{{ route('clients.show', $client) }}" class="block hover:bg-gray-50 transition duration-150">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-4 flex-shrink-0">
                                    <span class="text-sm font-medium">{{ substr($client->name, 0, 1) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-numblio-blue truncate">
                                        {{ $client->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Cliënt ID: {{ $client->id }}
                                    </p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $client->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                @empty
                <li class="px-4 py-5 sm:px-6 text-center text-gray-500">
                    Geen recente cliënten gevonden
                </li>
                @endforelse
            </ul>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 border-t border-gray-200">
                <div class="text-sm">
                    <a href="{{ route('clients.index') }}" class="font-medium text-numblio-blue hover:text-numblio-lightblue transition duration-150">
                        Bekijk alle cliënten <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sessions -->
    <div class="bg-white shadow sm:rounded-lg overflow-hidden mb-8">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-key mr-2 text-gray-400"></i>
                Recente actieve sessies
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Gebruiker
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aangemaakt
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Verloopt
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentSessions as $session)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($session->user)
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium">{{ substr($session->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $session->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $session->user->email }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-500">Onbekend</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($session->client)
                            <div class="text-sm font-medium text-gray-900">{{ $session->client->name }}</div>
                            <div class="text-sm text-gray-500">ID: {{ $session->client_id }}</div>
                            @else
                            <span class="text-gray-500">Onbekend (ID: {{ $session->client_id }})</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $session->created_at->format('d-m-Y H:i') }}
                            <div class="text-xs text-gray-400">{{ $session->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($session->expires_at)
                            {{ $session->expires_at->format('d-m-Y H:i') }}
                            <div class="text-xs text-gray-400">{{ $session->expires_at->diffForHumans() }}</div>
                            @else
                            <span class="text-gray-400">Onbekend</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(!$session->expires_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Onbekend
                            </span>
                            @elseif($session->expires_at->isPast())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Verlopen
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Actief
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Geen recente sessies gevonden
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 border-t border-gray-200">
            <div class="text-sm">
                <a href="{{ route('sessions.index') }}" class="font-medium text-numblio-blue hover:text-numblio-lightblue">
                    Bekijk alle sessies <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Sessions per Client -->
    <div class="bg-white shadow sm:rounded-lg overflow-hidden mb-8">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-chart-pie mr-2 text-gray-400"></i>
                Sessies per client (Top 5)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aantal sessies
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sessionsPerClient as $sessionStat)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($sessionStat->client)
                            <div class="text-sm font-medium text-gray-900">{{ $sessionStat->client->name }}</div>
                            <div class="text-sm text-gray-500">ID: {{ $sessionStat->client_id }}</div>
                            @else
                            <span class="text-gray-500">Onbekend (ID: {{ $sessionStat->client_id }})</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $sessionStat->total }} sessies
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                            Geen sessiegegevens beschikbaar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
