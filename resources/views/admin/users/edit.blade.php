@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Gebruiker bewerken
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-user-edit mr-1.5 text-gray-400"></i>
                    Bewerk de gegevens van {{ $user->name }}
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4">
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Terug naar overzicht
            </a>
        </div>
    </div>

    <!-- Edit User Form Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Gebruikersgegevens
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Bewerk de onderstaande gegevens om de gebruiker bij te werken.
            </p>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Name Field -->
                <div class="sm:col-span-3">
                    <label for="name" class="block text-sm font-medium text-gray-700">Naam</label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 text-red-900 placeholder-red-300 @enderror">
                    </div>
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="sm:col-span-3">
                    <label for="email" class="block text-sm font-medium text-gray-700">E-mailadres</label>
                    <div class="mt-1">
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 text-red-900 placeholder-red-300 @enderror">
                    </div>
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="sm:col-span-3">
                    <label for="password" class="block text-sm font-medium text-gray-700">Nieuw wachtwoord</label>
                    <div class="mt-1">
                        <input type="password" name="password" id="password" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md @error('password') border-red-300 text-red-900 placeholder-red-300 @enderror">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Laat leeg om het huidige wachtwoord te behouden.</p>
                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation Field -->
                <div class="sm:col-span-3">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Wachtwoord bevestigen</label>
                    <div class="mt-1">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="shadow-sm focus:ring-numblio-blue focus:border-numblio-blue block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <!-- Admin Checkbox -->
                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_admin" name="is_admin" type="checkbox" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }} class="focus:ring-numblio-blue h-4 w-4 text-numblio-blue border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_admin" class="font-medium text-gray-700">Beheerder</label>
                            <p class="text-gray-500">Geef deze gebruiker beheerdersrechten voor het SSO platform.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                    <a href="{{ route('users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue">
                        Annuleren
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue">
                        Wijzigingen opslaan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 