<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numblio Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        admin: {
                            primary: '#1e3a8a', // Darker blue for admin
                            secondary: '#172554',
                            accent: '#3b82f6'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col">
<div class="flex items-center justify-center flex-grow">
    <!-- Admin Login Section -->
    <div class="w-full max-w-md p-8 mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header with Admin Badge -->
            <div class="bg-admin-primary p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto invert">
                    <span class="bg-yellow-500 text-admin-primary text-xs font-bold px-2 py-1 rounded-md">ADMIN PORTAL</span>
                </div>
                <h2 class="text-2xl font-bold">Administrator Login</h2>
                <p class="text-sm text-slate-200 mt-1">Secure access to the SSO Client Management dashboard</p>
            </div>

            <div class="p-6">
                <!-- Security Notice -->
                <div class="mb-6 p-3 bg-slate-50 border-l-4 border-admin-primary rounded-r-md">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <i class="fas fa-shield-alt text-admin-primary text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-slate-800">Restricted Area</h3>
                            <p class="text-xs text-slate-600 mt-1">
                                This portal is for authorized administrators only. All login attempts are logged and monitored.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Error Message -->
                @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Authentication failed</h3>
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
                <form method="POST" action="{{ route('adminloginprocess') }}" id="login-form" class="space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Administrator Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-slate-400"></i>
                            </div>
                            <input placeholder="admin@numblio.com" type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-admin-accent {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }}">
                        </div>
                        @error('email')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-slate-400"></i>
                            </div>
                            <input placeholder="••••••••" type="password" name="password" id="password" required
                                class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-admin-accent {{ $errors->has('password') ? 'border-red-500' : 'border-slate-300' }}">
                        </div>
                        @error('password')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" class="h-4 w-4 text-admin-primary border-slate-300 rounded" id="remember">
                        <label for="remember" class="ml-2 text-sm text-slate-700">Keep me logged in</label>
                    </div>

                    <!-- reCAPTCHA Checkbox -->
                    <div class="flex justify-center mt-4">
                        <div class="g-recaptcha" data-sitekey="{{ $recaptcha_site_key }}"></div>
                    </div>
                    @error('g-recaptcha-response')
                    <p class="mt-1 text-sm text-red-600 text-center">
                        {{ $message }}
                    </p>
                    @enderror

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            class="w-full bg-admin-primary text-white py-2 px-4 rounded-md hover:bg-admin-secondary focus:outline-none focus:ring-2 focus:ring-admin-accent focus:ring-offset-2">
                            <i class="fas fa-sign-in-alt mr-2"></i> Secure Login
                        </button>
                    </div>
                </form>

                <!-- Return to Main Site Link -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-admin-accent hover:text-admin-primary">
                        <i class="fas fa-arrow-left mr-1"></i> Return to main login
                    </a>
                </div>
            </div>
        </div>

        <!-- Simple Text Footer -->
        <div class="mt-8 text-center text-xs text-slate-500">
            <p>&copy; 2025 Numblio. All rights reserved.</p>
            <div class="mt-2 space-x-4">
                <a href="#" class="text-slate-500 hover:text-slate-700">Privacy Policy</a>
                <a href="#" class="text-slate-500 hover:text-slate-700">Terms of Service</a>
                <a href="#" class="text-slate-500 hover:text-slate-700">Contact</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
