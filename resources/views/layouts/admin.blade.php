<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Numblio Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        numblio: {
                            blue: '#2563eb',
                            lightblue: '#3b82f6',
                            gray: '#f9fafb',
                            dark: '#111827'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans">
<!-- Navigation -->
<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto">
                <div class="ml-3 py-1 pl-3 border-l border-gray-200">
                    <span class="text-lg font-semibold text-gray-900">com.numblio.sso</span>
                </div>
            </div>
            <div class="flex items-center">
                <div class="hidden md:flex space-x-6 mr-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-numblio-blue flex items-center text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-numblio-blue flex items-center text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-users mr-2"></i>Clients
                    </a>
                    <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-numblio-blue flex items-center text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-user-shield mr-2"></i>Gebruikers
                    </a>
                    <a href="{{ route('admin.settings') }}" class="text-gray-600 hover:text-numblio-blue flex items-center text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-cog mr-2"></i>Instellingen
                    </a>
                </div>
                <div class="flex items-center space-x-4 border-l border-gray-200 pl-6">
                    <!-- User Menu -->
                    <div class="relative flex items-center group">
                        <div class="h-8 w-8 rounded-full bg-numblio-blue text-white flex items-center justify-center mr-2">
                            <span class="text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-numblio-blue transition-colors duration-200">{{ Auth::user()->name }}</span>
                            <a href="{{ route('logout') }}" class="block text-xs text-gray-500 hover:text-numblio-blue transition-colors duration-200">
                                Uitloggen <i class="fas fa-sign-out-alt ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="flex-grow py-8">
    @yield('content')
</main>

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
</body>
</html>
