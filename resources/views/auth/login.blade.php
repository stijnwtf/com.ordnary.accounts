<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body class="bg-white min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow">
        <!-- Left Login Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Logo and Title -->
                <div class="mb-8 flex flex-col items-start">
                    <img src="https://cdn.ordnary.com/site_assets/ordnary-black.png" alt="Ordnary Logo" class="h-8 w-auto mb-3">
                    <div>
                        <h2 id="page-title" class="text-2xl font-bold text-gray-900">What's your email address?</h2>
                        <p id="page-subtitle" class="text-sm text-gray-600 mt-2">Enter your email address to log in to a Ordnary account or create a new one.</p>
                    </div>
                </div>

                <!-- Warning Alert -->
                @if (!isset($direct_login))
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">You are currently signing in to
                                    <b>{{ $client_name }}</b>.</h3>
                                <div class="mt-1 text-sm text-yellow-700">
                                    Sign in to proceed and gain access to {{ $client_name }}. This sign-in is managed via
                                    Single Sign-On (SSO), allowing you to quickly and securely sign in with your existing
                                    account.
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
                                <h3 class="text-sm font-medium text-red-800">Something went wrong while signing in.</h3>
                                <div class="mt-1 text-sm text-red-700">
                                    <ul class="list-disc pl-4">
                                        @foreach ($errors->all() as $error)
                                            <li>{!! $error !!}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-6">
                    @csrf

                    <!-- Add hidden fields for SSO parameters -->
                    @if(session('client_id') && session('redirect_uri'))
                        <input type="hidden" name="client_id" value="{{ session('client_id') }}">
                        <input type="hidden" name="redirect_uri" value="{{ session('redirect_uri') }}">
                    @endif

                    <!-- Step 1: Email Input -->
                    <div id="step1" class="space-y-6">
                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                            <div class="relative">
                                <input placeholder="Email" type="email" name="email" id="email"
                                    value="{{ old('email', session('login_email')) }}" required autofocus
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:border-transparent {{ $errors->has('email') ? 'border-red-500' : '' }}">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Next Button -->
                        <div>
                            <button type="button" onclick="nextStep()"
                                class="w-full bg-[#0866FF] text-white py-3 px-4 rounded-full hover:bg-[#0952CC] focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:ring-offset-2 transition-colors duration-200 font-medium">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Password Input -->
                    <div id="step2" class="space-y-6 hidden">
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
                            <div class="relative">
                                <input placeholder="Password" type="password" name="password" id="password"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:border-transparent {{ $errors->has('password') ? 'border-red-500' : '' }}">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" class="h-4 w-4 text-[#0866FF] rounded focus:ring-[#0866FF]" id="remember">
                            <label for="remember" class="ml-2 text-sm text-gray-700">{{ __('Keep me logged in') }}</label>
                        </div>

                        <!-- Turnstile Widget -->
                        <div id="turnstile-container" hidden class="flex justify-center"></div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="w-full bg-[#0866FF] text-white py-3 px-4 rounded-full hover:bg-[#0952CC] focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:ring-offset-2 transition-colors duration-200 font-medium">
                                Sign In
                            </button>
                        </div>

                        <!-- Forgot Password and Alternative Login Links -->
                        <div class="flex justify-between items-center text-sm">
                            <a href="{{route('password.forgot')}}"
                                class="text-[#0866FF] hover:underline font-medium">{{ __('Forgot password?') }}</a>
                            <button type="button" class="text-[#0866FF] hover:underline font-medium">
                                {{ __('Sign in using another way') }}
                            </button>
                        </div>
                    </div>

                </form>
                
                <!-- Sign Up Link - Only show on step 1 -->
                <div id="signup-link" class="text-center text-sm mt-6">
                    <a href="{{ route('register', ['client_id' => request()->query('client_id'), 'redirect_uri' => request()->query('redirect_uri')]) }}"
                        class="text-[#0866FF] hover:underline font-medium">{{ __('New here? Create an account') }}</a>
                </div>
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
        let currentStep = 1;

        function nextStep() {
            const email = document.getElementById('email').value;
            
            // Simple email validation
            if (!email || !email.includes('@')) {
                alert('Please enter a valid email address');
                return;
            }

            // Hide step 1, show step 2
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
            document.getElementById('signup-link').classList.add('hidden');
            
            // Update title and subtitle
            document.getElementById('page-title').textContent = 'Enter your password';
            const subtitle = document.getElementById('page-subtitle');
            subtitle.innerHTML = 'Enter the password for <button type="button" onclick="prevStep()" class="text-[#0866FF] hover:underline font-medium">' + email + '</button> to continue.';
            
            // Focus on password field
            document.getElementById('password').focus();
            
            // Render Turnstile widget when moving to step 2
            if (typeof turnstile !== 'undefined') {
                setTimeout(() => {
                    turnstile.render('#turnstile-container', {
                        sitekey: '{{ config("services.turnstile.site_key") }}',
                        theme: 'light',
                    });
                }, 100);
            }
            
            currentStep = 2;
        }

        function prevStep() {
            // Show step 1, hide step 2
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
            document.getElementById('signup-link').classList.remove('hidden');
            
            // Update title and subtitle
            document.getElementById('page-title').textContent = "What's your email address?";
            document.getElementById('page-subtitle').textContent = 'Enter your email address to log in to a Ordnary account or create a new one.';
            
            // Focus on email field
            document.getElementById('email').focus();
            
            currentStep = 1;
        }

        // Handle Enter key press on email input
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    nextStep();
                }
            });
        });
    </script>
</body>

</html>