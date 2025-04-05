<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Manage your Numblio-account settings">
    <title>Numblio · Account Center</title>
    <link rel="icon" href="https://numblio.com/img/icon_black.png" type="image/gif" sizes="20x20">

    <!-- Inline critical CSS to prevent FOUC -->
    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #dbeafe;
            --primary-dark: #1e40af;
            --success: #10b981;
            --success-light: #d1fae5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-tertiary: #9ca3af;
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --border: #e5e7eb;
        }

        /* Critical rendering path styles */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }

        /* Loading screen styles */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--bg-secondary);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        #loading-logo {
            width: 50px;
            height: 50px;
            margin-bottom: 20px;
            animation: pulse 1.5s infinite ease-in-out;
        }

        #loading-spinner {
            width: 30px;
            height: 30px;
            border: 3px solid rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s infinite linear;
        }

        .loaded #loading-screen {
            opacity: 0;
            pointer-events: none;
        }

        @keyframes pulse {
            0% { opacity: 0.6; transform: scale(0.98); }
            50% { opacity: 1; transform: scale(1); }
            100% { opacity: 0.6; transform: scale(0.98); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Prevent content flash */
        .content-container {
            opacity: 0;
            transition: opacity 0.3s ease-in;
        }

        .loaded .content-container {
            opacity: 1;
        }

        .sidebar-item {
            transition: all 0.2s ease;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
        }

        .sidebar-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .sidebar-item.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--primary);
            font-weight: 500;
        }

        .sidebar-item.active i {
            color: var(--primary) !important;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .mobile-nav.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--primary);
        }

        .mobile-nav.active i {
            color: var(--primary);
        }
    </style>

    <!-- Preload key resources -->
    <link rel="preload" href="https://cdn.tailwindcss.com" as="script">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style">

    <!-- Deferred loading for better performance -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com" defer></script>

    <!-- Using reCAPTCHA v2 Invisible instead of v3 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <!-- Loading screen -->
    <div id="loading-screen">
        <div id="loading-spinner"></div>
    </div>

    <div class="content-container bg-[#f9fafb] min-h-screen">
        <!-- Top navigation bar for mobile -->
        <header class="lg:hidden bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center space-x-3">
                    <button id="mobile-menu-toggle" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <a href="/" class="flex items-center">
                        <img src="/img/dark.png" alt="Numblio" class="h-6 w-auto">
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="relative">
                        <button id="mobile-user-menu" class="flex items-center">
                            <img class="h-8 w-8 rounded-full object-cover border-2 border-blue-100"
                                src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=200&d=mp&r=pg"
                                alt="{{ $user->first_name }} {{ $user->last_name }}">
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-4 py-6 lg:py-8">
            <!-- Main container with two cards side by side -->
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <!-- Left Sidebar Section -->
                <div class="w-full lg:w-1/4 lg:block hidden">
                    <div class="space-y-4">
                        <!-- Sidebar Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-8">
                            <!-- Account Summary -->
                            <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <img class="h-14 w-14 rounded-full object-cover border-2 border-white shadow-sm"
                                            src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=200&d=mp&r=pg"
                                            alt="{{ $user->first_name }} {{ $user->last_name }}">
                                        <div class="absolute bottom-0 right-0 h-4 w-4 bg-green-400 rounded-full border-2 border-white"></div>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                        <div class="mt-1 flex">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mr-1">
                                                <i class="fas fa-check-circle mr-1"></i> Verified
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Section -->
                            <div class="p-3">
                                <div class="mb-4">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">
                                        Account Settings
                                    </div>
                                    <a href="{{ route('account.profile') }}" class="sidebar-item {{ $active_page == 'profile' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-user mr-3 text-gray-500"></i>
                                        Profile Information
                                    </a>
                                    <a href="{{ route('account.password') }}" class="sidebar-item {{ $active_page == 'password' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-lock mr-3 text-gray-500"></i>
                                        Password
                                    </a>
                                    <a href="{{ route('account.security') }}" class="sidebar-item {{ $active_page == 'security' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-shield-alt mr-3 text-gray-500"></i>
                                        Security
                                    </a>
                                    <a href="{{ route('account.connections') }}" class="sidebar-item {{ $active_page == 'connections' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-globe mr-3 text-gray-500"></i>
                                        Connections
                                    </a>
                                </div>

                                <div class="mt-3 border-t border-gray-200 pt-3">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">
                                        Support
                                    </div>
                                    <a href="https://support.numblio.com" class="sidebar-item text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-question-circle mr-3 text-gray-500"></i>
                                        Help Center
                                    </a>
                                    <a href="/faq" class="sidebar-item text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-info-circle mr-3 text-gray-500"></i>
                                        FAQ
                                    </a>
                                </div>
                            </div>

                            <!-- Logout Button and Account ID -->
                            <div class="px-4 py-4 border-t border-gray-200 bg-gray-50">
                                <a href="{{ route('logout') }}" class="flex items-center text-sm text-red-600 hover:text-red-700 mb-4 font-medium">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Log out
                                </a>
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Account ID:</span>
                                        <span class="text-gray-400">{{ substr(md5($user->id), 0, 8) }}</span>
                                    </p>
                                    <a href="/" class="flex items-center">
                                        <img src="/img/dark.png" alt="Numblio" class="h-4 w-auto">
                                    </a>
                                </div>
                            </div>

                            <!-- Footer (moved here from outside) -->
                            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                <div class="text-xs text-gray-400">
                                    <div class="flex flex-col space-y-1">
                                        <span>&copy; {{ date('Y') }} Numblio. All rights reserved.</span>
                                        <div class="flex items-center space-x-3 mt-1">
                                            <a href="https://numblio.com/documents/privacy" class="hover:text-gray-600">Privacy</a>
                                            <a href="https://numblio.com/documents/terms" class="hover:text-gray-600">Terms</a>
                                            <a href="https://support.numblio.com" class="hover:text-gray-600">Help</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content Card -->
                <div class="w-full lg:w-3/4">
                    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
                        <!-- Welcome header -->
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-white to-blue-50">
                            <h2 class="text-xl font-bold text-gray-900">Hi, {{$user->first_name}}!</h2>
                            <p class="text-sm text-gray-600 mt-1">Welcome to your personal account settings. Manage your profile, security, and preferences here.</p>
                        </div>

                        <!-- Success Message -->
                        @if (session('status'))
                        <div class="mx-6 mt-6 p-4 bg-green-50 border border-green-200 rounded-lg fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Success!</h3>
                                    <div class="mt-1 text-sm text-green-700">
                                        {{ session('status') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Error Message -->
                        @if ($errors->any())
                        <div class="mx-6 mt-6 p-4 bg-red-50 border border-red-200 rounded-lg fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Something went wrong.</h3>
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

                        <!-- Content Section -->
                        <div class="p-6">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile sidebar for small screens -->
    <div id="mobile-sidebar" class="fixed inset-0 flex z-40 hidden">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button id="close-sidebar" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <i class="fas fa-times text-white text-2xl"></i>
                </button>
            </div>
            <!-- Sidebar content -->
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center px-4 mb-4">
                    <div class="flex items-center flex-shrink-0">
                        <img class="h-6 w-auto" src="/img/dark.png" alt="Numblio">
                        <span class="ml-2 text-sm font-semibold text-gray-800">Account Center</span>
                    </div>
                </div>
                <div class="border-t border-b border-gray-200 py-4 px-4 mb-4 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center">
                        <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm"
                             src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=200&d=mp&r=pg"
                             alt="{{ $user->name }}">
                        <div class="ml-3">
                            <h2 class="text-sm font-semibold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                <nav class="px-2 space-y-1">
                    <a href="{{ route('account.profile') }}" class="mobile-nav {{ $active_page == 'profile' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-user mr-4 text-gray-500"></i>
                        Profile
                    </a>
                    <a href="{{ route('account.password') }}" class="mobile-nav {{ $active_page == 'password' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-lock mr-4 text-gray-500"></i>
                        Password
                    </a>
                    <a href="{{ route('account.security') }}" class="mobile-nav {{ $active_page == 'security' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-shield-alt mr-4 text-gray-500"></i>
                        Security
                    </a>
                    <a href="{{ route('account.notifications') }}" class="mobile-nav {{ $active_page == 'notifications' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-bell mr-4 text-gray-500"></i>
                        Notifications
                    </a>
                    <a href="{{ route('account.connections') }}" class="mobile-nav {{ $active_page == 'connections' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-link mr-4 text-gray-500"></i>
                        Connected Services
                    </a>
                    <div class="border-t border-gray-200 my-2 pt-2"></div>
                    <a href="https://support.numblio.com" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-question-circle mr-4 text-gray-500"></i>
                        Help Center
                    </a>
                    <a href="/faq" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-info-circle mr-4 text-gray-500"></i>
                        FAQ
                    </a>
                    <div class="border-t border-gray-200 my-2 pt-2"></div>
                    <a href="{{ route('logout') }}" class="mobile-nav text-red-600 hover:bg-red-50 hover:text-red-700 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-sign-out-alt mr-4 text-red-500"></i>
                        Log out
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Loading screen handling
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading screen after DOM content is loaded
            setTimeout(function() {
                document.body.classList.add('loaded');
            }, 300); // Small delay to ensure smooth transition
        });

        // Fallback for slow connections - hide loading screen after max wait time
        setTimeout(function() {
            document.body.classList.add('loaded');
        }, 2000);

        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            const mobileUserMenu = document.getElementById('mobile-user-menu');

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileSidebar.classList.remove('hidden');
                });
            }

            if (mobileUserMenu) {
                mobileUserMenu.addEventListener('click', function() {
                    mobileSidebar.classList.remove('hidden');
                });
            }

            if (closeSidebar) {
                closeSidebar.addEventListener('click', function() {
                    mobileSidebar.classList.add('hidden');
                });
            }

            // Close sidebar when clicking outside
            document.addEventListener('click', function(event) {
                if (mobileSidebar && !mobileSidebar.classList.contains('hidden')) {
                    if (!event.target.closest('#mobile-sidebar') &&
                        !event.target.closest('#mobile-menu-toggle') &&
                        !event.target.closest('#mobile-user-menu')) {
                        mobileSidebar.classList.add('hidden');
                    }
                }
            });

            // Password visibility toggle (if present on page)
            const passwordToggles = document.querySelectorAll('.password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.closest('.relative').querySelector('input');
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // Automatically hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.fade-in');
            if (alerts.length > 0) {
                setTimeout(() => {
                    alerts.forEach(alert => {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            alert.style.display = 'none';
                        }, 300);
                    });
                }, 5000);
            }

            // Lazy load images
            const lazyImages = document.querySelectorAll('img.lazy');
            if ('IntersectionObserver' in window && lazyImages.length > 0) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const image = entry.target;
                            image.src = image.dataset.src;
                            image.classList.add('loaded');
                            imageObserver.unobserve(image);
                        }
                    });
                });

                lazyImages.forEach(image => {
                    imageObserver.observe(image);
                });
            } else if (lazyImages.length > 0) {
                // Fallback for browsers without IntersectionObserver
                lazyImages.forEach(image => {
                    image.src = image.dataset.src;
                    image.classList.add('loaded');
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
