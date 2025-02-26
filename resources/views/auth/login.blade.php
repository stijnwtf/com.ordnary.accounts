<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen | Numblio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f9fafb] min-h-screen">
<div class="flex items-center justify-center min-h-screen">
    <!-- Left Login Section -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="mb-8">
                <img src="image.png" alt="Numblio Logo" class="h-16 w-auto mx-auto mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Inloggen op je Numblio-account.</h2>
                <p class="text-sm text-gray-600 mt-2">Beheer je hosting en domeinen</p>
            </div>

            <!-- Error Message -->
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">{{ __('Authenticatie mislukt') }}</h3>
                        <div class="mt-1 text-sm text-red-700">{{ session('error') }}</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" id="login" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('E-mailadres') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Wachtwoord') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                               class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" class="h-4 w-4 text-blue-600" id="remember">
                    <label for="remember" class="ml-2 text-sm text-gray-700">{{ __('Onthoud mij') }}</label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Inloggen
                    </button>
                </div>

                <!-- Forgot Password Link -->
                <div class="text-sm text-center mt-4">
                    <a href="#" class="text-blue-600 hover:text-blue-700">{{ __('Wachtwoord vergeten?') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
