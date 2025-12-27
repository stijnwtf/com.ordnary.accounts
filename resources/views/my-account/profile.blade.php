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
                <label for="first_name"
                    class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.first_name', [], 'profile') }}</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" placeholder="John" name="first_name" id="first_name"
                        value="{{ old('first_name', $user->first_name) }}" required
                        class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('first_name') ? 'border-red-500' : 'border-gray-300' }}">
                </div>
                @error('first_name')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">{{ lang('fields.first_name_helper', [], 'profile') }}</p>
            </div>

            <!-- Last Name Input -->
            <div>
                <label for="last_name"
                    class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.last_name', [], 'profile') }}</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" placeholder="Doe" name="last_name" id="last_name"
                        value="{{ old('last_name', $user->last_name) }}" required
                        class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('last_name') ? 'border-red-500' : 'border-gray-300' }}">
                </div>
                @error('last_name')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">{{ lang('fields.last_name_helper', [], 'profile') }}</p>
            </div>
        </div>

        <!-- Email Input -->
        <div>
            <label for="email"
                class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.email', [], 'profile') }}</label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input placeholder="john.doe@example.com" type="email" name="email" id="email"
                    value="{{ old('email', $user->email) }}" required
                    class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                </p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">{{ lang('fields.email_helper', [], 'profile') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Phone Number Input -->
            <div>
                <label for="phone"
                    class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.phone', [], 'profile') }} <span
                        class="text-gray-400">{{ lang('fields.phone_optional', [], 'profile') }}</span></label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                    <input placeholder="+31 6 12345678" type="tel" name="phone" id="phone"
                        value="{{ old('phone', $user->phone ?? '') }}"
                        class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}">
                </div>
                @error('phone')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">{{ lang('fields.phone_helper', [], 'profile') }}</p>
            </div>

            <!-- Language Preference -->
            <div>
                <label for="language"
                    class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.language', [], 'profile') }}</label>
                <div class="relative rounded-md shadow-sm">
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
@endsection