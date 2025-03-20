<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Using reCAPTCHA v2 Invisible instead of v3 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-[#f9fafb] min-h-screen flex flex-col">
<div class="flex items-center justify-center flex-grow">
    <!-- Left Login Section -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="mb-8 flex flex-col items-start">
                <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto mb-3">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Log in to your Numblio-account.</h2>
                    <p class="text-sm text-gray-600 mt-2">Log in with your Numblio account to easily access all your Numblio services. One account for everything you need.</p>
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
                        <h3 class="text-sm font-medium text-yellow-800">You are currently logging in to <b>{{ $client_name }}</b>.</h3>
                        <div class="mt-1 text-sm text-yellow-700">
                            Log in to proceed and gain access to {{ $client_name }}. This login is managed via Single Sign-On (SSO), allowing you to quickly and securely log in with your existing account.
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
                                <li>{{ $error }}</li>
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

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input placeholder="john.doe@example.com" type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
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
                    @error('password')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" class="h-4 w-4 text-blue-600" id="remember">
                    <label for="remember" class="ml-2 text-sm text-gray-700">{{ __('Keep me logged in ') }}</label>
                </div>

                <!-- Submit Button with invisible reCAPTCHA -->
                <div>
                    <button
                        type="submit"
                        class="g-recaptcha w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        data-sitekey="{{ config('services.recaptcha.site_key') }}"
                        data-callback="onSubmit"
                        data-action="login"
                        data-size="invisible">
                        Sign In
                    </button>
                </div>

                <!-- Forgot Password and Sign Up Links -->
                <div class="flex justify-between text-sm mt-4">
                    <a href="{{route('password.forgot')}}" class="text-blue-600 hover:text-blue-700">{{ __('Forgot password?') }}</a>
                    <a href="{{ route('register', ['client_id' => request()->query('client_id'), 'redirect_uri' => request()->query('redirect_uri')]) }}" class="text-blue-600 hover:text-blue-700">{{ __('New here? Create an account') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="fixed bottom-0 left-0 p-2 text-xs text-gray-400 max-w-sm">
    <div class="flex flex-col space-y-1">
        <span>&copy; {{ date('Y') }} Numblio. All rights reserved.</span>
        <div class="flex items-center space-x-2">
            <a href="#" class="hover:text-gray-600">Privacy</a>
            <span class="text-gray-500">•</span>
            <a href="#" class="hover:text-gray-600">Terms</a>
        </div>
    </div>
</footer>

<script>
    function onSubmit(token) {
        document.getElementById("login-form").submit();
    }
</script>
</body>
</html>
