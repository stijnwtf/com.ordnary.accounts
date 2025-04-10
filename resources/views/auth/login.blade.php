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
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex flex-col">
<div class="flex items-center justify-center flex-grow p-4">
    <!-- Login Card -->
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 border border-gray-100">
        <div class="mb-8 flex flex-col items-start">
            <img src="/img/dark.png" alt="Numblio Logo" class="h-9 w-auto mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Log in to your Numblio-account.</h2>
                <p class="text-sm text-gray-600 mt-2">One account for everything you need across all Numblio services.</p>
            </div>
        </div>

        <!-- Warning Alert -->
        @if (!isset($direct_login))
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0 pt-0.5">
                    <i class="fas fa-info-circle text-amber-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-800">You are logging in to <b>{{ $client_name }}</b></h3>
                    <div class="mt-1 text-sm text-amber-700">
                        This login is managed via Numblio's Single Sign-On (SSO), allowing you secure access with your existing account.
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Error Message -->
        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0 pt-0.5">
                    <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Something went wrong while signing in</h3>
                    <div class="mt-1 text-sm text-red-700">
                        <ul class="list-disc pl-4 space-y-1">
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
        <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-5">
            @csrf
            
            <!-- Add hidden fields for SSO parameters -->
            @if(session('client_id') && session('redirect_uri'))
            <input type="hidden" name="client_id" value="{{ session('client_id') }}">
            <input type="hidden" name="redirect_uri" value="{{ session('redirect_uri') }}">
            @endif

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input placeholder="john.doe@example.com" type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
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
                           class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}">
                </div>
                @error('password')
                <p class="mt-1 text-sm text-red-600 flex items-center">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Remember Me Checkbox -->
            <div class="flex items-center">
                <input type="checkbox" name="remember" class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" id="remember">
                <label for="remember" class="ml-2 text-sm text-gray-700">{{ __('Keep me logged in') }}</label>
            </div>

            <!-- Submit Button with invisible reCAPTCHA -->
            <div>
                <button
                    type="submit"
                    class="g-recaptcha w-full bg-blue-600 text-white py-2.5 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium"
                    data-sitekey="{{ config('services.recaptcha.site_key') }}"
                    data-callback="onSubmit"
                    data-action="login"
                    data-size="invisible">
                    Sign In
                </button>
            </div>

            <!-- Forgot Password and Sign Up Links -->
            <div class="flex justify-between text-sm mt-6">
                <a href="{{route('password.forgot')}}" class="text-blue-600 hover:text-blue-700 hover:underline transition-colors">{{ __('Forgot password?') }}</a>
                <a href="{{ route('register', ['client_id' => request()->query('client_id'), 'redirect_uri' => request()->query('redirect_uri')]) }}" class="text-blue-600 hover:text-blue-700 hover:underline transition-colors">{{ __('New here? Create an account') }}</a>
            </div>
        </form>
        <!-- Footer -->
        <footer class="mt-8 text-xs text-gray-400">
            <div class="flex flex-col space-y-1">
                <span>&copy; {{ date('Y') }} Numblio. All rights reserved.</span>
                <div class="flex items-center space-x-2 mt-1">
                    <a href="https://numblio.com/documents/privacy" class="hover:text-gray-600 transition-colors">Privacy</a>
                    <span class="text-gray-500">•</span>
                    <a href="https://numblio.com/documents/terms" class="hover:text-gray-600 transition-colors">Terms</a>
                    <span class="text-gray-500">•</span>
                    <a href="https://support.numblio.com" class="hover:text-gray-600 transition-colors">Help</a>
                </div>
            </div>
        </footer>
    </div>
</div>

<script>
    function onSubmit(token) {
        document.getElementById("login-form").submit();
    }
</script>
</body>
</html>
