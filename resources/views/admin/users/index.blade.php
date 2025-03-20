@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Gebruikers
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-users mr-1.5 text-gray-400"></i>
                    Beheer gebruikers van het SSO platform
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4">
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-user-plus mr-2"></i>
                Nieuwe Gebruiker
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
    <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-100 shadow-sm animate-fadeIn">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <span class="sr-only">Dismiss</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Alert -->
    @if(session('error'))
    <div class="rounded-md bg-red-50 p-4 mb-6 border border-red-100 shadow-sm animate-fadeIn">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">
                    {{ session('error') }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <span class="sr-only">Dismiss</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Users Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Gebruikersoverzicht
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Overzicht van alle gebruikers in het systeem.
            </p>
        </div>
        <ul class="divide-y divide-gray-200">
            @forelse($users as $user)
            <li>
                <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-4">
                                <span class="text-sm font-medium">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-numblio-blue truncate">
                                    {{ $user->name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($user->is_admin)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-shield-alt mr-1"></i> Admin
                            </span>
                            @endif
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                ID: {{ $user->id }}
                            </span>
                            <span class="text-xs text-gray-500">
                                Aangemaakt: {{ $user->created_at->format('d-m-Y') }}
                            </span>
                            <div class="ml-2 flex-shrink-0 flex">
                                <a href="{{ route('users.edit', $user) }}" class="p-1 text-gray-400 hover:text-numblio-blue">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('users.show', $user) }}" class="p-1 text-gray-400 hover:text-numblio-blue">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="px-4 py-5 sm:px-6 text-center text-gray-500">
                <p>Geen gebruikers gevonden.</p>
            </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
