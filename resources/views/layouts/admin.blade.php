<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Numblio</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="/admin" class="font-bold text-xl">Numblio Admin</a>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('clients.index') }}" class="mr-4 hover:underline">Clients</a>
                    <div class="relative ml-3">
                        <span>{{ Auth::user()->name }}</span>
                        <a href="{{ route('logout') }}" class="ml-4 hover:underline">Uitloggen</a>
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
    <footer class="bg-white py-4 border-t">
        <div class="container mx-auto px-4">
            <p class="text-gray-600 text-center">&copy; {{ date('Y') }} Numblio. Alle rechten voorbehouden.</p>
        </div>
    </footer>
</div>
</body>
</html>
