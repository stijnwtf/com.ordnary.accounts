<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Numblio SSO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Using reCAPTCHA v2 Invisible -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-[#f9fafb] min-h-screen flex flex-col">
<div class="flex items-center justify-center flex-grow">
    <!-- Left Registration Section -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="mb-8 flex flex-col items-start">
                <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto mb-3">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Create your Numblio-account.</h2>
                    <p class="text-sm text-gray-600 mt-2">Get access to all Numblio services with a single account. It's free and only takes a minute.</p>
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
                        <h3 class="text-sm font-medium text-yellow-800">You are creating an account to use with <b>{{ $client_name }}</b>.</h3>
                        <div class="mt-1 text-sm text-yellow-700">
                            Sign up to create your Numblio account and proceed to {{ $client_name }}. This registration is managed via Single Sign-On (SSO), allowing you to access all Numblio services with one account.
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

                <!-- Name Input -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Name') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input placeholder="John Doe" type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                               class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">
                    </div>
                    @error('name')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input placeholder="john.doe@example.com" type="email" name="email" id="email" value="{{ old('email') }}" required
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
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
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
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm Password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input placeholder="••••••••" type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-700">I agree to the <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a></label>
                    </div>
                </div>

                <!-- Submit Button with invisible reCAPTCHA -->
                <div>
                    <button
                        type="submit"
                        class="g-recaptcha w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        data-sitekey="{{ config('services.recaptcha.site_key') }}"
                        data-callback="onSubmit"
                        data-action="register"
                        data-size="invisible">
                        Create Account
                    </button>
                </div>

                <!-- Log In Link -->
                <div class="text-center text-sm mt-4">
                    <p class="text-gray-600">
                        Already have an account?
                        <a href="{{ route('login', ['client_id' => request()->query('client_id'), 'redirect_uri' => request()->query('redirect_uri')]) }}" class="text-blue-600 hover:text-blue-700 font-medium">Sign in</a>
                    </p>
                </div>
            </form>
            <!-- Footer -->
            <footer class="mt-8 text-xs text-gray-400">
                <div class="flex flex-col space-y-1">
                    <span>&copy; {{ date('Y') }} Numblio. All rights reserved. Numblio and all associated services are protected by copyright and intellectual property laws.</span>
                    <div class="flex items-center space-x-2 mt-1">
                        <a href="https://numblio.com/documents/privacy" class="hover:text-gray-600">Privacy</a>
                        <span class="text-gray-500">•</span>
                        <a href="https://numblio.com/documents/terms" class="hover:text-gray-600">Terms</a>
                        <span class="text-gray-500">•</span>
                        <a href="https://support.numblio.com" class="hover:text-gray-600">Help</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

<script>
    function onSubmit(token) {
        document.getElementById("register-form").submit();
    }
</script>
</body>
</html>
