<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numblio Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f9fafb] min-h-screen flex flex-col">
<!-- Navigation -->
<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto">
                <span class="ml-3 text-xl font-bold text-gray-900">Admin</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('clients.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                    <i class="fas fa-users mr-2"></i>Clients
                </a>
                <div class="relative ml-3 flex items-center">
                    <span class="text-gray-700 text-sm mr-2">{{ Auth::user()->name }}</span>
                    <a href="{{ route('logout') }}" class="text-gray-700 hover:text-blue-600 text-sm font-medium">
                        <i class="fas fa-sign-out-alt mr-1"></i>Uitloggen
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="flex-grow">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-white py-4 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Numblio. Alle rechten voorbehouden.</p>
        <div class="mt-2 flex justify-center space-x-4">
            <a href="#" class="text-gray-500 hover:text-gray-700 text-sm">Privacy Policy</a>
            <a href="#" class="text-gray-500 hover:text-gray-700 text-sm">Terms of Service</a>
            <a href="#" class="text-gray-500 hover:text-gray-700 text-sm">Contact</a>
        </div>
    </div>
</footer>
</body>
</html>
