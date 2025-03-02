@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Activiteitenlog Details
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-history mr-1.5 text-gray-400"></i>
                    Details van activiteitenlog #{{ $activityLog->id }}
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4 space-x-3">
            <form action="{{ route('activity-logs.destroy', $activityLog) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze log wilt verwijderen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Verwijderen
                </button>
            </form>
            <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar overzicht
            </a>
        </div>
    </div>

    <!-- Activity Log Details Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center">
                @if($activityLog->user)
                <div class="h-12 w-12 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-4">
                    <span class="text-lg font-medium">{{ substr($activityLog->user->name, 0, 1) }}</span>
                </div>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $activityLog->user->name }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1.5 text-gray-400"></i>
                        {{ $activityLog->created_at->format('d-m-Y H:i:s') }} ({{ $activityLog->created_at->diffForHumans() }})
                    </p>
                </div>
                @else
                <div class="h-12 w-12 rounded-full bg-gray-300 text-white flex items-center justify-center mr-4">
                    <i class="fas fa-cog text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Systeem
                    </h3>
                    <p class="text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1.5 text-gray-400"></i>
                        {{ $activityLog->created_at->format('d-m-Y H:i:s') }} ({{ $activityLog->created_at->diffForHumans() }})
                    </p>
                </div>
                @endif
                <div class="ml-auto">
                    @php
                        $actionColors = [
                            'create' => 'bg-green-100 text-green-800',
                            'update' => 'bg-blue-100 text-blue-800',
                            'delete' => 'bg-red-100 text-red-800',
                            'login' => 'bg-purple-100 text-purple-800',
                            'logout' => 'bg-yellow-100 text-yellow-800',
                        ];
                        $actionColor = $actionColors[$activityLog->action] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $actionColor }}">
                        {{ ucfirst($activityLog->action) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Log ID
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $activityLog->id }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Gebruiker
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($activityLog->user)
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-3">
                                <span class="text-xs font-medium">{{ substr($activityLog->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="font-medium">{{ $activityLog->user->name }}</div>
                                <div class="text-gray-500">{{ $activityLog->user->email }}</div>
                            </div>
                            <a href="{{ route('users.show', $activityLog->user) }}" class="ml-3 text-numblio-blue hover:text-numblio-lightblue">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        @else
                        <span class="text-gray-500">Systeem</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Actie
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                            {{ ucfirst($activityLog->action) }}
                        </span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Beschrijving
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $activityLog->description }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Model Type
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($activityLog->model_type)
                        {{ class_basename($activityLog->model_type) }}
                        @else
                        <span class="text-gray-500">-</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Model ID
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($activityLog->model_id)
                        {{ $activityLog->model_id }}
                        @else
                        <span class="text-gray-500">-</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        IP Adres
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $activityLog->ip_address ?? '-' }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        User Agent
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="max-h-32 overflow-y-auto">
                            {{ $activityLog->user_agent ?? '-' }}
                        </div>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Aangemaakt op
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $activityLog->created_at->format('d-m-Y H:i:s') }}
                        <span class="text-gray-500">({{ $activityLog->created_at->diffForHumans() }})</span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection 