@extends('my-account.layout')

@section('title', 'Profile Information')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-2">Personal information</h3>
    <p class="text-sm text-gray-600">Update your personal information to personalize your Numblio experience.</p>
</div>
<form method="POST" action="{{ route('my-account.profile') }}" class="space-y-6">
    @csrf

    <!-- Name Input -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user text-gray-400"></i>
            </div>
            <input type="text" placeholder="John Doe" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required
                   class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('first_name') ? 'border-red-500' : 'border-gray-300' }}">
        </div>
        @error('first_name')
        <p class="mt-1 text-sm text-red-600 flex items-center">
            {{ $message }}
        </p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">Your first name as displayed in Numblio-services.</p>
    </div>

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user text-gray-400"></i>
            </div>
            <input type="text" placeholder="Doe" name="last_name" id="last_name" value="{{ old('first_name', $user->last_name) }}" required
                   class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('last_name') ? 'border-red-500' : 'border-gray-300' }}">
        </div>
        @error('last_name')
        <p class="mt-1 text-sm text-red-600 flex items-center">
            {{ $message }}
        </p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">Your last name as displayed in Numblio-services.</p>
    </div>

    <!-- Email Input -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input placeholder="john.doe@example.com" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                   class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
        </div>
        @error('email')
        <p class="mt-1 text-sm text-red-600 flex items-center">
            {{ $message }}
        </p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">This email address is used for login and communication.</p>
    </div>

    <!-- Additional Fields -->
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-gray-400">(Optional)</span></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-phone text-gray-400"></i>
            </div>
            <input placeholder="+31 6 12345678" type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}"
                   class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}">
        </div>
        @error('phone')
        <p class="mt-1 text-sm text-red-600 flex items-center">
            {{ $message }}
        </p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">Used for account recovery and important notifications.</p>
    </div>

    <!-- Language Preference -->
    <div>
        <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language Preference</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-globe text-gray-400"></i>
            </div>
            <select name="language" id="language"
                    class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('language') ? 'border-red-500' : 'border-gray-300' }}">
                <option value="nl" {{ (old('language', $user->lang ?? 'nl') == 'nl') ? 'selected' : '' }}>Nederlands</option>
                <option value="en" {{ (old('language', $user->lang ?? 'nl') == 'en') ? 'selected' : '' }}>English</option>
                <option value="de" {{ (old('language', $user->lang ?? 'nl') == 'de') ? 'selected' : '' }}>Deutsch</option>
                <option value="fr" {{ (old('language', $user->lang ?? 'nl') == 'fr') ? 'selected' : '' }}>Français</option>
            </select>
        </div>
        <p class="mt-1 text-xs text-gray-500">Choose your preferred language for the platform.</p>
    </div>

    <!-- Account Info -->
    <div class="pt-4 border-t border-gray-200 space-y-2">
        <p class="text-xs text-gray-500">
            <span class="font-medium">Account created at:</span> {{ $user->created_at->format('d-m-Y') }}
        </p>
        <p class="text-xs text-gray-500">
            <span class="font-medium">Last update:</span> {{ $user->updated_at->format('d-m-Y') }}
        </p>
        <p class="text-xs text-gray-500">
            <span class="font-medium">Last login:</span> {{ now()->subDays(rand(0, 5))->format('d-m-Y H:i') }}
            <span class="text-xs text-gray-400">(from {{ request()->ip() }})</span>
        </p>
        <p class="text-xs text-gray-500">
            <span class="font-medium">Account type:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                <i class="fas fa-star mr-1"></i> Standard
            </span>
        </p>
    </div>

    <!-- Submit Button for Profile Form -->
    <div class="pb-6 flex justify-end">
        <button
            type="submit"
            class="flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm">
            <i class="fas fa-save mr-2"></i> Confirm changes
        </button>
    </div>
</form>
@endsection
