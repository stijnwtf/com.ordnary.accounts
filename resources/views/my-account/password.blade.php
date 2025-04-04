@extends('my-account.layout')

@section('title', 'Change Password')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
        Password Settings
    </h3>
    <p class="mt-2 text-sm text-gray-600">
        Protect your account with a strong, unique password that you don't use elsewhere.
    </p>
</div>

<form method="POST" action="{{ route('my-account.password') }}" class="space-y-6">
    @csrf

    <!-- Current Password Section -->
    <div class="pb-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-medium text-gray-900">Current Password</h4>
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input placeholder="Enter your current password" type="password" name="current_password" id="current_password" required
                   class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300' }}">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        @error('current_password')
        <p class="mt-1.5 text-sm text-red-600 flex items-center">
            <i class="fas fa-exclamation-circle mr-1.5"></i>
            {{ $message }}
        </p>
        @enderror
        <p class="mt-1.5 text-xs text-gray-500">We need your current password to verify your identity.</p>
    </div>

    <!-- New Password Section -->
    <div class="py-3 border-b border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-medium text-gray-900">New Password</h4>
        </div>

        <div class="space-y-4">
            <!-- New Password Input -->
            <div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock-open text-gray-400"></i>
                    </div>
                    <input placeholder="Create a new password" type="password" name="password" id="password" required
                           class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                           oninput="checkPasswordStrength()">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                @error('password')
                <p class="mt-1.5 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1.5"></i>
                    {{ $message }}
                </p>
                @enderror

                <!-- Password Strength Meter -->
                <div class="mt-2">
                    <div class="flex justify-between mb-1">
                        <span class="text-xs text-gray-500">Password strength:</span>
                        <span id="password-strength-text" class="text-xs font-medium">Too weak</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div id="password-strength-meter" class="bg-red-500 h-1.5 rounded-full" style="width: 10%"></div>
                    </div>
                </div>

                <p class="mt-1.5 text-xs text-gray-500">Use at least 8 characters with a mix of letters, numbers and symbols.</p>
            </div>

            <!-- Confirm Password Input -->
            <div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-shield-alt text-gray-400"></i>
                    </div>
                    <input placeholder="Confirm your new password" type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <p class="mt-1.5 text-xs text-gray-500">Enter the same password again to confirm.</p>
            </div>
        </div>
    </div>

    <!-- Password Security Tips -->
    <div class="pt-6">
        <div class="bg-blue-50 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-blue-800">Password security tips</h4>
                    <ul class="mt-2 text-xs text-blue-700 list-disc space-y-1 pl-5">
                        <li>Use at least 8 characters</li>
                        <li>Combine uppercase and lowercase letters</li>
                        <li>Include numbers and special characters</li>
                        <li>Avoid personal information (name, birthday)</li>
                        <li>Don't reuse passwords from other sites</li>
                        <li>Consider using a password manager</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end">
            <button type="submit"
                class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-check mr-2"></i> Update Password
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    function checkPasswordStrength() {
        const password = document.getElementById('password').value;
        const meter = document.getElementById('password-strength-meter');
        const strengthText = document.getElementById('password-strength-text');

        // Default - too weak
        let strength = 0;
        let strengthClass = 'bg-red-500';
        let text = 'Too weak';

        // Check password strength
        if (password.length >= 8) {
            strength += 20;

            // Check for mixed case
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
                strength += 20;
            }

            // Check for numbers
            if (password.match(/\d/)) {
                strength += 20;
            }

            // Check for special characters
            if (password.match(/[^a-zA-Z0-9]/)) {
                strength += 20;
            }

            // Check password length
            if (password.length >= 12) {
                strength += 20;
            }
        }

        // Update the strength text and color
        if (strength >= 80) {
            strengthClass = 'bg-green-500';
            text = 'Very strong';
        } else if (strength >= 60) {
            strengthClass = 'bg-green-400';
            text = 'Strong';
        } else if (strength >= 40) {
            strengthClass = 'bg-yellow-500';
            text = 'Medium';
        } else if (strength >= 20) {
            strengthClass = 'bg-orange-500';
            text = 'Weak';
        }

        // Update UI
        meter.className = `h-1.5 rounded-full ${strengthClass}`;
        meter.style.width = `${strength}%`;
        strengthText.textContent = text;

        // Update text color based on strength
        if (strength >= 60) {
            strengthText.className = 'text-xs font-medium text-green-600';
        } else if (strength >= 40) {
            strengthText.className = 'text-xs font-medium text-yellow-600';
        } else if (strength >= 20) {
            strengthText.className = 'text-xs font-medium text-orange-600';
        } else {
            strengthText.className = 'text-xs font-medium text-red-600';
        }
    }

    // Initialize password strength meter on page load
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        if (password) {
            password.addEventListener('input', checkPasswordStrength);
        }
    });
</script>
@endsection
