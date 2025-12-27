<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body class="bg-[#f9fafb] min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow">
        <!-- Left Registration Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="mb-8 flex flex-col items-start">
                    <img src="https://cdn.ordnary.com/site_assets/ordnary-black.png" alt="Ordnary Logo" class="h-8 w-auto mb-3">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Create your Ordnary-account.</h2>
                        <p class="text-sm text-gray-600 mt-2">Get access to all Ordnary services with a single account.
                            It's free and only takes a minute.</p>
                    </div>
                </div>

                <!-- Progress Steps -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center">
                                1
                            </div>
                            <div class="ml-2 text-sm font-medium text-blue-600">Account</div>
                        </div>
                        <div class="flex-1 h-1 mx-4 bg-gray-200">
                            <div id="progress-bar" class="h-1 bg-blue-600" style="width: 0%"></div>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center">
                                2
                            </div>
                            <div class="ml-2 text-sm font-medium text-gray-600">Personal</div>
                        </div>
                    </div>
                </div>

                <!-- Warning Alert -->
                @if(!isset($direct_login) && isset($client_name))
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">You are creating an account to use with
                                    <b>{{ $client_name }}</b>.</h3>
                                <div class="mt-1 text-sm text-yellow-700">
                                    Sign up to create your Ordnary account and proceed to {{ $client_name }}. This
                                    registration is managed via Single Sign-On (SSO), allowing you to access all Ordnary
                                    services with one account.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Error Message -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Something went wrong during registration.</h3>
                                <div class="mt-1 text-sm text-red-700">
                                    <ul class="list-disc pl-4">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" id="register-form" class="space-y-6">
                    @csrf

                    <!-- Add hidden fields for SSO parameters -->
                    @if(session('client_id') && session('redirect_uri'))
                        <input type="hidden" name="client_id" value="{{ session('client_id') }}">
                        <input type="hidden" name="redirect_uri" value="{{ session('redirect_uri') }}">
                    @endif

                    <!-- Step 1: Account Information -->
                    <div id="step1" class="space-y-6">
                        <!-- Email Input -->
                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input placeholder="john.doe@example.com" type="email" name="email" id="email"
                                    value="{{ old('email') }}" required
                                    class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input placeholder="••••••••" type="password" name="password" id="password" required
                                    class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Password must be at least 8 characters and contain letters and numbers.
                            </p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Confirm Password Input -->
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm Password') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input placeholder="••••••••" type="password" name="password_confirmation"
                                    id="password_confirmation" required
                                    class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <button type="button" onclick="nextStep()"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Next Step
                        </button>
                    </div>

                    <!-- Step 2: Personal Information -->
                    <div id="step2" class="space-y-6 hidden">
                        <!-- Username Input -->
                        <div>
                            <label for="username"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Username') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input placeholder="johndoe" type="text" name="username" id="username"
                                    value="{{ old('username') }}" required
                                    class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('username') ? 'border-red-500' : 'border-gray-300' }}">
                            </div>
                            @error('username')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- First Name Input -->
                        <div>
                            <label for="first_name"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('First Name') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input placeholder="John" type="text" name="first_name" id="first_name"
                                    value="{{ old('first_name') }}" required
                                    class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('first_name') ? 'border-red-500' : 'border-gray-300' }}">
                            </div>
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Last Name Input -->
                        <div>
                            <label for="last_name"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Last Name') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input placeholder="Doe" type="text" name="last_name" id="last_name"
                                    value="{{ old('last_name') }}" required
                                    class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('last_name') ? 'border-red-500' : 'border-gray-300' }}">
                            </div>
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Turnstile Widget -->
                        <div id="turnstile-container" class="flex justify-center"></div>

                        <!-- GDPR Consent -->
                        <div class="space-y-3 pt-2 pb-1">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="gdpr_consent" name="gdpr_consent" type="checkbox" required
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="gdpr_consent" class="text-gray-700">
                                        I consent to the processing of my personal data in accordance with the <span
                                            class="font-medium">GDPR</span>. I understand that Ordnary will collect,
                                        store, and process my data as described in the <a
                                            href="https://ordnary.com/documents/privacy"
                                            class="text-blue-600 hover:underline">Privacy Policy</a>, and that I have
                                        the right to access, modify, export, or request deletion of my personal data at
                                        any time.
                                    </label>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 pl-7">
                                Your data will only be used for the purposes clearly stated in our Privacy Policy,
                                including account creation, service provision, security, and communication. You can
                                withdraw your consent at any time through your account settings.
                            </p>
                        </div>

                        <div class="flex space-x-4">
                            <button type="button" onclick="prevStep()"
                                class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Previous
                            </button>
                            <button type="submit" id="submit-button"
                                class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Create Account
                            </button>
                        </div>
                    </div>
                </form>
                <!-- Footer -->
                <footer class="mt-8 text-xs text-gray-400">
                    <div class="flex flex-col space-y-1">
                        <span>&copy; {{ date('Y') }} Ordnary. All rights reserved. Ordnary and all associated services
                            are protected by copyright and intellectual property laws.</span>
                        <div class="flex items-center space-x-2 mt-1">
                            <a href="https://ordnary.com/documents/privacy-policy" class="hover:text-gray-600">Privacy Policy</a>
                            <span class="text-gray-500">•</span>
                            <a href="https://ordnary.com/documents/terms-and-conditions" class="hover:text-gray-600">Terms & Conditions</a>
                            <span class="text-gray-500">•</span>
                            <a href="https://ordnary.com/documents/disclaimer" class="hover:text-gray-600">Disclaimer</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script>
        let turnstileWidgetId = null;

        function nextStep() {
            // Get step 1 inputs
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            
            // Clear any previous error messages
            const errorElements = document.querySelectorAll('#step1 .validation-error');
            errorElements.forEach(el => el.remove());
            
            let isValid = true;
            
            // Validate email
            if (!email.value.trim()) {
                showError(email, 'Email is required.');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError(email, 'Please enter a valid email address.');
                isValid = false;
            }
            
            // Validate password
            if (!password.value) {
                showError(password, 'Password is required.');
                isValid = false;
            } else if (password.value.length < 8) {
                showError(password, 'Password must be at least 8 characters.');
                isValid = false;
            }
            
            // Validate password confirmation
            if (!passwordConfirmation.value) {
                showError(passwordConfirmation, 'Please confirm your password.');
                isValid = false;
            } else if (password.value !== passwordConfirmation.value) {
                showError(passwordConfirmation, 'Passwords do not match.');
                isValid = false;
            }
            
            // Only proceed if all validations pass
            if (!isValid) {
                return;
            }
            
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
            document.getElementById('progress-bar').style.width = '100%';
            document.querySelectorAll('.w-8.h-8')[1].classList.remove('bg-gray-200', 'text-gray-600');
            document.querySelectorAll('.w-8.h-8')[1].classList.add('bg-blue-600', 'text-white');
            document.querySelectorAll('.text-gray-600')[1].classList.remove('text-gray-600');
            document.querySelectorAll('.text-gray-600')[1].classList.add('text-blue-600');
            
            // Render Turnstile widget when moving to step 2
            if (turnstileWidgetId === null && typeof turnstile !== 'undefined') {
                setTimeout(() => {
                    const container = document.getElementById('turnstile-container');
                    if (container) {
                        turnstileWidgetId = turnstile.render('#turnstile-container', {
                            sitekey: '{{ config("services.turnstile.site_key") }}',
                            theme: 'light',
                        });
                    }
                }, 100);
            }
        }
        
        function showError(input, message) {
            const errorDiv = document.createElement('p');
            errorDiv.className = 'mt-1 text-sm text-red-600 flex items-center validation-error';
            errorDiv.textContent = message;
            input.parentElement.parentElement.appendChild(errorDiv);
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-300');
        }
        
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function prevStep() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
            document.getElementById('progress-bar').style.width = '0%';
            document.querySelectorAll('.w-8.h-8')[1].classList.add('bg-gray-200', 'text-gray-600');
            document.querySelectorAll('.w-8.h-8')[1].classList.remove('bg-blue-600', 'text-white');
            document.querySelectorAll('.text-blue-600')[1].classList.remove('text-blue-600');
            document.querySelectorAll('.text-blue-600')[1].classList.add('text-gray-600');
            
            // Reset Turnstile widget
            if (turnstileWidgetId !== null && typeof turnstile !== 'undefined') {
                turnstile.reset(turnstileWidgetId);
            }
        }

        // Handle Enter key press on step 1 inputs
        document.addEventListener('DOMContentLoaded', function() {
            const step1Inputs = document.querySelectorAll('#step1 input');
            
            step1Inputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        nextStep();
                    }
                });
                
                // Clear error styling on input
                input.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-300');
                    const errorElement = this.parentElement.parentElement.querySelector('.validation-error');
                    if (errorElement) {
                        errorElement.remove();
                    }
                });
            });

            // Handle Enter key on step 2 to submit (except last button which is submit)
            const step2Inputs = document.querySelectorAll('#step2 input[type="text"], #step2 input[type="email"]');
            
            step2Inputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.querySelector('#step2 button[type="submit"]').click();
                    }
                });
            });
        });
    </script>
</body>

</html>