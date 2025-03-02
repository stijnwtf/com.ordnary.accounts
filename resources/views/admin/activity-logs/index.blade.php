@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Activiteitenlogboek
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-history mr-1.5 text-gray-400"></i>
                    Overzicht van alle activiteiten in het systeem
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4 space-x-3">
            <form action="{{ route('activity-logs.destroy-all') }}" method="POST" onsubmit="return confirm('Weet je zeker dat je alle activiteitenlogs wilt verwijderen? Dit kan niet ongedaan worden gemaakt.');">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Alles wissen
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

    <!-- Filter Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-filter mr-2 text-gray-400"></i>
                Filters
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('activity-logs.index') }}" method="GET">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label for="action" class="block text-sm font-medium text-gray-700">
                            Actie
                        </label>
                        <div class="mt-1">
                            <select id="action" name="action" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Alle acties</option>
                                @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="model_type" class="block text-sm font-medium text-gray-700">
                            Model Type
                        </label>
                        <div class="mt-1">
                            <select id="model_type" name="model_type" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Alle types</option>
                                @foreach($modelTypes as $type)
                                <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                                    {{ class_basename($type) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="date_from" class="block text-sm font-medium text-gray-700">
                            Datum vanaf
                        </label>
                        <div class="mt-1">
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="date_to" class="block text-sm font-medium text-gray-700">
                            Datum tot
                        </label>
                        <div class="mt-1">
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">
                            Gebruiker ID
                        </label>
                        <div class="mt-1">
                            <input type="number" name="user_id" id="user_id" value="{{ request('user_id') }}" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Voer gebruiker ID in">
                        </div>
                    </div>

                    <div class="sm:col-span-2 flex items-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                            <i class="fas fa-search mr-2"></i>
                            Filteren
                        </button>
                        <a href="{{ route('activity-logs.index') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-list mr-2 text-gray-400"></i>
                Activiteiten
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Gebruiker
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actie
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Beschrijving
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Model
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Datum
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acties
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $log->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->user)
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium">{{ substr($log->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $log->user->email }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-500">Systeem</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $actionColors = [
                                    'create' => 'bg-green-100 text-green-800',
                                    'update' => 'bg-blue-100 text-blue-800',
                                    'delete' => 'bg-red-100 text-red-800',
                                    'login' => 'bg-purple-100 text-purple-800',
                                    'logout' => 'bg-yellow-100 text-yellow-800',
                                ];
                                $actionColor = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ Str::limit($log->description, 50) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($log->model_type)
                            <span class="text-xs text-gray-500">{{ class_basename($log->model_type) }}</span>
                            <span class="block text-xs font-medium">#{{ $log->model_id }}</span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $log->created_at->format('d-m-Y H:i:s') }}
                            <div class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('activity-logs.show', $log) }}" class="text-numblio-blue hover:text-numblio-lightblue mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('activity-logs.destroy', $log) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze log wilt verwijderen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Geen activiteiten gevonden
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection 