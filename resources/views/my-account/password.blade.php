@extends('my-account.layout')

@section('title', lang('title', [], 'password'))

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-medium leading-6 text-gray-900">
        {{ lang('header', [], 'password') }}
    </h3>
    <p class="mt-2 text-sm text-gray-600">
        {{ lang('subtitle', [], 'password') }}
    </p>
</div>

<form method="POST" action="{{ route('my-account.password') }}" class="space-y-8">
    @csrf
    
    <!-- Current Password Section -->
    <div class="space-y-4">
        <h4 class="text-sm font-medium text-gray-900">{{ lang('current_password.label', [], 'password') }}</h4>
        
        <div>
            <div class="relative">
                <input placeholder="{{ lang('current_password.placeholder', [], 'password') }}" type="password" name="current_password" id="current_password" required
                       class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300' }}">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            @error('current_password')
            <p class="mt-1.5 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
            <p class="mt-1.5 text-xs text-gray-500">{{ lang('current_password.helper', [], 'password') }}</p>
        </div>
    </div>
    
    <!-- New Password Section -->
    <div class="space-y-4 pt-4 border-t border-gray-200">
        <h4 class="text-sm font-medium text-gray-900">{{ lang('new_password.label', [], 'password') }}</h4>
        
        <div>
            <div class="relative">
                <input placeholder="{{ lang('new_password.placeholder', [], 'password') }}" type="password" name="password" id="password" required
                       class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                       oninput="checkPasswordStrength()">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            @error('password')
            <p class="mt-1.5 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
            
            <!-- Password Strength Meter -->
            <div class="mt-3">
                <div class="flex justify-between mb-1">
                    <span class="text-xs text-gray-500">{{ lang('strength.label', [], 'password') }}</span>
                    <span id="password-strength-text" class="text-xs font-medium">{{ lang('strength.too_weak', [], 'password') }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div id="password-strength-meter" class="bg-red-500 h-1.5 rounded-full" style="width: 10%"></div>
                </div>
            </div>
            
            <p class="mt-1.5 text-xs text-gray-500">{{ lang('new_password.helper', [], 'password') }}</p>
        </div>
        
        <!-- Confirm Password Input -->
        <div>
            <div class="relative">
                <input placeholder="{{ lang('confirm_password.placeholder', [], 'password') }}" type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <p class="mt-1.5 text-xs text-gray-500">{{ lang('confirm_password.helper', [], 'password') }}</p>
        </div>
    </div>
    
    <!-- Password Security Tips -->
    <div class="pt-4 border-t border-gray-200">
        <div class="bg-blue-50 rounded-md p-4">
            <div class="flex">
                <div class="ml-1">
                    <h4 class="text-sm font-medium text-blue-800">{{ lang('security_tips.title', [], 'password') }}</h4>
                    <ul class="mt-2 text-xs text-blue-700 list-disc space-y-1 pl-5">
                        <li>{{ lang('security_tips.tips.0', [], 'password') }}</li>
                        <li>{{ lang('security_tips.tips.1', [], 'password') }}</li>
                        <li>{{ lang('security_tips.tips.2', [], 'password') }}</li>
                        <li>{{ lang('security_tips.tips.3', [], 'password') }}</li>
                        <li>{{ lang('security_tips.tips.4', [], 'password') }}</li>
                        <li>{{ lang('security_tips.tips.5', [], 'password') }}</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="mt-8">
            <button type="submit"
                class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm">
                {{ lang('buttons.update', [], 'password') }}
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
        let text = '{{ lang('strength.too_weak', [], 'password') }}';
        
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
            text = '{{ lang('strength.very_strong', [], 'password') }}';
        } else if (strength >= 60) {
            strengthClass = 'bg-green-400';
            text = '{{ lang('strength.strong', [], 'password') }}';
        } else if (strength >= 40) {
            strengthClass = 'bg-yellow-500';
            text = '{{ lang('strength.medium', [], 'password') }}';
        } else if (strength >= 20) {
            strengthClass = 'bg-orange-500';
            text = '{{ lang('strength.weak', [], 'password') }}';
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
