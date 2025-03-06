@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Instellingen
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-cog mr-1.5 text-gray-400"></i>
                    Systeeminstellingen en configuratie
                </div>
            </div>
        </div>
        <div class="mt-5 flex md:mt-0 md:ml-4 space-x-3">
            <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue transition-colors duration-200">
                    <i class="fas fa-broom mr-2"></i>
                    Cache wissen
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

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <!-- Applicatie Instellingen -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-sliders-h mr-2 text-gray-400"></i>
                    Applicatie Instellingen
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Configureer de basisinstellingen van de applicatie.
                </p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="session_lifetime" class="block text-sm font-medium text-gray-700">
                                Sessie Levensduur (minuten)
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <input type="number" name="session_lifetime" id="session_lifetime" class="block w-full pl-11 pr-4 py-3 border-0 ring-1 ring-gray-300 rounded-lg text-gray-900 shadow-sm focus:ring-2 focus:ring-numblio-blue focus:outline-none transition-all duration-200" value="{{ $settings['session_lifetime'] }}">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Aantal minuten dat een gebruiker ingelogd blijft voordat de sessie verloopt.
                            </p>
                            @error('session_lifetime')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700">
                                Tijdzone
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-globe text-gray-400"></i>
                                </div>
                                <select name="timezone" id="timezone" class="block w-full pl-11 pr-4 py-3 border-0 ring-1 ring-gray-300 rounded-lg text-gray-900 shadow-sm focus:ring-2 focus:ring-numblio-blue focus:outline-none transition-all duration-200">
                                    <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="Europe/Amsterdam" {{ $settings['timezone'] == 'Europe/Amsterdam' ? 'selected' : '' }}>Europe/Amsterdam</option>
                                    <option value="Europe/London" {{ $settings['timezone'] == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                    <option value="America/New_York" {{ $settings['timezone'] == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                    <option value="Asia/Tokyo" {{ $settings['timezone'] == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                                </select>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                De tijdzone die gebruikt wordt voor datums en tijden in de applicatie.
                            </p>
                            @error('timezone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="locale" class="block text-sm font-medium text-gray-700">
                                Taal
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-language text-gray-400"></i>
                                </div>
                                <select name="locale" id="locale" class="focus:ring-numblio-blue focus:border-numblio-blue block w-full pl-10 sm:text-sm border-gray-300 rounded-md">
                                    <option value="nl" {{ $settings['locale'] == 'nl' ? 'selected' : '' }}>Nederlands</option>
                                    <option value="en" {{ $settings['locale'] == 'en' ? 'selected' : '' }}>Engels</option>
                                    <option value="de" {{ $settings['locale'] == 'de' ? 'selected' : '' }}>Duits</option>
                                    <option value="fr" {{ $settings['locale'] == 'fr' ? 'selected' : '' }}>Frans</option>
                                </select>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                De standaardtaal voor de applicatie.
                            </p>
                            @error('locale')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-5">
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-numblio-blue hover:bg-numblio-lightblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-numblio-blue">
                                    <i class="fas fa-save mr-2"></i>
                                    Instellingen opslaan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Systeem Informatie -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                    Systeem Informatie
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Informatie over de huidige systeemconfiguratie.
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Applicatie Naam
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $settings['app_name'] }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Omgeving
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $settings['app_env'] == 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($settings['app_env']) }}
                            </span>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Debug Modus
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $settings['app_debug'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $settings['app_debug'] ? 'Ingeschakeld' : 'Uitgeschakeld' }}
                            </span>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            URL
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $settings['app_url'] }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            PHP Versie
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $systemInfo['php_version'] }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Laravel Versie
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $systemInfo['laravel_version'] }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Server
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $systemInfo['server'] }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Database
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ ucfirst($systemInfo['database']) }} ({{ $systemInfo['database_name'] }})
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Cache Driver
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $settings['cache_driver'] }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Queue Connection
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $settings['queue_connection'] }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
