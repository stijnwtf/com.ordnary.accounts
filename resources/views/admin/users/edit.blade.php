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
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-4">
                    <span class="text-lg font-medium">{{ substr($user->name, 0, 1) }}</span>
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
                    <p class="text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1.5 text-gray-400"></i>
                        Aangemaakt op {{ $user->created_at->format('d-m-Y') }} om {{ $user->created_at->format('H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="px-6 py-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Naam Inputveld -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-900 mb-1">
                        Naam
                    </label>
                    <div class="group relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 group-focus-within:text-numblio-blue transition-colors duration-200">
                            <i class="fas fa-user"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="block w-full pl-11 pr-4 py-3 border-0 ring-1 ring-gray-300 rounded-lg text-gray-900 shadow-sm focus:ring-2 focus:ring-numblio-blue focus:outline-none transition-all duration-200"
                               placeholder="Voer de naam van de gebruiker in">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none opacity-0 group-focus-within:opacity-100 transition-opacity duration-200">
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">De volledige naam van de gebruiker</p>
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Inputveld -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 mb-1">
                        E-mailadres
                    </label>
                    <div class="group relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 group-focus-within:text-numblio-blue transition-colors duration-200">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="block w-full pl-11 pr-4 py-3 border-0 ring-1 ring-gray-300 rounded-lg text-gray-900 shadow-sm focus:ring-2 focus:ring-numblio-blue focus:outline-none transition-all duration-200"
                               placeholder="gebruiker@voorbeeld.com">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none opacity-0 group-focus-within:opacity-100 transition-opacity duration-200">
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Het e-mailadres wordt gebruikt voor inloggen en communicatie</p>
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Inputveld -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900 mb-1">
                        Nieuw wachtwoord
                    </label>
                    <div class="group relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 group-focus-within:text-numblio-blue transition-colors duration-200">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password"
                               class="block w-full pl-11 pr-4 py-3 border-0 ring-1 ring-gray-300 rounded-lg text-gray-900 shadow-sm focus:ring-2 focus:ring-numblio-blue focus:outline-none transition-all duration-200"
                               placeholder="••••••••">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none opacity-0 group-focus-within:opacity-100 transition-opacity duration-200">
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Laat leeg om het huidige wachtwoord te behouden</p>
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation Inputveld -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-1">
                        Wachtwoord bevestigen
                    </label>
                    <div class="group relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 group-focus-within:text-numblio-blue transition-colors duration-200">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="block w-full pl-11 pr-4 py-3 border-0 ring-1 ring-gray-300 rounded-lg text-gray-900 shadow-sm focus:ring-2 focus:ring-numblio-blue focus:outline-none transition-all duration-200"
                               placeholder="••••••••">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none opacity-0 group-focus-within:opacity-100 transition-opacity duration-200">
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Voer het nieuwe wachtwoord nogmaals in ter bevestiging</p>
                </div>

                <!-- Admin Checkbox -->
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_admin" name="is_admin" type="checkbox" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                               class="h-5 w-5 text-numblio-blue border-gray-300 rounded focus:ring-numblio-blue">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_admin" class="font-medium text-gray-900">Beheerder</label>
                        <p class="text-gray-500">Geef deze gebruiker beheerdersrechten voor het SSO platform</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                    Annuleren
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Wijzigingen opslaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 