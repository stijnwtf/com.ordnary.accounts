@extends('my-account.layout')

@section('title', 'Profile Information')

@section('content')
<div class="mb-8">
    <h3 class="text-xl font-semibold text-gray-900 mb-2">Personal Information</h3>
    <p class="text-sm text-gray-600">Update your personal details and account preferences.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row items-center gap-6">
            <div class="relative">
                <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md" 
                     src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=400&d=mp&r=pg"
                     alt="{{ $user->first_name }} {{ $user->last_name }}">
                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                    <a href="https://gravatar.com" target="_blank" class="inline-flex items-center px-3 py-1 bg-white text-xs font-medium text-gray-700 rounded-full shadow-sm border border-gray-200 hover:bg-gray-50">
                        <i class="fas fa-camera mr-1 text-blue-500"></i> Change
                    </a>
                </div>
            </div>
            <div class="text-center sm:text-left">
                <h4 class="text-lg font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h4>
                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                <div class="mt-2 flex flex-wrap justify-center sm:justify-start gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Verified
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <i class="fas fa-star mr-1"></i> Standard
                    </span>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('my-account.profile') }}" class="p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- First Name Input -->
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" placeholder="John" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required
                          class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('first_name') ? 'border-red-500' : 'border-gray-300' }}">
                </div>
                @error('first_name')
                <p class="mt-1 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Your first name as displayed in Numblio services.</p>
            </div>

            <!-- Last Name Input -->
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" placeholder="Doe" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required
                          class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('last_name') ? 'border-red-500' : 'border-gray-300' }}">
                </div>
                @error('last_name')
                <p class="mt-1 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Your last name as displayed in Numblio services.</p>
            </div>
        </div>

        <!-- Email Input -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input placeholder="john.doe@example.com" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                      class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
            </div>
            @error('email')
            <p class="mt-1 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
            </p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">This email address is used for login, communication, and Gravatar.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Phone Number Input -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-gray-400">(Optional)</span></label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                    <input placeholder="+31 6 12345678" type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}"
                          class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}">
                </div>
                @error('phone')
                <p class="mt-1 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Used for account recovery and important notifications.</p>
            </div>

            <!-- Language Preference -->
            <div>
                <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language Preference</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-globe text-gray-400"></i>
                    </div>
                    <select name="language" id="language"
                           class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none {{ $errors->has('language') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="nl" {{ (old('language', $user->lang ?? 'nl') == 'nl') ? 'selected' : '' }}>Nederlands</option>
                        <option value="en" {{ (old('language', $user->lang ?? 'nl') == 'en') ? 'selected' : '' }}>English</option>
                        <option value="de" {{ (old('language', $user->lang ?? 'nl') == 'de') ? 'selected' : '' }}>Deutsch</option>
                        <option value="fr" {{ (old('language', $user->lang ?? 'nl') == 'fr') ? 'selected' : '' }}>Français</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Choose your preferred language for the platform.</p>
            </div>
        </div>

        <!-- Account Info -->
        <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-100">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Account Information</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="flex items-center text-xs">
                    <i class="fas fa-calendar-alt text-gray-400 mr-2 w-5 text-center"></i>
                    <span class="text-gray-500">Created:</span>
                    <span class="ml-1 text-gray-700 font-medium">{{ $user->created_at->format('d-m-Y') }}</span>
                </div>
                <div class="flex items-center text-xs">
                    <i class="fas fa-history text-gray-400 mr-2 w-5 text-center"></i>
                    <span class="text-gray-500">Last update:</span>
                    <span class="ml-1 text-gray-700 font-medium">{{ $user->updated_at->format('d-m-Y') }}</span>
                </div>
                <div class="flex items-center text-xs">
                    <i class="fas fa-sign-in-alt text-gray-400 mr-2 w-5 text-center"></i>
                    <span class="text-gray-500">Last login:</span>
                    <span class="ml-1 text-gray-700 font-medium">{{ now()->subDays(rand(0, 5))->format('d-m-Y H:i') }}</span>
                </div>
                <div class="flex items-center text-xs">
                    <i class="fas fa-map-marker-alt text-gray-400 mr-2 w-5 text-center"></i>
                    <span class="text-gray-500">IP address:</span>
                    <span class="ml-1 text-gray-700 font-medium">{{ request()->ip() }}</span>
                </div>
            </div>
        </div>

        <!-- Submit Button for Profile Form -->
        <div class="pt-4 border-t border-gray-200 flex justify-end">
            <button
                type="submit"
                class="flex items-center px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-colors duration-150">
                <i class="fas fa-save mr-2"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
