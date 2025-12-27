@extends('my-account.layout')

@section('title', lang('profile.title', [], 'profile'))

@section('content')
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ lang('personal_info', [], 'profile') }}</h3>
        <p class="text-sm text-gray-600">{{ lang('subtitle', [], 'profile') }}</p>
    </div>

    <!-- Profile Picture & Banner Section -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <h4 class="text-lg font-medium text-gray-900 mb-4">Profile Picture & Banner</h4>
        
        <div class="space-y-6">
            <!-- Profile Picture + Full Name Row -->
            <div class="flex flex-col md:flex-row md:items-start gap-6">
                <!-- Left: Profile Picture -->
                <div class="flex-shrink-0">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Profile Picture</label>
                    <div class="relative">
                        <img class="h-32 w-32 rounded-full object-cover border-2 border-gray-200"
                            src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=200&d=mp&r=pg"
                            alt="{{ $user->first_name }} {{ $user->last_name }}">
                        <div class="absolute bottom-1 right-1 h-6 w-6 bg-green-400 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="mt-3 space-y-2">
                        <button type="button" 
                            class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-upload mr-2"></i>Upload
                        </button>
                        <button type="button" 
                            class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                            <i class="fas fa-trash-alt mr-2"></i>Remove
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">JPG, PNG or GIF. Max 2MB.</p>
                </div>

                <!-- Right: Full Name -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Full Name</label>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <p class="text-2xl font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                    </div>
                    <p class="text-xs text-gray-500">Your display name as shown across the platform.</p>
                </div>
            </div>

            <!-- Banner Customization -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Banner Customization</label>
                <div class="relative">
                    <div class="w-full h-32 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg overflow-hidden">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium opacity-50">Coming Soon</span>
                        </div>
                    </div>
                    <button type="button" disabled
                        class="mt-3 px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                        <i class="fas fa-upload mr-2"></i>Upload Banner (Coming Soon)
                    </button>
                    <p class="mt-2 text-xs text-gray-500">JPG or PNG. Max 5MB. Recommended: 1500x500px</p>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('my-account.profile') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name Input -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ lang('fields.first_name', [], 'profile') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" placeholder="John" name="first_name" id="first_name"
                                value="{{ old('first_name', $user->first_name) }}" required
                                class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('first_name') ? 'border-red-500' : 'border-gray-300' }}">
                        </div>
                        @error('first_name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1.5"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Last Name Input -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ lang('fields.last_name', [], 'profile') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" placeholder="Doe" name="last_name" id="last_name"
                                value="{{ old('last_name', $user->last_name) }}" required
                                class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('last_name') ? 'border-red-500' : 'border-gray-300' }}">
                        </div>
                        @error('last_name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1.5"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ lang('fields.email', [], 'profile') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 text-sm"></i>
                        </div>
                        <input placeholder="john.doe@example.com" type="email" name="email" id="email"
                            value="{{ old('email', $user->email) }}" required
                            class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1.5"></i> {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">{{ lang('fields.email_helper', [], 'profile') }}</p>
                </div>
            </div>
        </div>

        <!-- Contact & Preferences Card -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cog text-green-600 text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Contact & Preferences</h4>
                    <p class="text-sm text-gray-600">Additional contact info and language settings</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Phone Number Input -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ lang('fields.phone', [], 'profile') }} 
                        <span class="text-gray-400 font-normal">{{ lang('fields.phone_optional', [], 'profile') }}</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400 text-sm"></i>
                        </div>
                        <input placeholder="+31 6 12345678" type="tel" name="phone" id="phone"
                            value="{{ old('phone', $user->phone ?? '') }}"
                            class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}">
                    </div>
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1.5"></i> {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">{{ lang('fields.phone_helper', [], 'profile') }}</p>
                </div>

                <!-- Language Preference -->
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ lang('fields.language', [], 'profile') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-globe text-gray-400 text-sm"></i>
                        </div>
                        <select name="language" id="language" onchange="this.form.submit()"
                            class="w-full px-3 py-2.5 pl-10 pr-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition-colors {{ $errors->has('language') ? 'border-red-500' : 'border-gray-300' }}">
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
                            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">{{ lang('fields.language_helper', [], 'profile') }}</p>
                </div>
            </div>
        </div>

        <!-- Account Information Card -->
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl shadow-sm p-6">
            <div class="flex items-center mb-5">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-info-circle text-gray-600 text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-base font-semibold text-gray-900">{{ lang('account_info', [], 'profile') }}</h4>
                    <p class="text-sm text-gray-600">Read-only account statistics</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-500">{{ lang('account_details.created', [], 'profile') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('d-m-Y') }}</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-history text-green-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-500">{{ lang('account_details.last_update', [], 'profile') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->updated_at->format('d-m-Y') }}</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sign-in-alt text-purple-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-500">{{ lang('account_details.last_login', [], 'profile') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ now()->subDays(rand(0, 5))->format('d-m-Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-orange-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-500">{{ lang('account_details.ip_address', [], 'profile') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ request()->ip() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-between pt-4">
            <p class="text-sm text-gray-500">
                <i class="fas fa-shield-alt mr-1.5 text-blue-500"></i>
                Your data is secure and encrypted
            </p>
            <button type="submit"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md hover:shadow-lg transition-all duration-150">
                <i class="fas fa-save mr-2"></i> {{ lang('buttons.save', [], 'profile') }}
            </button>
        </div>
    </form>
@endsection