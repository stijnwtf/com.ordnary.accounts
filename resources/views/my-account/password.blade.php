@extends('my-account.layout')

@section('title', 'Change Password')

@section('header', 'Change Password')
@section('subheader', 'Make sure you have a strong password that you don\'t use on other websites.')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-2">Change your password</h3>
    <p class="text-sm text-gray-600">Make sure you have a strong password that you don't use on other websites.</p>
</div>

<!-- Password Strength Meter -->
<div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-100">
    <div class="flex items-center">
        <div class="flex-shrink-0 mr-3">
            <i class="fas fa-shield-alt text-yellow-500 text-xl"></i>
        </div>
        <div>
            <h4 class="text-sm font-medium text-yellow-800">Password security check</h4>
            <p class="text-xs text-yellow-700 mt-1">Your password was last changed {{ now()->subMonths(rand(2, 6))->format('d-m-Y') }}</p>
            <div class="mt-2">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 70%"></div>
                </div>
                <p class="text-xs text-yellow-700 mt-1">Your password strength is <span class="font-medium">good</span>.</p>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('my-account.password') }}" class="space-y-6">
    @csrf

    <!-- Current Password Input -->
    <div>
        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input type="password" name="current_password" id="current_password" required
                   class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300' }}">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        @error('current_password')
        <p class="mt-1 text-sm text-red-600 flex items-center">
            {{ $message }}
        </p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">Enter your current password for verification.</p>
    </div>

    <!-- New Password Input -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input type="password" name="password" id="password" required
                   class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        @error('password')
        <p class="mt-1 text-sm text-red-600 flex items-center">
            {{ $message }}
        </p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">Use at least 8 characters with letters, numbers and symbols.</p>
    </div>

    <!-- Confirm Password Input -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                   class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <p class="mt-1 text-xs text-gray-500">Enter your new password again to confirm.</p>
    </div>

    <!-- Password Security Tips -->
    <div class="p-4 bg-blue-50 rounded-lg mt-4">
        <h4 class="text-sm font-medium text-blue-800 mb-2">Tips for a strong password:</h4>
        <ul class="text-xs text-blue-700 list-disc pl-4 space-y-1">
            <li>Use at least 8 characters</li>
            <li>Combine upper and lower case letters</li>
            <li>Include numbers and symbols (e.g. !@#$%)</li>
            <li>Avoid easily guessable information such as your name or date of birth</li>
            <li>Use a unique password for each website</li>
        </ul>
    </div>

    <!-- Submit Button for Password Form -->
    <div class="pb-6 flex justify-end">
        <button
            type="submit"
            class="flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm">
            <i class="fas fa-key mr-2"></i> Confirm changes
        </button>
    </div>
</form>
@endsection 