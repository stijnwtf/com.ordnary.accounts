<!DOCTYPE html>
<html lang="{{ Lang::getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ lang('meta.description', [], 'layout') }}">
    <title>Numblio · {{ lang('title', [], 'layout') }}</title>
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
            0% {
                opacity: 0.6;
                transform: scale(0.98);
            }

            50% {
                opacity: 1;
                transform: scale(1);
            }

            100% {
                opacity: 0.6;
                transform: scale(0.98);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                    <div class="space-y-6">
                        <!-- Modern User Profile Card -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-8">
                            <!-- Profile Header with Gradient Background -->
                            <div class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 p-6">
                                <div class="absolute inset-0 bg-black/10"></div>
                                <div class="relative z-10">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="relative mb-4">
                                            <img class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-xl ring-4 ring-white/20"
                                                src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=200&d=mp&r=pg"
                                                alt="{{ $user->first_name }} {{ $user->last_name }}">
                                            <div class="absolute -bottom-1 -right-1 h-6 w-6 bg-green-400 rounded-full border-3 border-white shadow-lg flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="text-white">
                                            <h2 class="text-lg font-bold mb-1">{{ $user->first_name }} {{ $user->last_name }}</h2>
                                            <p class="text-blue-100 text-sm mb-3 opacity-90">{{ $user->email }}</p>
                                            
                                            <div class="inline-flex items-center px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-full border border-white/30">
                                                <i class="fas fa-shield-check text-green-300 mr-2 text-sm"></i>
                                                <span class="text-white text-xs font-medium">{{ lang('status.verified', [], 'layout') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Details -->
                            <div class="p-4 bg-gray-50 border-b border-gray-100">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-500 font-medium">{{ lang('sidebar.account_id', [], 'layout') }}</span>
                                        <span class="text-gray-700 font-mono bg-gray-200 px-2 py-1 rounded">#{{ substr(md5($user->id), 0, 8) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-500 font-medium">Lid sinds</span>
                                        <span class="text-gray-700">{{ $user->created_at->format('M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-500 font-medium">Taal</span>
                                        <span class="text-gray-700 uppercase">{{ $user->lang ?? 'nl' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Menu -->
                            <div class="p-4">
                                <div class="space-y-1">
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 py-2 mb-2">
                                        {{ lang('sidebar.account_settings', [], 'layout') }}
                                    </div>
                                    
                                    <a href="{{ route('account.profile') }}" 
                                       class="sidebar-item {{ $active_page == 'profile' ? 'active' : '' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-blue-50 hover:text-blue-700">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-blue-100 mr-3 transition-colors">
                                            <i class="fas fa-user text-gray-500 group-hover:text-blue-600 text-sm"></i>
                                        </div>
                                        <span>{{ lang('sidebar.profile', [], 'layout') }}</span>
                                        @if($active_page == 'profile')
                                            <div class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></div>
                                        @endif
                                    </a>
                                    
                                    <a href="{{ route('account.password') }}" 
                                       class="sidebar-item {{ $active_page == 'password' ? 'active' : '' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-blue-50 hover:text-blue-700">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-blue-100 mr-3 transition-colors">
                                            <i class="fas fa-lock text-gray-500 group-hover:text-blue-600 text-sm"></i>
                                        </div>
                                        <span>{{ lang('sidebar.password', [], 'layout') }}</span>
                                        @if($active_page == 'password')
                                            <div class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></div>
                                        @endif
                                    </a>
                                    
                                    <a href="{{ route('account.security') }}" 
                                       class="sidebar-item {{ $active_page == 'security' ? 'active' : '' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-blue-50 hover:text-blue-700">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-blue-100 mr-3 transition-colors">
                                            <i class="fas fa-shield-alt text-gray-500 group-hover:text-blue-600 text-sm"></i>
                                        </div>
                                        <span>{{ lang('sidebar.security', [], 'layout') }}</span>
                                        @if($active_page == 'security')
                                            <div class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></div>
                                        @endif
                                    </a>
                                    
                                    <a href="{{ route('account.connections') }}" 
                                       class="sidebar-item {{ $active_page == 'connections' ? 'active' : '' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-blue-50 hover:text-blue-700">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-blue-100 mr-3 transition-colors">
                                            <i class="fas fa-globe text-gray-500 group-hover:text-blue-600 text-sm"></i>
                                        </div>
                                        <span>{{ lang('sidebar.connections', [], 'layout') }}</span>
                                        @if($active_page == 'connections')
                                            <div class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></div>
                                        @endif
                                    </a>
                                    
                                    <a href="{{ route('account.subscriptions') }}" 
                                       class="sidebar-item {{ $active_page == 'subscriptions' ? 'active' : '' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-blue-50 hover:text-blue-700">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-blue-100 mr-3 transition-colors">
                                            <i class="fas fa-credit-card text-gray-500 group-hover:text-blue-600 text-sm"></i>
                                        </div>
                                        <span>{{ lang('sidebar.subscriptions', [], 'layout') }}</span>
                                        @if($active_page == 'subscriptions')
                                            <div class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></div>
                                        @endif
                                    </a>
                                </div>

                                <!-- Support Section -->
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 py-2 mb-2">
                                        {{ lang('sidebar.support', [], 'layout') }}
                                    </div>
                                    
                                    <a href="https://support.numblio.com" 
                                       class="sidebar-item group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-700">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200 mr-3 transition-colors">
                                            <i class="fas fa-question-circle text-gray-500 group-hover:text-gray-600 text-sm"></i>
                                        </div>
                                        <span>{{ lang('sidebar.help_center', [], 'layout') }}</span>
                                        <i class="fas fa-external-link-alt text-xs text-gray-400 ml-auto"></i>
                                    </a>
                                    
                                    <a href="https://docs.numblio.com" 
                                       class="sidebar-item group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-700">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200 mr-3 transition-colors">
                                            <i class="fas fa-book text-gray-500 group-hover:text-gray-600 text-sm"></i>
                                        </div>
                                        <span>{{ lang('sidebar.faq', [], 'layout') }}</span>
                                        <i class="fas fa-external-link-alt text-xs text-gray-400 ml-auto"></i>
                                    </a>
                                </div>

                                <!-- Logout Button -->
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <a href="{{ route('logout.simple') }}" 
                                       class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-red-50 hover:text-red-700 text-gray-600">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-red-100 mr-3 transition-colors">
                                            <i class="fas fa-sign-out-alt text-gray-500 group-hover:text-red-600 text-sm"></i>
                                        </div>
                                        <span>{{ lang('sidebar.logout', [], 'layout') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                </div>
                            </div>

                            <!-- Logout Button and Account ID -->
                            <div class="px-4 py-4 border-t border-gray-200 bg-gray-50">
                                <a href="{{ route('logout.simple') }}"
                                    class="flex items-center text-sm text-red-600 hover:text-red-700 mb-4 font-medium">
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
                            <div class="mx-6 mt-6 p-4 bg-red-50 border border-red-200 rounded-lg fade-in">
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
                            <a href="https://numblio.com/documents/privacy"
                                class="hover:text-gray-600">{{ lang('footer.privacy', [], 'layout') }}</a>
                            <a href="https://numblio.com/documents/terms"
                                class="hover:text-gray-600">{{ lang('footer.terms', [], 'layout') }}</a>
                            <a href="https://support.numblio.com"
                                class="hover:text-gray-600">{{ lang('footer.help', [], 'layout') }}</a>
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
                        <img class="h-6 w-auto" src="/img/dark.png" alt="Numblio">
                        <span
                            class="ml-2 text-sm font-semibold text-gray-800">{{ lang('mobile.account_center', [], 'layout') }}</span>
                    </div>
                </div>
                <div
                    class="border-t border-b border-gray-200 py-4 px-4 mb-4 bg-gradient-to-r from-blue-50 to-indigo-50">
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
                    <a href="{{ route('account.profile') }}"
                        class="mobile-nav {{ $active_page == 'profile' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-user mr-4 text-gray-500"></i>
                        {{ lang('mobile.profile', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.password') }}"
                        class="mobile-nav {{ $active_page == 'password' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-lock mr-4 text-gray-500"></i>
                        {{ lang('mobile.password', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.security') }}"
                        class="mobile-nav {{ $active_page == 'security' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-shield-alt mr-4 text-gray-500"></i>
                        {{ lang('mobile.security', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.notifications') }}"
                        class="mobile-nav {{ $active_page == 'notifications' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-bell mr-4 text-gray-500"></i>
                        {{ lang('mobile.notifications', [], 'layout') }}
                    </a>
                    <a href="{{ route('account.connections') }}"
                        class="mobile-nav {{ $active_page == 'connections' ? 'active' : '' }} text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-link mr-4 text-gray-500"></i>
                        {{ lang('mobile.connections', [], 'layout') }}
                    </a>
                    <div class="border-t border-gray-200 my-2 pt-2"></div>
                    <a href="https://support.numblio.com"
                        class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-question-circle mr-4 text-gray-500"></i>
                        {{ lang('mobile.help_center', [], 'layout') }}
                    </a>
                    <a href="/faq"
                        class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-info-circle mr-4 text-gray-500"></i>
                        {{ lang('mobile.faq', [], 'layout') }}
                    </a>
                    <div class="border-t border-gray-200 my-2 pt-2"></div>
                    <a href="{{ route('logout') }}"
                        class="mobile-nav text-red-600 hover:bg-red-50 hover:text-red-700 group flex items-center px-3 py-2.5 text-base font-medium rounded-md">
                        <i class="fas fa-sign-out-alt mr-4 text-red-500"></i>
                        {{ lang('mobile.logout', [], 'layout') }}
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Loading screen handling
        document.addEventListener('DOMContentLoaded', function () {
            // Hide loading screen after DOM content is loaded
            setTimeout(function () {
                document.body.classList.add('loaded');
            }, 300); // Small delay to ensure smooth transition
        });

        // Fallback for slow connections - hide loading screen after max wait time
        setTimeout(function () {
            document.body.classList.add('loaded');
        }, 2000);

        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            const mobileUserMenu = document.getElementById('mobile-user-menu');

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function () {
                    mobileSidebar.classList.remove('hidden');
                });
            }

            if (mobileUserMenu) {
                mobileUserMenu.addEventListener('click', function () {
                    mobileSidebar.classList.remove('hidden');
                });
            }

            if (closeSidebar) {
                closeSidebar.addEventListener('click', function () {
                    mobileSidebar.classList.add('hidden');
                });
            }

            // Close sidebar when clicking outside
            document.addEventListener('click', function (event) {
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
                toggle.addEventListener('click', function () {
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