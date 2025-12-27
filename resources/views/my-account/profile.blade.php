@extends('my-account.layout')

@section('title', lang('profile.title', [], 'profile'))

@section('content')
    <div x-data="{ activeTab: 'gegevens' }"
        x-init="() => { activeTab = localStorage.getItem('profileTab') || 'gegevens' }">
        <div class="mb-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                {{ lang('personal_info', [], 'profile') }}
            </h3>
            <p class="mt-2 text-sm text-gray-600">
                {{ lang('subtitle', [], 'profile') }}
            </p>
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-6 border-b border-gray-200">
            <div class="flex -mb-px">
                <button @click="activeTab = 'gegevens'; localStorage.setItem('profileTab', 'gegevens')"
                    :class="activeTab === 'gegevens' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center">
                    <i class="fas fa-user mr-2"></i> Gegevens
                </button>

                <button @click="activeTab = 'customization'; localStorage.setItem('profileTab', 'customization')"
                    :class="activeTab === 'customization' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center">
                    <i class="fas fa-palette mr-2"></i> Customization
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <!-- Gegevens Tab -->
        <div x-show="activeTab === 'gegevens'" x-cloak>
            <form method="POST" action="{{ route('my-account.profile') }}" class="space-y-6">
                @csrf

                <!-- Profile Picture + Name Fields -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Left: Profile Picture -->
                        <div class="flex-shrink-0">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                            <div class="relative">
                                <img class="h-24 w-24 rounded-full object-cover border-2 border-gray-200"
                                    src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=200&d=mp&r=pg"
                                    alt="{{ $user->first_name }} {{ $user->last_name }}">
                                <div class="absolute bottom-0 right-0 h-5 w-5 bg-green-400 rounded-full border-2 border-white"></div>
                            </div>
                            <div class="mt-3 flex gap-2">
                                <button type="button" 
                                    class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700">
                                    <i class="fas fa-upload mr-1"></i>Upload
                                </button>
                                <button type="button" 
                                    class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-gray-50">
                                    <i class="fas fa-trash-alt mr-1"></i>Remove
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Max 2MB</p>
                        </div>

                        <!-- Right: Name Fields -->
                        <div class="flex-1 space-y-4">
                            <!-- Username Input -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-at text-gray-400"></i>
                                    </div>
                                    <input type="text" placeholder="username" name="username" id="username"
                                        value="{{ old('username', $user->username) }}" required
                                        class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('username') ? 'border-red-500' : 'border-gray-300' }}">
                                </div>
                                @error('username')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- First Name Input -->
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.first_name', [], 'profile') }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" placeholder="John" name="first_name" id="first_name"
                                            value="{{ old('first_name', $user->first_name) }}" required
                                            class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('first_name') ? 'border-red-500' : 'border-gray-300' }}">
                                    </div>
                                    @error('first_name')
                                        <p class="mt-1 text-xs text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Last Name Input -->
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.last_name', [], 'profile') }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" placeholder="Doe" name="last_name" id="last_name"
                                            value="{{ old('last_name', $user->last_name) }}" required
                                            class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('last_name') ? 'border-red-500' : 'border-gray-300' }}">
                                    </div>
                                    @error('last_name')
                                        <p class="mt-1 text-xs text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email, Phone & Language -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-4">
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.email', [], 'profile') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input placeholder="john.doe@example.com" type="email" name="email" id="email"
                                value="{{ old('email', $user->email) }}" required
                                class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">{{ lang('fields.email_helper', [], 'profile') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Phone Number Input -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ lang('fields.phone', [], 'profile') }} <span class="text-gray-400">{{ lang('fields.phone_optional', [], 'profile') }}</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input placeholder="+31 6 12345678" type="tel" name="phone" id="phone"
                                    value="{{ old('phone', $user->phone ?? '') }}"
                                    class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}">
                            </div>
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">{{ lang('fields.phone_helper', [], 'profile') }}</p>
                        </div>

                        <!-- Language Preference -->
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.language', [], 'profile') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-globe text-gray-400"></i>
                                </div>
                                <select name="language" id="language" onchange="this.form.submit()"
                                    class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none {{ $errors->has('language') ? 'border-red-500' : 'border-gray-300' }}">
                                    @foreach(config('app.available_locales', ['en', 'nl']) as $locale)
                                        <option value="{{ $locale }}" {{ (old('language', $user->lang ?? config('app.locale')) == $locale) ? 'selected' : '' }}>
                                            @if($locale == 'nl')
                                                Nederlands
                                            @elseif($locale == 'en')
                                                English
                                            @elseif($locale == 'de')
                                                Deutsch
                                            @elseif($locale == 'fr')
                                                Français
                                            @else
                                                {{ strtoupper($locale) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ lang('fields.language_helper', [], 'profile') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">{{ lang('account_info', [], 'profile') }}</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center text-xs">
                            <i class="fas fa-calendar-alt text-gray-400 mr-2 w-5 text-center"></i>
                            <span class="text-gray-500">{{ lang('account_details.created', [], 'profile') }}</span>
                            <span class="ml-1 text-gray-700 font-medium">{{ $user->created_at->format('d-m-Y') }}</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-history text-gray-400 mr-2 w-5 text-center"></i>
                            <span class="text-gray-500">{{ lang('account_details.last_update', [], 'profile') }}</span>
                            <span class="ml-1 text-gray-700 font-medium">{{ $user->updated_at->format('d-m-Y') }}</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-sign-in-alt text-gray-400 mr-2 w-5 text-center"></i>
                            <span class="text-gray-500">{{ lang('account_details.last_login', [], 'profile') }}</span>
                            <span
                                class="ml-1 text-gray-700 font-medium">{{ now()->subDays(rand(0, 5))->format('d-m-Y H:i') }}</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2 w-5 text-center"></i>
                            <span class="text-gray-500">{{ lang('account_details.ip_address', [], 'profile') }}</span>
                            <span class="ml-1 text-gray-700 font-medium">{{ request()->ip() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button for Profile Form -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="flex items-center px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-colors duration-150">
                        <i class="fas fa-save mr-2"></i> {{ lang('buttons.save', [], 'profile') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Customization Tab -->
        <div x-show="activeTab === 'customization'" x-cloak>
            <div class="space-y-6">
                <!-- Banner Customization -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h4 class="text-base font-medium text-gray-900 flex items-center mb-4">
                        <i class="fas fa-image text-blue-500 mr-2"></i>
                        Banner Customization
                    </h4>
                    <div class="w-full h-32 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg overflow-hidden relative">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium opacity-75">Coming Soon</span>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <button type="button" disabled
                            class="px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                            <i class="fas fa-upload mr-2"></i>Upload Banner
                        </button>
                        <button type="button" disabled
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                            <i class="fas fa-trash-alt mr-2"></i>Remove
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">JPG or PNG. Max 5MB. Recommended: 1500x500px</p>
                </div>

                <!-- Theme Settings -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h4 class="text-base font-medium text-gray-900 flex items-center mb-4">
                        <i class="fas fa-palette text-blue-500 mr-2"></i>
                        Theme Preferences
                    </h4>
                    <p class="text-sm text-gray-600 mb-4">Customize your account appearance and theme settings.</p>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Color Scheme</label>
                            <div class="grid grid-cols-3 gap-3">
                                <button type="button" disabled
                                    class="p-4 border-2 border-gray-300 rounded-lg text-center cursor-not-allowed opacity-50">
                                    <i class="fas fa-sun text-2xl text-yellow-500 mb-2"></i>
                                    <div class="text-xs font-medium text-gray-500">Light</div>
                                </button>
                                <button type="button" disabled
                                    class="p-4 border-2 border-gray-300 rounded-lg text-center cursor-not-allowed opacity-50">
                                    <i class="fas fa-moon text-2xl text-blue-500 mb-2"></i>
                                    <div class="text-xs font-medium text-gray-500">Dark</div>
                                </button>
                                <button type="button" disabled
                                    class="p-4 border-2 border-gray-300 rounded-lg text-center cursor-not-allowed opacity-50">
                                    <i class="fas fa-adjust text-2xl text-gray-500 mb-2"></i>
                                    <div class="text-xs font-medium text-gray-500">Auto</div>
                                </button>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-clock mr-1"></i> Coming Soon
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection