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

                        <!-- Turnstile Widget (hidden but functional) -->
                        <div id="turnstile-container" class="hidden"></div>

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
                            <button type="button" onclick="showLoginOptions()" class="text-[#0866FF] hover:underline font-medium">
                                {{ __('Sign in using another way') }}
                            </button>
                        </div>
                    </div>

                    <!-- Step 2.5: Login Method Selection -->
                    <div id="step2-options" class="space-y-4 hidden">
                        <div class="text-sm text-gray-600 mb-4">
                            Choose how you want to sign in:
                        </div>

                        <!-- Password Option -->
                        <button type="button" onclick="selectPasswordLogin()" 
                            class="w-full flex items-center justify-between p-4 border border-gray-300 rounded-lg hover:border-[#0866FF] hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-lock text-gray-600"></i>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900">Password</div>
                                    <div class="text-sm text-gray-500">Sign in with your password</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <!-- Email Code Option -->
                        <button type="button" onclick="selectCodeLogin()" 
                            class="w-full flex items-center justify-between p-4 border border-gray-300 rounded-lg hover:border-[#0866FF] hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope text-gray-600"></i>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-900">Email code</div>
                                    <div class="text-sm text-gray-500">Get a code sent to your email</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <!-- Back Button -->
                        <div class="text-center mt-4">
                            <button type="button" onclick="backFromOptions()" class="text-sm text-gray-600 hover:text-gray-900">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Email Code Input -->
                    <div id="step3" class="space-y-6 hidden">
                        <div>
                            <label for="email_code"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Enter code') }}</label>
                            <div class="relative">
                                <input placeholder="Enter 6-digit code" type="text" name="email_code" id="email_code" maxlength="6"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:border-transparent text-center text-2xl tracking-widest font-mono">
                            </div>
                            <p class="mt-2 text-xs text-gray-500 text-center">
                                We sent a code to <span id="code-email" class="font-medium"></span>. Check your inbox and spam folder.
                            </p>
                        </div>

                        <!-- Resend Code Link -->
                        <div class="text-center">
                            <button type="button" onclick="resendCode()" class="text-sm text-[#0866FF] hover:underline font-medium">
                                {{ __('Didn\'t receive a code? Send again') }}
                            </button>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="button" onclick="verifyCode()"
                                class="w-full bg-[#0866FF] text-white py-3 px-4 rounded-full hover:bg-[#0952CC] focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:ring-offset-2 transition-colors duration-200 font-medium">
                                Continue
                            </button>
                        </div>

                        <!-- Back to Password Login Link -->
                        <div class="text-center">
                            <button type="button" onclick="switchToPasswordLogin()" class="text-sm text-[#0866FF] hover:underline font-medium">
                                {{ __('Sign in with password instead') }}
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
        let userEmail = '';

        function nextStep() {
            const email = document.getElementById('email').value;
            
            // Simple email validation
            if (!email || !email.includes('@')) {
                alert('Please enter a valid email address');
                return;
            }

            userEmail = email;

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
            // Show step 1, hide step 2, step 2-options and step 3
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step2-options').classList.add('hidden');
            document.getElementById('step3').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
            document.getElementById('signup-link').classList.remove('hidden');
            
            // Update title and subtitle
            document.getElementById('page-title').textContent = "What's your email address?";
            document.getElementById('page-subtitle').textContent = 'Enter your email address to log in to a Ordnary account or create a new one.';
            
            // Focus on email field
            document.getElementById('email').focus();
            
            currentStep = 1;
        }

        function showLoginOptions() {
            // Hide step 2, show options
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step2-options').classList.remove('hidden');
            
            // Update title
            document.getElementById('page-title').textContent = 'Choose how to sign in';
            const subtitle = document.getElementById('page-subtitle');
            subtitle.innerHTML = 'Select a sign-in method for <button type="button" onclick="prevStep()" class="text-[#0866FF] hover:underline font-medium">' + userEmail + '</button>';
        }

        function backFromOptions() {
            // Show step 2, hide options
            document.getElementById('step2-options').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
            
            // Update title and subtitle
            document.getElementById('page-title').textContent = 'Enter your password';
            const subtitle = document.getElementById('page-subtitle');
            subtitle.innerHTML = 'Enter the password for <button type="button" onclick="prevStep()" class="text-[#0866FF] hover:underline font-medium">' + userEmail + '</button> to continue.';
            
            // Focus on password field
            document.getElementById('password').focus();
        }

        function selectPasswordLogin() {
            // Just go back to password screen
            backFromOptions();
        }

        function selectCodeLogin() {
            // Send code to email
            sendLoginCode(userEmail);
            
            // Hide options, show step 3
            document.getElementById('step2-options').classList.add('hidden');
            document.getElementById('step3').classList.remove('hidden');
            
            // Update title and subtitle
            document.getElementById('page-title').textContent = 'Check your email';
            const subtitle = document.getElementById('page-subtitle');
            subtitle.innerHTML = 'We sent a login code to <button type="button" onclick="prevStep()" class="text-[#0866FF] hover:underline font-medium">' + userEmail + '</button>';
            
            // Update the code email display
            document.getElementById('code-email').textContent = userEmail;
            
            // Focus on code field
            document.getElementById('email_code').focus();
            
            currentStep = 3;
        }

        function switchToCodeLogin() {
            // This function is kept for backward compatibility but now calls selectCodeLogin
            selectCodeLogin();
        }

        function switchToPasswordLogin() {
            // Hide step 3, show step 2
            document.getElementById('step3').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
            
            // Update title and subtitle
            document.getElementById('page-title').textContent = 'Enter your password';
            const subtitle = document.getElementById('page-subtitle');
            subtitle.innerHTML = 'Enter the password for <button type="button" onclick="prevStep()" class="text-[#0866FF] hover:underline font-medium">' + userEmail + '</button> to continue.';
            
            // Focus on password field
            document.getElementById('password').focus();
            
            currentStep = 2;
        }

        function sendLoginCode(email) {
            fetch('{{ route("login.send-code") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Code sent successfully');
                } else {
                    alert('Failed to send code. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

        function resendCode() {
            sendLoginCode(userEmail);
            alert('A new code has been sent to your email.');
        }

        function verifyCode() {
            const code = document.getElementById('email_code').value;
            
            if (!code || code.length !== 6) {
                alert('Please enter a valid 6-digit code');
                return;
            }

            fetch('{{ route("login.verify-code") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ 
                    email: userEmail,
                    code: code
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to the appropriate page
                    window.location.href = data.redirect_url;
                } else {
                    alert('Invalid code. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
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

            // Handle Enter key press on code input
            const codeInput = document.getElementById('email_code');
            codeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    verifyCode();
                }
            });

            // Auto-format code input (only numbers)
            codeInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</body>

</html>