@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Notificaties
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-bell mr-1.5 text-gray-400"></i>
                    Beheer je notificaties
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4 space-x-3">
            <a href="{{ url('/admin/notifications') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-sync-alt mr-2"></i>
                Ververs pagina
            </a>
            <form action="{{ route('notifications.test') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Verstuur test notificatie
                </button>
            </form>
            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                    <i class="fas fa-check-double mr-2"></i>
                    Alles als gelezen markeren
                </button>
            </form>
            <form action="{{ route('notifications.destroy-all') }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je alle notificaties wilt verwijderen?');">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Alles verwijderen
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="rounded-md bg-green-50 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                        <span class="sr-only">Sluiten</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="rounded-md bg-red-50 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">
                    {{ session('error') }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">
                        <span class="sr-only">Sluiten</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Debug Info -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-bug mr-2 text-gray-400"></i>
                Debug Informatie
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <h4 class="text-md font-medium text-gray-900 mb-2">Notificaties in database:</h4>
            <div class="bg-gray-100 p-4 rounded overflow-auto max-h-60">
                <pre class="text-xs">{{ json_encode($notifications, JSON_PRETTY_PRINT) }}</pre>
            </div>
            
            <h4 class="text-md font-medium text-gray-900 mt-4 mb-2">Ongelezen notificaties:</h4>
            <div class="bg-gray-100 p-4 rounded overflow-auto max-h-60">
                <pre class="text-xs">Aantal: {{ $unreadCount }}</pre>
            </div>
            
            <h4 class="text-md font-medium text-gray-900 mt-4 mb-2">Gebruiker:</h4>
            <div class="bg-gray-100 p-4 rounded overflow-auto max-h-60">
                <pre class="text-xs">ID: {{ Auth::id() }}<br>Naam: {{ Auth::user()->name }}<br>Email: {{ Auth::user()->email }}</pre>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-filter mr-2 text-gray-400"></i>
                Filters
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('notifications.index') }}" method="GET">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label for="type" class="block text-sm font-medium text-gray-700">
                            Type
                        </label>
                        <div class="mt-1">
                            <select id="type" name="type" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Alle types</option>
                                @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="read" class="block text-sm font-medium text-gray-700">
                            Status
                        </label>
                        <div class="mt-1">
                            <select id="read" name="read" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Alle notificaties</option>
                                <option value="false" {{ request('read') === 'false' ? 'selected' : '' }}>Ongelezen</option>
                                <option value="true" {{ request('read') === 'true' ? 'selected' : '' }}>Gelezen</option>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-2 flex items-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                            <i class="fas fa-search mr-2"></i>
                            Filteren
                        </button>
                        <a href="{{ route('notifications.index') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-bell mr-2 text-gray-400"></i>
                Notificaties
                @if($unreadCount > 0)
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $unreadCount }} ongelezen
                </span>
                @endif
            </h3>
        </div>
        <ul class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
            <li class="relative {{ $notification->isRead() ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50">
                <a href="{{ route('notifications.show', $notification) }}" class="block">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @php
                                        $typeColors = [
                                            'info' => 'bg-blue-100 text-blue-600',
                                            'success' => 'bg-green-100 text-green-600',
                                            'warning' => 'bg-yellow-100 text-yellow-600',
                                            'error' => 'bg-red-100 text-red-600',
                                        ];
                                        $bgColor = $typeColors[$notification->type] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <div class="h-10 w-10 rounded-full {{ $bgColor }} flex items-center justify-center">
                                        <i class="fas {{ $notification->icon }}"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="text-sm font-medium text-numblio-blue truncate">
                                        {{ $notification->title }}
                                    </div>
                                    <div class="text-sm text-gray-500 line-clamp-2">
                                        {{ $notification->message }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                        @if($notification->isRead())
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-check text-xs mr-1"></i> Gelezen
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="ml-2 flex-shrink-0 flex">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bgColor }}">
                                    {{ ucfirst($notification->type) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="absolute top-4 right-4 flex space-x-2">
                    @if($notification->isRead())
                    <form action="{{ route('notifications.mark-as-unread', $notification) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-500" title="Markeer als ongelezen">
                            <i class="fas fa-envelope"></i>
                        </button>
                    </form>
                    @else
                    <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-500" title="Markeer als gelezen">
                            <i class="fas fa-envelope-open"></i>
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze notificatie wilt verwijderen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-500" title="Verwijderen">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </li>
            @empty
            <li class="px-4 py-5 sm:px-6 text-center text-gray-500">
                Geen notificaties gevonden
            </li>
            @endforelse
        </ul>
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $notifications->links() }}
        </div>
    </div>

    <!-- Send Notification Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-paper-plane mr-2 text-gray-400"></i>
                Verstuur Notificatie
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Verstuur een notificatie naar een specifieke gebruiker of alle gebruikers.
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('notifications.send') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">
                            Gebruiker (leeg laten voor alle gebruikers)
                        </label>
                        <div class="mt-1">
                            <select id="user_id" name="user_id" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Alle gebruikers</option>
                                @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="type" class="block text-sm font-medium text-gray-700">
                            Type
                        </label>
                        <div class="mt-1">
                            <select id="type" name="type" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="info">Info</option>
                                <option value="success">Success</option>
                                <option value="warning">Warning</option>
                                <option value="error">Error</option>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Titel
                        </label>
                        <div class="mt-1">
                            <input type="text" name="title" id="title" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="message" class="block text-sm font-medium text-gray-700">
                            Bericht
                        </label>
                        <div class="mt-1">
                            <textarea id="message" name="message" rows="3" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="link" class="block text-sm font-medium text-gray-700">
                            Link (optioneel)
                        </label>
                        <div class="mt-1">
                            <input type="url" name="link" id="link" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md" placeholder="https://...">
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Verstuur Notificatie
                            </button>
                            <button type="button" onclick="window.location.href='{{ route('notifications.test') }}'" class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue">
                                <i class="fas fa-vial mr-2"></i>
                                Test Notificatie
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 