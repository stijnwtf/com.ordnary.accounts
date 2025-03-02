<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <a href="{{ route('notifications.index') }}" class="text-gray-600 hover:text-numblio-blue flex items-center text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-bell mr-2"></i>Notificaties
                    </a>
                    <a href="{{ route('admin.settings') }}" class="text-gray-600 hover:text-numblio-blue flex items-center text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-cog mr-2"></i>Instellingen
                    </a>
                </div>
                <div class="flex items-center space-x-4 border-l border-gray-200 pl-6">
                    <!-- Notifications Dropdown -->
                    <div class="relative" x-data="{ open: false, notifications: [], unreadCount: 0 }" @click.away="open = false" x-init="
                        // Voeg console.log toe om te debuggen
                        console.log('Initializing notifications dropdown');
                        
                        // Haal ongelezen notificaties op
                        fetch('{{ route('notifications.unread') }}')
                            .then(response => {
                                console.log('Response status:', response.status);
                                return response.json();
                            })
                            .then(data => {
                                console.log('Notifications data:', data);
                                notifications = data.notifications;
                                unreadCount = data.unreadCount;
                            })
                            .catch(error => {
                                console.error('Error fetching notifications:', error);
                            });
                        
                        // Refresh notificaties elke 60 seconden
                        setInterval(() => {
                            fetch('{{ route('notifications.unread') }}')
                                .then(response => response.json())
                                .then(data => {
                                    notifications = data.notifications;
                                    unreadCount = data.unreadCount;
                                })
                                .catch(error => {
                                    console.error('Error refreshing notifications:', error);
                                });
                        }, 60000);
                    ">
                        <button @click="open = !open" class="relative p-1 text-gray-600 hover:text-numblio-blue focus:outline-none focus:text-numblio-blue transition-colors duration-200">
                            <i class="fas fa-bell text-xl"></i>
                            <span x-show="unreadCount > 0" x-cloak class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                <span x-text="unreadCount"></span>
                            </span>
                        </button>
                        
                        <div x-show="open" x-cloak class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="notifications-menu">
                            <div class="py-1" role="none">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-sm font-medium text-gray-900">Notificaties</h3>
                                        <div class="flex space-x-2">
                                            <button @click="
                                                fetch('{{ route('notifications.unread') }}')
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        notifications = data.notifications;
                                                        unreadCount = data.unreadCount;
                                                        console.log('Notifications refreshed:', data);
                                                    })
                                                    .catch(error => {
                                                        console.error('Error refreshing notifications:', error);
                                                    });
                                            " class="text-xs text-gray-500 hover:text-gray-700">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                            <a href="{{ route('notifications.index') }}" class="text-xs text-numblio-blue hover:text-numblio-lightblue">
                                                Bekijk alle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Debug info -->
                                <div class="px-4 py-2 border-b border-gray-200 text-xs text-gray-500">
                                    <p>Notifications count: <span x-text="notifications.length"></span></p>
                                    <p>Unread count: <span x-text="unreadCount"></span></p>
                                    <p>Data loaded: <span x-text="notifications.length > 0 || unreadCount > 0 ? 'Yes' : 'No'"></span></p>
                                </div>
                                
                                <template x-if="notifications.length === 0">
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                        Geen notificaties
                                    </div>
                                </template>
                                
                                <template x-for="notification in notifications" :key="notification.id">
                                    <a :href="'{{ url('admin/notifications') }}/' + notification.id" class="block px-4 py-2 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <template x-if="notification.type === 'info'">
                                                    <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                        <i :class="'fas ' + notification.icon"></i>
                                                    </div>
                                                </template>
                                                <template x-if="notification.type === 'success'">
                                                    <div class="h-8 w-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                                        <i :class="'fas ' + notification.icon"></i>
                                                    </div>
                                                </template>
                                                <template x-if="notification.type === 'warning'">
                                                    <div class="h-8 w-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                                                        <i :class="'fas ' + notification.icon"></i>
                                                    </div>
                                                </template>
                                                <template x-if="notification.type === 'error'">
                                                    <div class="h-8 w-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                                        <i :class="'fas ' + notification.icon"></i>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                                <p class="text-xs text-gray-500 truncate" x-text="notification.message"></p>
                                                <div class="flex justify-between mt-1">
                                                    <p class="text-xs text-gray-400" x-text="new Date(notification.created_at).toLocaleString()"></p>
                                                    <span x-show="notification.read_at" class="text-xs text-gray-400">
                                                        <i class="fas fa-check"></i> Gelezen
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                                
                                <div class="border-t border-gray-200 px-4 py-2">
                                    <div class="flex justify-between">
                                        <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-numblio-blue hover:text-numblio-lightblue">
                                                Markeer alles als gelezen
                                            </button>
                                        </form>
                                        <a href="{{ route('notifications.index') }}" class="text-xs text-gray-500 hover:text-gray-700">
                                            Beheer notificaties
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
<footer class="bg-white py-6 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center mb-4 md:mb-0">
                <span class="ml-2 text-sm text-gray-500">&copy; {{ date('Y') }} Numblio. All rights reserved.</span>
            </div>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-500 hover:text-numblio-blue text-sm transition-colors duration-200">Privacy Policy</a>
                <a href="#" class="text-gray-500 hover:text-numblio-blue text-sm transition-colors duration-200">Terms of Service</a>
                <a href="#" class="text-gray-500 hover:text-numblio-blue text-sm transition-colors duration-200">Contact</a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
