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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
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
            border-radius: 6px;
            margin-bottom: 1px;
        }

        .sidebar-item:hover {
            background: #f8fafc;
        }

        .sidebar-item.active {
            background: #f1f5f9;
            color: #374151;
            font-weight: 500;
        }

        .sidebar-item.active i {
            color: #6b7280;
        }

        /* Mobile navigation styling */
        .mobile-nav {
            position: relative;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-nav.active {
            background: #f1f5f9;
            color: #374151;
            font-weight: 500;
        }

        .mobile-nav.active i {
            color: #6b7280;
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
    </style>

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
                                                <i class="fas fa-check-circle mr-1"></i> {{ lang('status.verified', [], 'layout') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Section -->
                            <div class="p-3">
                                <div class="mb-4">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">
                                        {{ lang('sidebar.account_settings', [], 'layout') }}
                                    </div>
                                    <a href="{{ route('account.profile') }}" class="sidebar-item {{ $active_page == 'profile' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-user mr-3 text-gray-500"></i>
                                        {{ lang('sidebar.profile', [], 'layout') }}
                                    </a>
                                    <a href="{{ route('account.password') }}" class="sidebar-item {{ $active_page == 'password' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-lock mr-3 text-gray-500"></i>
                                        {{ lang('sidebar.password', [], 'layout') }}
                                    </a>
                                    <a href="{{ route('account.security') }}" class="sidebar-item {{ $active_page == 'security' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-shield-alt mr-3 text-gray-500"></i>
                                        {{ lang('sidebar.security', [], 'layout') }}
                                    </a>
                                    <a href="{{ route('account.connections') }}" class="sidebar-item {{ $active_page == 'connections' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-globe mr-3 text-gray-500"></i>
                                        {{ lang('sidebar.connections', [], 'layout') }}
                                    </a>
                                    <a href="{{ route('account.subscriptions') }}" class="sidebar-item {{ $active_page == 'subscriptions' ? 'active' : '' }} text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-credit-card mr-3 text-gray-500"></i>
                                        {{ lang('sidebar.subscriptions', [], 'layout') }}
                                    </a>
                                </div>

                                <div class="mt-3 border-t border-gray-200 pt-3">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">
                                        {{ lang('sidebar.support', [], 'layout') }}
                                    </div>
                                    <a href="https://support.numblio.com" class="sidebar-item text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-question-circle mr-3 text-gray-500"></i>
                                        {{ lang('sidebar.help_center', [], 'layout') }}
                                    </a>
                                    <a href="/faq" class="sidebar-item text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium">
                                        <i class="fas fa-info-circle mr-3 text-gray-500"></i>
                                        {{ lang('sidebar.faq', [], 'layout') }}
                                    </a>
                                </div>
                            </div>

                            <!-- Logout Button and Account ID -->
                            <div class="px-4 py-4 border-t border-gray-200 bg-gray-50">
                                <a href="{{ route('logout.simple') }}" class="flex items-center text-sm text-red-600 hover:text-red-700 mb-4 font-medium">
                                    <i class="fas fa-sign-out-alt mr-2"></i> {{ lang('sidebar.logout', [], 'layout') }}
                                </a>
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">{{ lang('sidebar.account_id', [], 'layout') }}:</span>
                                        <span class="text-gray-400">{{ substr(md5($user->id), 0, 8) }}</span>
                                    </p>
                                    <a href="/" class="flex items-center">
                                        <img src="/img/dark.png" alt="Numblio" class="h-4 w-auto">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content Card -->
                <div class="w-full lg:w-3/4">
                    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">

                        <!-- Success Message -->
                        @if (session('status'))
                        <div class="mx-6 mt-6 p-4 bg-green-50 border border-green-200 rounded-lg fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">{{ lang('alerts.success', [], 'layout') }}</h3>
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
                                    <h3 class="text-sm font-medium text-red-800">{{ lang('alerts.error', [], 'layout') }}</h3>
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
            
            <!-- Mobile Footer (only visible on small screens) -->
            <div class="mt-8 py-3 px-4 border-t border-gray-200 bg-white rounded-lg shadow-sm lg:hidden">
                <div class="text-xs text-gray-400">
                    <div class="flex flex-col space-y-2">
                        <span>&copy; {{ date('Y') }} Numblio. {{ lang('footer.rights', [], 'layout') }}</span>
                        <div class="flex items-center space-x-3">
                            <a href="https://numblio.com/documents/privacy" class="hover:text-gray-600">{{ lang('footer.privacy', [], 'layout') }}</a>
                            <a href="https://numblio.com/documents/terms" class="hover:text-gray-600">{{ lang('footer.terms', [], 'layout') }}</a>
                            <a href="https://support.numblio.com" class="hover:text-gray-600">{{ lang('footer.help', [], 'layout') }}</a>
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
                    <span class="sr-only">{{ lang('mobile.close_sidebar', [], 'layout') }}</span>
                    <i class="fas fa-times text-white text-2xl"></i>
                </button>
            </div>
            <!-- Sidebar content -->
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center px-4 mb-4">
                    <div class="flex items-center flex-shrink-0">
                        <img class="h-6 w-auto" src="/img/dark.png" alt="Numblio">
                        <span class="ml-2 text-sm font-semibold text-gray-800">{{ lang('mobile.account_center', [], 'layout') }}</span>
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
                        {{ lang('mobile.profile', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.password') }}" class="mobile-nav {{ $active_page == 'password' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-lock mr-4 text-gray-500"></i>
                        {{ lang('mobile.password', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.security') }}" class="mobile-nav {{ $active_page == 'security' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-shield-alt mr-4 text-gray-500"></i>
                        {{ lang('mobile.security', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.notifications') }}" class="mobile-nav {{ $active_page == 'notifications' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-bell mr-4 text-gray-500"></i>
                        {{ lang('mobile.notifications', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.connections') }}" class="mobile-nav {{ $active_page == 'connections' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-link mr-4 text-gray-500"></i>
                        {{ lang('mobile.connections', [], 'layout') }}
                    </a>
                    <div class="border-t border-gray-200 my-2 pt-2"></div>
                    <a href="https://support.numblio.com" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-question-circle mr-4 text-gray-500"></i>
                        {{ lang('mobile.help_center', [], 'layout') }}
                    </a>
                    <a href="/faq" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-info-circle mr-4 text-gray-500"></i>
                        {{ lang('mobile.faq', [], 'layout') }}
                    </a>
                    <div class="border-t border-gray-200 my-2 pt-2"></div>
                    <a href="{{ route('logout') }}" class="mobile-nav text-red-600 hover:bg-red-50 hover:text-red-700 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-sign-out-alt mr-4 text-red-500"></i>
                        {{ lang('mobile.logout', [], 'layout') }}
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
