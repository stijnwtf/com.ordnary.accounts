<!DOCTYPE html>
<html lang="{{ Lang::getLocale() }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ lang('meta.description', [], 'layout') }}">
    <title>Account Center</title>

    <link rel="icon" type="image/x-icon" href="/favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicons/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/favicons/android-chrome-512x512.png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap');

        :root {
            --primary: #2563eb;
            --primary-light: #dbeafe;
            --primary-dark: #1d4ed8;
            --success: #059669;
            --success-light: #d1fae5;
            --danger: #dc2626;
            --danger-light: #fee2e2;
            --warning: #d97706;
            --warning-light: #fef3c7;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        * {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Responsive container */
        @media (max-width: 1023px) {
            .content-container {
                padding: 0;
            }
        }

        /* Prevent horizontal scroll on mobile */
        * {
            box-sizing: border-box;
        }

        /* Better responsive form handling */
        input,
        select,
        textarea {
            max-width: 100%;
            min-width: 0;
        }
        
        /* Ensure labels and text don't overflow */
        label {
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
        }
        
        /* Prevent text overflow in containers */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-item {
            position: relative;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            transform: translateX(4px);
        }

        .nav-item.active {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 3px solid var(--primary);
            color: var(--primary);
            font-weight: 500;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary);
            border-radius: 0 2px 2px 0;
        }

        .profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .fade-up {
            animation: fadeUp 0.6s ease-out;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .content-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }

        .status-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);
        }

        .mobile-menu-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-menu-btn:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: scale(1.05);
        }

        /* Sidebar item styling */
        .sidebar-item {
            position: relative;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 999px;
            margin-bottom: 2px;
        }

        .sidebar-item:hover {
            text-decoration: underline;
        }

        .sidebar-item.active {
            background: #e7f3ff;
            color: #0866ff;
            font-weight: 500;
        }

        .sidebar-item.active i {
            color: #0866ff;
        }

        .sidebar-item.active:hover {
            color: #0866ff;
            text-decoration-color: #0866ff;
        }

        /* Mobile navigation styling */
        .mobile-nav {
            position: relative;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 999px;
        }

        .mobile-nav:hover {
            text-decoration: underline;
            background: transparent;
        }

        .mobile-nav.active {
            background: #e7f3ff;
            color: #0866ff;
            font-weight: 500;
        }

        .mobile-nav.active i {
            color: #0866ff;
        }

        .mobile-nav.active:hover {
            color: #0866ff;
            text-decoration-color: #0866ff;
        }

        /* Loading animation */
        .loading-spinner {
            border: 3px solid rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            border-top: 3px solid #3b82f6;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Alpine.js cloak - hide elements until Alpine.js is loaded */
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Using reCAPTCHA v2 Invisible instead of v3 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <!-- Loading screen -->
    <div id="loading-screen">
        <div id="loading-spinner"></div>
    </div>

    <div class="content-container bg-white min-h-screen">
        <!-- Top navigation bar for mobile -->
        <header class="lg:hidden bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3">
                <div class="flex items-center space-x-3">
                    <button id="mobile-menu-toggle" class="text-gray-500 hover:text-gray-700 p-2 -ml-2">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <a href="/" class="flex items-center">
                        <img src="https://cdn.ordnary.com/site_assets/ordnary-black.png" alt="ordnary"
                            class="h-5 sm:h-6 w-auto">
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="relative">
                        <button id="mobile-user-menu" class="flex items-center p-1">
                            <img class="h-8 w-8 rounded-full object-cover border-2 border-blue-100"
                                src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=200&d=initials&r=pg"
                                alt="{{ $user->first_name }} {{ $user->last_name }}">
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto lg:px-8">
            <!-- Main container with sidebar and content -->
            <div class="flex flex-col lg:flex-row min-h-screen">
                <!-- Left Sidebar Section -->
                <div class="hidden lg:block lg:w-80 border-r border-gray-200 py-8 pr-8">
                    <div class="space-y-1">
                        <!-- Account Summary -->
                        <div class="px-4 py-4 mb-6">
                            <div class="flex items-center justify-between space-x-3">
                                <div class="flex items-center space-x-3 min-w-0 flex-1">
                                    <img class="h-14 w-14 rounded-full object-cover"
                                        src="https://ui-avatars.com/api/?name={{ urlencode($user->first_name . ' ' . $user->last_name) }}&size=200&background=E5E7EB&color=4B5563&font-size=0.5&bold=true"
                                        alt="{{ $user->first_name }} {{ $user->last_name }}">

                                    <div class="min-w-0 flex-1">
                                        <h1 class="text-base font-semibold text-gray-900">{{ $user->first_name }}
                                            {{ $user->last_name }}</h1>
                                        <form method="POST" action="{{ route('logout') }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-sm text-red-600 hover:text-red-700 hover:underline font-medium transition-all">
                                                {{ lang('sidebar.logout', [], 'layout') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Section -->
                        <div>
                            <div class="mb-1">
                                <div
                                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-2 mb-1">
                                    {{ lang('sidebar.account_settings', [], 'layout') }}
                                </div>
                                <a href="{{ route('account.profile') }}"
                                    class="sidebar-item {{ $active_page == 'profile' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-4 py-3 text-[15px] font-medium">
                                    {{ lang('sidebar.profile', [], 'layout') }}
                                </a>
                                <a href="{{ route('account.password') }}"
                                    class="sidebar-item {{ $active_page == 'password' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-4 py-3 text-[15px] font-medium">
                                    {{ lang('sidebar.password', [], 'layout') }}
                                </a>
                                <a href="{{ route('account.security') }}"
                                    class="sidebar-item {{ $active_page == 'security' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-4 py-3 text-[15px] font-medium">
                                    {{ lang('sidebar.security', [], 'layout') }}
                                </a>
                                <a href="{{ route('account.connections') }}"
                                    class="sidebar-item {{ $active_page == 'connections' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-4 py-3 text-[15px] font-medium">
                                    {{ lang('sidebar.connections', [], 'layout') }}
                                </a>
                                <a href="{{ route('account.subscriptions') }}"
                                    class="sidebar-item {{ $active_page == 'subscriptions' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-4 py-3 text-[15px] font-medium">
                                    {{ lang('sidebar.subscriptions', [], 'layout') }}
                                </a>
                            </div>
                        </div>

                        <div class="px-4 mt-4 ">
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-400">
                                    <span class="font-medium">{{ lang('sidebar.account_id', [], 'layout') }}:</span>
                                    <span class="text-gray-400">{{ substr(md5($user->id), 0, 8) }}</span>
                                </p>
                                <a href="/" class="flex items-center">
                                    <img src="https://cdn.ordnary.com/site_assets/ordnary-black.png" alt="ordnary"
                                        class="h-3 w-auto opacity-50">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content Area -->
                <div class="flex-1 lg:py-8 lg:pl-8 w-full">
                    <div class="bg-white w-full px-4 sm:px-6 lg:px-0 py-6 lg:py-8 overflow-x-hidden"

                        <!-- Success Message -->
                        @if (session('status'))
                            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg fade-in">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800">
                                            {{ lang('alerts.success', [], 'layout') }}</h3>
                                        <div class="mt-1 text-sm text-green-700">
                                            {{ session('status') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Error Message -->
                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg fade-in">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">
                                            {{ lang('alerts.error', [], 'layout') }}</h3>
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
                        <div>
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
                <button id="close-sidebar"
                    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">{{ lang('mobile.close_sidebar', [], 'layout') }}</span>
                    <i class="fas fa-times text-white text-2xl"></i>
                </button>
            </div>
            <!-- Sidebar content -->
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center px-4 mb-4">
                    <div class="flex items-center flex-shrink-0">
                        <img class="h-6 w-auto" src="https://cdn.ordnary.com/site_assets/ordnary-black.png"
                            alt="ordnary">
                    </div>
                </div>
                <div class="py-4 px-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 min-w-0 flex-1">
                            <img class="h-12 w-12 rounded-full object-cover flex-shrink-0"
                                src="https://ui-avatars.com/api/?name={{ urlencode($user->first_name . ' ' . $user->last_name) }}&size=200&background=E5E7EB&color=4B5563&font-size=0.5&bold=true"
                                alt="{{ $user->first_name }} {{ $user->last_name }}">
                            <div class="min-w-0 flex-1">
                                <h2 class="text-base font-semibold text-gray-900 truncate">{{ $user->first_name }}
                                    {{ $user->last_name }}</h2>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="text-sm text-red-600 hover:text-red-700 hover:underline font-medium transition-all">
                                        {{ lang('sidebar.logout', [], 'layout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200 mb-4"></div>
                <nav class="px-3 space-y-1">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2 mb-1">
                        {{ lang('sidebar.account_settings', [], 'layout') }}
                    </div>
                    <a href="{{ route('account.profile') }}"
                        class="mobile-nav {{ $active_page == 'profile' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-3 py-2.5 text-[15px] font-medium">
                        {{ lang('mobile.profile', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.password') }}"
                        class="mobile-nav {{ $active_page == 'password' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-3 py-2.5 text-[15px] font-medium">
                        {{ lang('mobile.password', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.security') }}"
                        class="mobile-nav {{ $active_page == 'security' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-3 py-2.5 text-[15px] font-medium">
                        {{ lang('mobile.security', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.notifications') }}"
                        class="mobile-nav {{ $active_page == 'notifications' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-3 py-2.5 text-[15px] font-medium">
                        {{ lang('mobile.notifications', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.connections') }}"
                        class="mobile-nav {{ $active_page == 'connections' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-3 py-2.5 text-[15px] font-medium">
                        {{ lang('mobile.connections', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.subscriptions') }}"
                        class="mobile-nav {{ $active_page == 'subscriptions' ? 'active' : '' }} text-gray-700 hover:text-gray-900 group flex items-center px-3 py-2.5 text-[15px] font-medium">
                        {{ lang('sidebar.subscriptions', [], 'layout') }}
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
