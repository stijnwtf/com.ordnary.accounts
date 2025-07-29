<!DOCTYPE html>
<html lang="{{ Lang::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ lang('meta.description', [], 'layout') }}">
    <title>Numblio · {{ lang('title', [], 'layout') }}</title>
    <link rel="icon" href="https://numblio.com/img/icon_black.png" type="image/gif" sizes="20x20">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Inline critical CSS -->
    <style>
        :root {
            /* Modern design system inspired by Microsoft/GitHub */
            --color-accent: #0969da;
            --color-accent-hover: #0550ae;
            --color-accent-emphasis: #0a3069;
            --color-accent-subtle: #ddf4ff;
            --color-accent-muted: #54aeff;
            
            --color-success: #1a7f37;
            --color-success-subtle: #dcffe4;
            --color-attention: #bf8700;
            --color-attention-subtle: #fff8c5;
            --color-danger: #cf222e;
            --color-danger-subtle: #ffebe9;
            
            /* Neutral palette */
            --color-fg-default: #1f2328;
            --color-fg-muted: #656d76;
            --color-fg-subtle: #6e7781;
            --color-fg-on-emphasis: #ffffff;
            
            --color-canvas-default: #ffffff;
            --color-canvas-subtle: #f6f8fa;
            --color-canvas-inset: #f6f8fa;
            
            --color-border-default: #d1d9e0;
            --color-border-muted: #d8dee4;
            --color-border-subtle: #eaeef2;
            
            /* Shadows */
            --shadow-small: 0 1px 3px rgba(31, 35, 40, 0.12), 0 1px 2px rgba(31, 35, 40, 0.24);
            --shadow-medium: 0 3px 6px rgba(31, 35, 40, 0.15), 0 2px 4px rgba(31, 35, 40, 0.12);
            --shadow-large: 0 8px 24px rgba(31, 35, 40, 0.12);
            --shadow-extra-large: 0 12px 28px rgba(31, 35, 40, 0.15);
            
            /* Typography */
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif;
            --font-size-0: 0.75rem;
            --font-size-1: 0.875rem;
            --font-size-2: 1rem;
            --font-size-3: 1.25rem;
            --font-size-4: 1.5rem;
            --font-size-5: 2rem;
            
            /* Spacing scale */
            --space-2: 0.125rem;
            --space-4: 0.25rem;
            --space-8: 0.5rem;
            --space-12: 0.75rem;
            --space-16: 1rem;
            --space-20: 1.25rem;
            --space-24: 1.5rem;
            --space-32: 2rem;
            --space-40: 2.5rem;
            --space-48: 3rem;
            --space-64: 4rem;
            
            /* Border radius */
            --radius-small: 0.375rem;
            --radius-medium: 0.5rem;
            --radius-large: 0.75rem;
            --radius-full: 50%;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: var(--font-family);
            font-size: var(--font-size-2);
            line-height: 1.5;
            color: var(--color-fg-default);
            background-color: var(--color-canvas-subtle);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Loading screen */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--color-canvas-default);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.3s ease-out;
        }

        #loading-spinner {
            width: 24px;
            height: 24px;
            border: 2px solid var(--color-border-muted);
            border-radius: var(--radius-full);
            border-top-color: var(--color-accent);
            animation: spin 0.8s infinite linear;
        }

        .loaded #loading-screen {
            opacity: 0;
            pointer-events: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Content container */
        .content-container {
            opacity: 0;
            transition: opacity 0.3s ease-in;
        }

        .loaded .content-container {
            opacity: 1;
        }

        /* Top navigation */
        .app-header {
            background: var(--color-canvas-default);
            border-bottom: 1px solid var(--color-border-default);
            position: sticky;
            top: 0;
            z-index: 30;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.95);
        }

        .header-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: var(--space-16) var(--space-24);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: var(--space-12);
            text-decoration: none;
            color: var(--color-fg-default);
        }

        .header-logo {
            height: 24px;
            width: auto;
        }

        .header-title {
            font-size: var(--font-size-2);
            font-weight: 600;
            margin: 0;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: var(--space-12);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-full);
            border: 2px solid var(--color-border-default);
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            padding: var(--space-8);
            color: var(--color-fg-muted);
            cursor: pointer;
            border-radius: var(--radius-small);
        }

        .mobile-menu-toggle:hover {
            background: var(--color-canvas-inset);
            color: var(--color-fg-default);
        }

        /* Main layout */
        .app-layout {
            max-width: 1280px;
            margin: 0 auto;
            padding: var(--space-32) var(--space-24);
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--space-48);
            align-items: start;
        }

        /* Sidebar */
        .sidebar {
            position: sticky;
            top: calc(80px + var(--space-32));
        }

        .sidebar-card {
            background: var(--color-canvas-default);
            border: 1px solid var(--color-border-default);
            border-radius: var(--radius-medium);
            overflow: hidden;
        }

        .sidebar-header {
            padding: var(--space-24);
            border-bottom: 1px solid var(--color-border-muted);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: var(--space-16);
        }

        .user-profile-avatar {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-full);
            border: 2px solid var(--color-border-default);
            position: relative;
        }

        .user-profile-avatar::after {
            content: '';
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: var(--color-success);
            border: 2px solid var(--color-canvas-default);
            border-radius: var(--radius-full);
        }

        .user-profile-info h3 {
            margin: 0;
            font-size: var(--font-size-2);
            font-weight: 600;
            color: var(--color-fg-default);
        }

        .user-profile-info p {
            margin: var(--space-4) 0 0;
            font-size: var(--font-size-1);
            color: var(--color-fg-muted);
        }

        .user-status {
            display: inline-flex;
            align-items: center;
            gap: var(--space-4);
            padding: var(--space-4) var(--space-8);
            background: var(--color-success-subtle);
            color: var(--color-success);
            border-radius: var(--radius-small);
            font-size: var(--font-size-0);
            font-weight: 500;
            margin-top: var(--space-8);
        }

        /* Navigation */
        .sidebar-nav {
            padding: var(--space-16);
        }

        .nav-section {
            margin-bottom: var(--space-24);
        }

        .nav-section:last-child {
            margin-bottom: 0;
        }

        .nav-section-title {
            font-size: var(--font-size-0);
            font-weight: 600;
            color: var(--color-fg-muted);
            text-transform: uppercase;
            letter-spacing: 0.025em;
            margin: 0 0 var(--space-12);
            padding: 0 var(--space-8);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: var(--space-12);
            padding: var(--space-8) var(--space-8);
            color: var(--color-fg-default);
            text-decoration: none;
            border-radius: var(--radius-small);
            font-size: var(--font-size-1);
            font-weight: 500;
            transition: all 0.15s ease;
            margin-bottom: var(--space-4);
        }

        .nav-item:hover {
            background: var(--color-canvas-inset);
            color: var(--color-accent);
        }

        .nav-item.active {
            background: var(--color-accent-subtle);
            color: var(--color-accent-emphasis);
        }

        .nav-item i {
            width: 16px;
            height: 16px;
            color: inherit;
            opacity: 0.8;
        }

        .nav-item.danger {
            color: var(--color-danger);
        }

        .nav-item.danger:hover {
            background: var(--color-danger-subtle);
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: var(--space-16) var(--space-24);
            border-top: 1px solid var(--color-border-muted);
            background: var(--color-canvas-inset);
        }

        .account-id {
            font-size: var(--font-size-0);
            color: var(--color-fg-muted);
            margin-bottom: var(--space-8);
        }

        .account-id strong {
            color: var(--color-fg-default);
            font-weight: 600;
        }

        .sidebar-footer-links {
            display: flex;
            gap: var(--space-16);
            font-size: var(--font-size-0);
        }

        .sidebar-footer-links a {
            color: var(--color-fg-muted);
            text-decoration: none;
        }

        .sidebar-footer-links a:hover {
            color: var(--color-accent);
        }

        /* Main content */
        .main-content {
            background: var(--color-canvas-default);
            border: 1px solid var(--color-border-default);
            border-radius: var(--radius-medium);
            overflow: hidden;
            min-height: 600px;
        }

        /* Alert components */
        .alert {
            padding: var(--space-16);
            border-radius: var(--radius-small);
            border: 1px solid;
            margin-bottom: var(--space-24);
            display: flex;
            align-items: flex-start;
            gap: var(--space-12);
        }

        .alert-success {
            background: var(--color-success-subtle);
            border-color: var(--color-success);
            color: var(--color-success);
        }

        .alert-danger {
            background: var(--color-danger-subtle);
            border-color: var(--color-danger);
            color: var(--color-danger);
        }

        .alert-icon {
            flex-shrink: 0;
            margin-top: var(--space-2);
        }

        .alert-content h4 {
            margin: 0 0 var(--space-4);
            font-size: var(--font-size-1);
            font-weight: 600;
        }

        .alert-content p {
            margin: 0;
            font-size: var(--font-size-1);
        }

        .alert-content ul {
            margin: var(--space-8) 0 0;
            padding-left: var(--space-16);
        }

        /* Content padding */
        .content-padding {
            padding: var(--space-32);
        }

        /* Fade animations */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(-8px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .header-title {
                display: none;
            }

            .app-layout {
                grid-template-columns: 1fr;
                gap: var(--space-24);
                padding: var(--space-24) var(--space-16);
            }

            .sidebar {
                position: static;
                order: 2;
            }

            .main-content {
                order: 1;
            }

            .user-profile {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--space-12);
            }

            .nav-section-title {
                font-size: var(--font-size-1);
            }

            .nav-item {
                padding: var(--space-12);
                font-size: var(--font-size-2);
            }
        }

        /* Mobile sidebar overlay */
        .mobile-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(31, 35, 40, 0.5);
            z-index: 40;
            display: none;
        }

        .mobile-sidebar-overlay.active {
            display: block;
        }

        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 320px;
            height: 100%;
            background: var(--color-canvas-default);
            z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .mobile-sidebar.active {
            transform: translateX(0);
        }

        .mobile-sidebar-header {
            padding: var(--space-16);
            border-bottom: 1px solid var(--color-border-default);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mobile-sidebar-close {
            background: none;
            border: none;
            padding: var(--space-8);
            color: var(--color-fg-muted);
            cursor: pointer;
            border-radius: var(--radius-small);
        }

        .mobile-sidebar-close:hover {
            background: var(--color-canvas-inset);
        }

        @media (min-width: 769px) {
            .mobile-sidebar-overlay,
            .mobile-sidebar {
                display: none !important;
            }
        }
    </style>

    <!-- External resources -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <!-- Loading screen -->
    <div id="loading-screen">
        <div id="loading-spinner"></div>
    </div>

    <div class="content-container">
        <!-- App Header -->
        <header class="app-header">
            <div class="header-content">
                <div style="display: flex; align-items: center; gap: var(--space-16);">
                    <button class="mobile-menu-toggle" id="mobile-menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a href="/" class="header-brand">
                        <img src="/img/dark.png" alt="Numblio" class="header-logo">
                        <h1 class="header-title">Account Settings</h1>
                    </a>
                </div>
                <div class="header-user">
                    <img class="user-avatar" 
                         src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=64&d=mp&r=pg"
                         alt="{{ $user->first_name }} {{ $user->last_name }}">
                </div>
            </div>
        </header>

        <!-- Main Layout -->
        <div class="app-layout">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-card">
                    <!-- User Profile Header -->
                    <div class="sidebar-header">
                        <div class="user-profile">
                            <img class="user-profile-avatar" 
                                 src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=96&d=mp&r=pg"
                                 alt="{{ $user->first_name }} {{ $user->last_name }}">
                            <div class="user-profile-info">
                                <h3>{{ $user->first_name }} {{ $user->last_name }}</h3>
                                <p>{{ $user->email }}</p>
                                <span class="user-status">
                                    <i class="fas fa-check-circle"></i>
                                    {{ lang('status.verified', [], 'layout') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="sidebar-nav">
                        <div class="nav-section">
                            <h4 class="nav-section-title">{{ lang('sidebar.account_settings', [], 'layout') }}</h4>
                            <a href="{{ route('account.profile') }}" class="nav-item {{ $active_page == 'profile' ? 'active' : '' }}">
                                <i class="fas fa-user"></i>
                                {{ lang('sidebar.profile', [], 'layout') }}
                            </a>
                            <a href="{{ route('account.password') }}" class="nav-item {{ $active_page == 'password' ? 'active' : '' }}">
                                <i class="fas fa-lock"></i>
                                {{ lang('sidebar.password', [], 'layout') }}
                            </a>
                            <a href="{{ route('account.security') }}" class="nav-item {{ $active_page == 'security' ? 'active' : '' }}">
                                <i class="fas fa-shield-alt"></i>
                                {{ lang('sidebar.security', [], 'layout') }}
                            </a>
                            <a href="{{ route('account.connections') }}" class="nav-item {{ $active_page == 'connections' ? 'active' : '' }}">
                                <i class="fas fa-globe"></i>
                                {{ lang('sidebar.connections', [], 'layout') }}
                            </a>
                            <a href="{{ route('account.subscriptions') }}" class="nav-item {{ $active_page == 'subscriptions' ? 'active' : '' }}">
                                <i class="fas fa-credit-card"></i>
                                {{ lang('sidebar.subscriptions', [], 'layout') }}
                            </a>
                        </div>

                        <div class="nav-section">
                            <h4 class="nav-section-title">{{ lang('sidebar.support', [], 'layout') }}</h4>
                            <a href="https://support.numblio.com" class="nav-item">
                                <i class="fas fa-question-circle"></i>
                                {{ lang('sidebar.help_center', [], 'layout') }}
                            </a>
                            <a href="/faq" class="nav-item">
                                <i class="fas fa-info-circle"></i>
                                {{ lang('sidebar.faq', [], 'layout') }}
                            </a>
                        </div>

                        <div class="nav-section">
                            <a href="{{ route('logout.simple') }}" class="nav-item danger">
                                <i class="fas fa-sign-out-alt"></i>
                                {{ lang('sidebar.logout', [], 'layout') }}
                            </a>
                        </div>
                    </nav>

                    <!-- Sidebar Footer -->
                    <div class="sidebar-footer">
                        <div class="account-id">
                            <strong>{{ lang('sidebar.account_id', [], 'layout') }}:</strong> {{ substr(md5($user->id), 0, 8) }}
                        </div>
                        <div class="sidebar-footer-links">
                            <a href="https://numblio.com/documents/privacy">{{ lang('footer.privacy', [], 'layout') }}</a>
                            <a href="https://numblio.com/documents/terms">{{ lang('footer.terms', [], 'layout') }}</a>
                            <a href="https://support.numblio.com">{{ lang('footer.help', [], 'layout') }}</a>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Success Message -->
                @if (session('status'))
                <div class="content-padding">
                    <div class="alert alert-success fade-in">
                        <i class="fas fa-check-circle alert-icon"></i>
                        <div class="alert-content">
                            <h4>{{ lang('alerts.success', [], 'layout') }}</h4>
                            <p>{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Error Message -->
                @if ($errors->any())
                <div class="content-padding">
                    <div class="alert alert-danger fade-in">
                        <i class="fas fa-exclamation-circle alert-icon"></i>
                        <div class="alert-content">
                            <h4>{{ lang('alerts.error', [], 'layout') }}</h4>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Page Content -->
                <div class="content-padding">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar-overlay" id="mobile-sidebar-overlay"></div>
    <div class="mobile-sidebar" id="mobile-sidebar">
        <div class="mobile-sidebar-header">
            <div class="header-brand">
                <img src="/img/dark.png" alt="Numblio" class="header-logo">
                <span style="font-weight: 600; margin-left: var(--space-8);">Account</span>
            </div>
            <button class="mobile-sidebar-close" id="mobile-sidebar-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-header">
            <div class="user-profile">
                <img class="user-profile-avatar" 
                     src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=96&d=mp&r=pg"
                     alt="{{ $user->first_name }} {{ $user->last_name }}">
                <div class="user-profile-info">
                    <h3>{{ $user->first_name }} {{ $user->last_name }}</h3>
                    <p>{{ $user->email }}</p>
                    <span class="user-status">
                        <i class="fas fa-check-circle"></i>
                        {{ lang('status.verified', [], 'layout') }}
                    </span>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <h4 class="nav-section-title">{{ lang('sidebar.account_settings', [], 'layout') }}</h4>
                <a href="{{ route('account.profile') }}" class="nav-item {{ $active_page == 'profile' ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    {{ lang('sidebar.profile', [], 'layout') }}
                </a>
                <a href="{{ route('account.password') }}" class="nav-item {{ $active_page == 'password' ? 'active' : '' }}">
                    <i class="fas fa-lock"></i>
                    {{ lang('sidebar.password', [], 'layout') }}
                </a>
                <a href="{{ route('account.security') }}" class="nav-item {{ $active_page == 'security' ? 'active' : '' }}">
                    <i class="fas fa-shield-alt"></i>
                    {{ lang('sidebar.security', [], 'layout') }}
                </a>
                <a href="{{ route('account.connections') }}" class="nav-item {{ $active_page == 'connections' ? 'active' : '' }}">
                    <i class="fas fa-globe"></i>
                    {{ lang('sidebar.connections', [], 'layout') }}
                </a>
                <a href="{{ route('account.subscriptions') }}" class="nav-item {{ $active_page == 'subscriptions' ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    {{ lang('sidebar.subscriptions', [], 'layout') }}
                </a>
            </div>

            <div class="nav-section">
                <h4 class="nav-section-title">{{ lang('sidebar.support', [], 'layout') }}</h4>
                <a href="https://support.numblio.com" class="nav-item">
                    <i class="fas fa-question-circle"></i>
                    {{ lang('sidebar.help_center', [], 'layout') }}
                </a>
                <a href="/faq" class="nav-item">
                    <i class="fas fa-info-circle"></i>
                    {{ lang('sidebar.faq', [], 'layout') }}
                </a>
            </div>

            <div class="nav-section">
                <a href="{{ route('logout.simple') }}" class="nav-item danger">
                    <i class="fas fa-sign-out-alt"></i>
                    {{ lang('sidebar.logout', [], 'layout') }}
                </a>
            </div>
        </nav>
    </div>

    <script>
        // Loading screen handling
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.body.classList.add('loaded');
            }, 150);
        });

        // Fallback for slow connections
        setTimeout(function() {
            document.body.classList.add('loaded');
        }, 2000);

        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
            const mobileSidebarClose = document.getElementById('mobile-sidebar-close');

            function openMobileSidebar() {
                mobileSidebar.classList.add('active');
                mobileSidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileSidebar() {
                mobileSidebar.classList.remove('active');
                mobileSidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', openMobileSidebar);
            }

            if (mobileSidebarClose) {
                mobileSidebarClose.addEventListener('click', closeMobileSidebar);
            }

            if (mobileSidebarOverlay) {
                mobileSidebarOverlay.addEventListener('click', closeMobileSidebar);
            }

            // Close mobile sidebar when clicking nav links
            const mobileNavLinks = document.querySelectorAll('.mobile-sidebar .nav-item');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', closeMobileSidebar);
            });

            // Automatically hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert.fade-in');
            if (alerts.length > 0) {
                setTimeout(() => {
                    alerts.forEach(alert => {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-8px)';
                        setTimeout(() => {
                            alert.style.display = 'none';
                        }, 300);
                    });
                }, 5000);
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
        });
    </script>
    @yield('scripts')
</body>
</html>
