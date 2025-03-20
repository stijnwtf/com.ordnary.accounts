<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage your Numblio account settings">
    <title>@yield('title', 'My Account') | Numblio</title>

    <!-- Inline critical CSS to prevent FOUC -->
    <style>
        /* Critical rendering path styles */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f9fafb;
        }

        /* Loading screen styles */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #f9fafb;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        #loading-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 20px;
            animation: pulse 1.5s infinite ease-in-out;
        }

        #loading-spinner {
            width: 30px;
            height: 30px;
            border: 3px solid rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            border-top-color: #3b82f6;
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
        }
        .sidebar-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        .sidebar-item.active {
            border-left: 3px solid #3b82f6;
            background-color: rgba(59, 130, 246, 0.1);
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

    <div class="content-container bg-[#f9fafb] min-h-screen py-8 px-4 md:py-12">
        <div class="max-w-7xl mx-auto">
            <!-- Main container with two cards side by side -->
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <!-- Left Sidebar Card -->
                <div class="w-full lg:w-1/4">
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden sticky top-8">
                        <!-- Account Summary -->
                        <div class="p-5 border-b border-gray-200">
                            <div class="flex items-center space-x-3">
                                <img class="h-12 w-12 rounded-full object-cover border-2 border-blue-100"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=3b82f6&background=dbeafe"
                                     alt="{{ $user->name }}">
                                <div>
                                    <h2 class="text-sm font-semibold text-gray-900">{{ $user->name }}</h2>
                                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="mt-3 flex">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mr-1">
                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user-shield mr-1"></i> Active
                                </span>
                            </div>
                        </div>

                        <!-- Navigation Section -->
                        <div class="py-2">
                            <div class="px-3 py-2">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">
                                    Account Settings
                                </div>
                                <a href="{{ route('account.profile') }}" class="sidebar-item {{ $active_page == 'profile' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                    <i class="fas fa-user mr-3 text-gray-500"></i>
                                    Profile Information
                                </a>
                                <a href="{{ route('account.password') }}" class="sidebar-item {{ $active_page == 'password' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                    <i class="fas fa-lock mr-3 text-gray-500"></i>
                                    Password
                                </a>
                                <a href="{{ route('account.security') }}" class="sidebar-item {{ $active_page == 'security' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                    <i class="fas fa-shield-alt mr-3 text-gray-500"></i>
                                    Security
                                </a>
                                <a href="{{ route('account.notifications') }}" class="sidebar-item {{ $active_page == 'notifications' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                    <i class="fas fa-bell mr-3 text-gray-500"></i>
                                    Notifications
                                </a>
                                <a href="{{ route('account.connections') }}" class="sidebar-item {{ $active_page == 'connections' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                    <i class="fas fa-link mr-3 text-gray-500"></i>
                                    Connections
                                </a>
                            </div>

                            <div class="px-3 py-2 border-t border-gray-200 mt-2">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">
                                    Support
                                </div>
                                <a href="https://support.numblio.com" class="sidebar-item text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                    <i class="fas fa-question-circle mr-3 text-gray-500"></i>
                                    Help Center
                                </a>
                                <a href="/faq" class="sidebar-item text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                    <i class="fas fa-info-circle mr-3 text-gray-500"></i>
                                    FAQ
                                </a>
                            </div>
                        </div>

                        <!-- Logout Button and Account ID -->
                        <div class="px-4 py-4 border-t border-gray-200">
                            <a href="{{ route('logout') }}" class="flex items-center text-sm text-red-600 hover:text-red-700 mb-4">
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
                    </div>

                    <!-- Mobile menu button -->
                    <div class="lg:hidden fixed bottom-4 right-4 z-10">
                        <button id="mobile-menu-button" class="rounded-full bg-blue-600 p-3 text-white shadow-lg">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>

                <!-- Right Content Card -->
                <div class="w-full lg:w-3/4">
                    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">



                        <!-- Welcome header -->
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">Oh hey, $user->name!</h2>
                            <p class="text-sm text-gray-600 mt-1">@yield('subheader', 'Manage your personal information and security settings for your Numblio-account.')</p>
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
                        <div class="px-6 pt-6">
                            @yield('content')
                        </div>
                    </div>

                    <!-- Footer -->
                    <footer class="mt-8 text-xs text-gray-400">
                        <div class="flex flex-col space-y-1">
                            <span>&copy; {{ date('Y') }} Numblio. All rights reserved.</span>
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
                        <img class="h-5 w-auto" src="/img/dark.png" alt="Numblio">
                        <span class="ml-2 text-sm font-semibold text-gray-800">My Account</span>
                    </div>
                </div>
                <div class="border-t border-b border-gray-200 py-4 px-4 mb-4">
                    <div class="flex items-center">
                        <img class="h-10 w-10 rounded-full object-cover border-2 border-blue-100"
                             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=3b82f6&background=dbeafe"
                             alt="{{ $user->name }}">
                        <div class="ml-3">
                            <h2 class="text-sm font-semibold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                <nav class="px-2 space-y-1">
                    <a href="{{ route('account.profile') }}" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-user mr-4 text-gray-500"></i>
                        Profile
                    </a>
                    <a href="{{ route('account.password') }}" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-lock mr-4 text-gray-500"></i>
                        Password
                    </a>
                    <a href="{{ route('account.security') }}" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-shield-alt mr-4 text-gray-500"></i>
                        Security
                    </a>
                    <a href="{{ route('account.notifications') }}" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-bell mr-4 text-gray-500"></i>
                        Notifications
                    </a>
                    <a href="{{ route('account.connections') }}" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-link mr-4 text-gray-500"></i>
                        Connected Services
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
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');

            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileSidebar.classList.remove('hidden');
                });
            }

            if (closeSidebar) {
                closeSidebar.addEventListener('click', function() {
                    mobileSidebar.classList.add('hidden');
                });
            }

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
