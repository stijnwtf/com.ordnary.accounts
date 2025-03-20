<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage your Numblio account settings">
    <title>My Account | Numblio</title>
    <!-- Deferred loading for better performance -->
    <script src="https://cdn.tailwindcss.com" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Using reCAPTCHA v2 Invisible instead of v3 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .tab-content {
            min-height: 380px; /* Zorgt voor consistente hoogte tussen tabbladen */
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
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        /* Lazy load images */
        img.lazy {
            opacity: 0;
            transition: opacity 0.3s;
        }
        img.lazy.loaded {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-[#f9fafb] min-h-screen py-8 px-4 md:py-12">
    <div class="max-w-7xl mx-auto">
        <!-- Main container with two cards side by side -->
        <div class="flex flex-col lg:flex-row gap-6 items-start">
            <!-- Left Sidebar Card -->
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden sticky top-8">
                    <!-- Account Summary -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center mb-4">
                            <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto mr-3">
                        </div>
                        <div class="flex items-center space-x-3 mb-4">
                            <img class="h-12 w-12 rounded-full object-cover border-2 border-blue-100"
                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=3b82f6&background=dbeafe"
                                 alt="{{ $user->name }}">
                            <div>
                                <h2 class="text-sm font-semibold text-gray-900">{{ $user->name }}</h2>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Verified
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                            <a href="#profile" id="sidebar-profile" class="sidebar-item active text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-user mr-3 text-gray-500"></i>
                                Profile Information
                            </a>
                            <a href="#password" id="sidebar-password" class="sidebar-item text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-lock mr-3 text-gray-500"></i>
                                Password
                            </a>
                            <a href="#security" id="sidebar-security" class="sidebar-item text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-shield-alt mr-3 text-gray-500"></i>
                                Security
                            </a>
                            <a href="#notifications" id="sidebar-notifications" class="sidebar-item text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-bell mr-3 text-gray-500"></i>
                                Notifications
                            </a>
                            <a href="#connected" id="sidebar-connected" class="sidebar-item text-gray-600 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-link mr-3 text-gray-500"></i>
                                Connected Services
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
                        <p class="text-xs text-gray-500">
                            <span class="font-medium">Account ID:</span>
                            <span class="text-gray-400">{{ substr(md5($user->id), 0, 8) }}</span>
                        </p>
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
                        <h2 class="text-xl font-bold text-gray-900">Welcome back, {{ $user->name }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage your personal information and security settings for your Numblio-account.</p>
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

                    <!-- Content Sections -->
                    <div class="px-6 pt-6">
                        <!-- Hidden section tabs for mobile -->
                        <div class="lg:hidden flex flex-wrap border-b border-gray-200 mb-6">
                            <button id="profile-tab" class="px-4 py-2 text-blue-600 border-b-2 border-blue-600 font-medium text-sm flex items-center">
                                <i class="fas fa-user mr-2"></i> Profile
                            </button>
                            <button id="password-tab" class="px-4 py-2 text-gray-500 font-medium text-sm flex items-center">
                                <i class="fas fa-lock mr-2"></i> Password
                            </button>
                            <button id="security-tab" class="px-4 py-2 text-gray-500 font-medium text-sm flex items-center">
                                <i class="fas fa-shield-alt mr-2"></i> Security
                            </button>
                        </div>

                        <!-- Tab Content Sections -->
                        <div id="profile-content" class="tab-content">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Personal information</h3>
                                <p class="text-sm text-gray-600">Update your personal information to personalize your Numblio experience.</p>
                            </div>

                            <!-- Profile Overview Card -->
                            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="flex items-center">
                                    <div class="mr-4">
                                        <img class="h-16 w-16 rounded-full object-cover border-2 border-white shadow"
                                             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=3b82f6&background=dbeafe&size=128"
                                             alt="{{ $user->name }}">
                                    </div>
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-900">{{ $user->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                        <div class="mt-2 flex space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Verified
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-user-shield mr-1"></i> Active
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('my-account.profile') }}" class="space-y-6">
                                @csrf

                                <!-- Name Input -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                               class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">
                                    </div>
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Your full name as displayed in Numblio-services.</p>
                                </div>

                                <!-- Email Input -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                               class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
                                    </div>
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">This email address is used for login and communication.</p>
                                </div>

                                <!-- Additional Fields -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-gray-400">(Optional)</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                               class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}">
                                    </div>
                                    @error('phone')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Used for account recovery and important notifications.</p>
                                </div>

                                <div>
                                    <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company <span class="text-gray-400">(Optional)</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-building text-gray-400"></i>
                                        </div>
                                        <input type="text" name="company" id="company" value="{{ old('company', $user->company ?? '') }}"
                                               class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('company') ? 'border-red-500' : 'border-gray-300' }}">
                                    </div>
                                    @error('company')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Your organization name for business-related services.</p>
                                </div>

                                <!-- Language Preference -->
                                <div>
                                    <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language Preference</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-globe text-gray-400"></i>
                                        </div>
                                        <select name="language" id="language"
                                                class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('language') ? 'border-red-500' : 'border-gray-300' }}">
                                            <option value="nl" {{ (old('language', $user->language ?? 'nl') == 'nl') ? 'selected' : '' }}>Nederlands</option>
                                            <option value="en" {{ (old('language', $user->language ?? 'nl') == 'en') ? 'selected' : '' }}>English</option>
                                            <option value="de" {{ (old('language', $user->language ?? 'nl') == 'de') ? 'selected' : '' }}>Deutsch</option>
                                            <option value="fr" {{ (old('language', $user->language ?? 'nl') == 'fr') ? 'selected' : '' }}>Français</option>
                                        </select>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Choose your preferred language for the platform.</p>
                                </div>

                                <!-- Account Info -->
                                <div class="pt-4 border-t border-gray-200 space-y-2">
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Account created at:</span> {{ $user->created_at->format('d-m-Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Last update:</span> {{ $user->updated_at->format('d-m-Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Last login:</span> {{ now()->subDays(rand(0, 5))->format('d-m-Y H:i') }}
                                        <span class="text-xs text-gray-400">(from {{ request()->ip() }})</span>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Account type:</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-star mr-1"></i> Standard
                                        </span>
                                    </p>
                                </div>

                                <!-- Submit Button -->
                                <div>
                                    <button
                                        type="submit"
                                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                                        <i class="fas fa-save mr-2"></i> Confirm changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Password Form -->
                        <div id="password-content" class="tab-content hidden">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Change your password</h3>
                                <p class="text-sm text-gray-600">Make sure you have a strong password that you don't use on other websites.</p>
                            </div>

                            <!-- Password Strength Meter -->
                            <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 mr-3">
                                        <i class="fas fa-shield-alt text-yellow-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800">Password security check</h4>
                                        <p class="text-xs text-yellow-700 mt-1">Your password was last changed {{ now()->subMonths(rand(2, 6))->format('d-m-Y') }}</p>
                                        <div class="mt-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 70%"></div>
                                            </div>
                                            <p class="text-xs text-yellow-700 mt-1">Your password strength is <span class="font-medium">good</span>.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('my-account.password') }}" class="space-y-6">
                                @csrf

                                <!-- Current Password Input -->
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input type="password" name="current_password" id="current_password" required
                                               class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300' }}">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('current_password')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Enter your current password for verification.</p>
                                </div>

                                <!-- New Password Input -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input type="password" name="password" id="password" required
                                               class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Use at least 8 characters with letters, numbers and symbols.</p>
                                </div>

                                <!-- Confirm Password Input -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required
                                               class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Enter your new password again to confirm.</p>
                                </div>

                                <!-- Password Security Tips -->
                                <div class="p-4 bg-blue-50 rounded-lg mt-4">
                                    <h4 class="text-sm font-medium text-blue-800 mb-2">Tips for a strong password:</h4>
                                    <ul class="text-xs text-blue-700 list-disc pl-4 space-y-1">
                                        <li>Use at least 8 characters</li>
                                        <li>Combine upper and lower case letters</li>
                                        <li>Include numbers and symbols (e.g. !@#$%)</li>
                                        <li>Avoid easily guessable information such as your name or date of birth</li>
                                        <li>Use a unique password for each website</li>
                                    </ul>
                                </div>

                                <!-- Submit Button -->
                                <div>
                                    <button
                                        type="submit"
                                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                                        <i class="fas fa-key mr-2"></i> Confirm changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Security Section -->
                        <div id="security-content" class="tab-content hidden">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Account security</h3>
                                <p class="text-sm text-gray-600">Enhance the security of your Numblio account with additional protection measures.</p>
                            </div>

                            <!-- Security Overview Card -->
                            <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-100">
                                <h4 class="text-sm font-medium text-green-800 mb-3">Security status</h4>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mr-3">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-700">Password protection</p>
                                            <p class="text-xs text-gray-500">Your account is protected with a strong password</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-times text-red-500 text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-700">Two-factor authentication</p>
                                            <p class="text-xs text-gray-500">Add an extra layer of security by enabling 2FA</p>
                                        </div>
                                        <button class="px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 focus:outline-none">
                                            Enable
                                        </button>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-exclamation text-yellow-600 text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-700">Recovery email</p>
                                            <p class="text-xs text-gray-500">Set up a recovery email for account access</p>
                                        </div>
                                        <button class="px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 focus:outline-none">
                                            Setup
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Two-Factor Authentication Card -->
                            <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                                <div class="p-4 bg-gray-50 border-b border-gray-200">
                                    <h4 class="text-base font-medium text-gray-900 flex items-center">
                                        <i class="fas fa-shield-alt mr-2 text-blue-500"></i>
                                        Two-Factor Authentication (2FA)
                                    </h4>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-600 mb-4">Add an extra layer of security to your account by requiring both your password and a verification code from your mobile phone to log in.</p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i> Not enabled
                                            </span>
                                        </div>
                                        <button class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <i class="fas fa-plus-circle mr-1"></i> Enable 2FA
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Login History Card -->
                            <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                                <div class="p-4 bg-gray-50 border-b border-gray-200">
                                    <h4 class="text-base font-medium text-gray-900 flex items-center">
                                        <i class="fas fa-history mr-2 text-blue-500"></i>
                                        Recent login activity
                                    </h4>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-600 mb-4">Monitor your recent login activity to ensure your account hasn't been compromised.</p>
                                    <div class="overflow-hidden rounded-lg border border-gray-200">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ now()->format('d-m-Y H:i') }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-laptop mr-1 text-gray-400"></i> Chrome on macOS
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">Amsterdam, NL</td>
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i> Current
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ now()->subDays(2)->format('d-m-Y H:i') }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-mobile-alt mr-1 text-gray-400"></i> Mobile App
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">Amsterdam, NL</td>
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <i class="fas fa-check-circle mr-1"></i> Success
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ now()->subDays(5)->format('d-m-Y H:i') }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-laptop mr-1 text-gray-400"></i> Chrome on Windows
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">Rotterdam, NL</td>
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <i class="fas fa-check-circle mr-1"></i> Success
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        <button class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                                            <i class="fas fa-list mr-1"></i> View all login activity
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Recovery -->
                            <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                                <div class="p-4 bg-gray-50 border-b border-gray-200">
                                    <h4 class="text-base font-medium text-gray-900 flex items-center">
                                        <i class="fas fa-life-ring mr-2 text-blue-500"></i>
                                        Account Recovery Options
                                    </h4>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-600 mb-4">Set up recovery options to help you regain access to your account if you lose your password.</p>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-900">Recovery Email</h5>
                                                <p class="text-xs text-gray-500">A backup email address for account recovery</p>
                                            </div>
                                            <button class="px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 focus:outline-none">
                                                <i class="fas fa-plus-circle mr-1"></i> Add
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-900">Recovery Phone</h5>
                                                <p class="text-xs text-gray-500">A phone number for account recovery via SMS</p>
                                            </div>
                                            <button class="px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 focus:outline-none">
                                                <i class="fas fa-plus-circle mr-1"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications Section -->
                        <div id="notifications-content" class="tab-content hidden">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Notifications</h3>
                                <p class="text-sm text-gray-600">Manage your notification preferences and settings.</p>
                            </div>

                            <!-- Notifications Overview Card -->
                            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="flex items-center">
                                    <div class="mr-4">
                                        <i class="fas fa-bell text-blue-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-900">Notification settings</h4>
                                        <p class="text-sm text-gray-600">Customize your notification preferences to receive relevant updates and alerts.</p>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="#" class="space-y-6">
                                @csrf

                                <!-- Email Notifications -->
                                <div>
                                    <label for="email_notifications" class="block text-sm font-medium text-gray-700 mb-1">Email Notifications</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <select name="email_notifications" id="email_notifications"
                                                class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('email_notifications') ? 'border-red-500' : 'border-gray-300' }}">
                                            <option value="daily">Daily summary</option>
                                            <option value="weekly">Weekly summary</option>
                                            <option value="none">No email notifications</option>
                                        </select>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Choose the frequency of email notifications.</p>
                                </div>

                                <!-- Push Notifications -->
                                <div>
                                    <label for="push_notifications" class="block text-sm font-medium text-gray-700 mb-1">Push Notifications</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-mobile-alt text-gray-400"></i>
                                        </div>
                                        <select name="push_notifications" id="push_notifications"
                                                class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('push_notifications') ? 'border-red-500' : 'border-gray-300' }}">
                                            <option value="all">All notifications</option>
                                            <option value="important">Important notifications only</option>
                                            <option value="none">No push notifications</option>
                                        </select>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Choose the types of notifications you want to receive via push notifications.</p>
                                </div>

                                <!-- Submit Button -->
                                <div>
                                    <button
                                        type="submit"
                                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                                        <i class="fas fa-save mr-2"></i> Confirm changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Connected Services Section -->
                        <div id="connected-content" class="tab-content hidden">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Connected services</h3>
                                <p class="text-sm text-gray-600">Manage the services and applications connected to your Numblio account.</p>
                            </div>

                            <!-- Connected Services Overview -->
                            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="flex items-center">
                                    <div class="mr-4">
                                        <i class="fas fa-link text-blue-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-900">Connected applications</h4>
                                        <p class="text-sm text-gray-600">View and manage third-party applications that have access to your Numblio account.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Connected Apps List -->
                            <div class="space-y-4">
                                <!-- Google -->
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <img src="https://cdn.cdnlogo.com/logos/g/35/google-icon.svg" alt="Google" class="h-8 w-8 mr-3">
                                                <div>
                                                    <h5 class="text-sm font-medium text-gray-900">Google</h5>
                                                    <p class="text-xs text-gray-500">Connected on {{ now()->subMonths(rand(1, 6))->format('d-m-Y') }}</p>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Connected
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex items-center justify-between">
                                            <div class="text-xs text-gray-500">
                                                <p>This connection allows you to log in using your Google account.</p>
                                            </div>
                                            <button class="px-3 py-1 rounded text-xs font-medium text-red-600 hover:text-red-800 focus:outline-none border border-red-200 hover:bg-red-50">
                                                <i class="fas fa-unlink mr-1"></i> Disconnect
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Microsoft -->
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <img src="https://cdn.cdnlogo.com/logos/m/44/microsoft.svg" alt="Microsoft" class="h-8 w-8 mr-3">
                                                <div>
                                                    <h5 class="text-sm font-medium text-gray-900">Microsoft</h5>
                                                    <p class="text-xs text-gray-500">Not connected</p>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Not connected
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex items-center justify-between">
                                            <div class="text-xs text-gray-500">
                                                <p>Connect to sign in using your Microsoft account and sync calendars.</p>
                                            </div>
                                            <button class="px-3 py-1 rounded text-xs font-medium text-blue-600 hover:text-blue-800 focus:outline-none border border-blue-200 hover:bg-blue-50">
                                                <i class="fas fa-link mr-1"></i> Connect
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Apple -->
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <img src="https://cdn.cdnlogo.com/logos/a/92/apple.svg" alt="Apple" class="h-8 w-8 mr-3">
                                                <div>
                                                    <h5 class="text-sm font-medium text-gray-900">Apple</h5>
                                                    <p class="text-xs text-gray-500">Not connected</p>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Not connected
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex items-center justify-between">
                                            <div class="text-xs text-gray-500">
                                                <p>Connect to sign in with your Apple ID and integrate with Apple services.</p>
                                            </div>
                                            <button class="px-3 py-1 rounded text-xs font-medium text-blue-600 hover:text-blue-800 focus:outline-none border border-blue-200 hover:bg-blue-50">
                                                <i class="fas fa-link mr-1"></i> Connect
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add More Connections -->
                            <div class="mt-6">
                                <button class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                                    <i class="fas fa-plus-circle mr-2"></i> Connect more services
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-6 border-t border-gray-200 mt-8">
                        <footer class="text-xs text-gray-400">
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
                <div class="flex-shrink-0 flex items-center px-4">
                    <img class="h-8 w-auto" src="/img/dark.png" alt="Numblio Logo">
                </div>
                <nav class="mt-5 px-2 space-y-1">
                    <a href="#profile" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-user mr-4 text-gray-500"></i>
                        Profile
                    </a>
                    <a href="#password" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-lock mr-4 text-gray-500"></i>
                        Password
                    </a>
                    <a href="#security" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-shield-alt mr-4 text-gray-500"></i>
                        Security
                    </a>
                    <a href="#notifications" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-bell mr-4 text-gray-500"></i>
                        Notifications
                    </a>
                    <a href="#connected" class="mobile-nav text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <i class="fas fa-link mr-4 text-gray-500"></i>
                        Connected Services
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality with lazy loading for better performance
        document.addEventListener('DOMContentLoaded', function() {
            // Tab elements
            const profileTab = document.getElementById('profile-tab');
            const passwordTab = document.getElementById('password-tab');
            const securityTab = document.getElementById('security-tab');
            
            // Content sections
            const profileContent = document.getElementById('profile-content');
            const passwordContent = document.getElementById('password-content');
            const securityContent = document.getElementById('security-content');
            const notificationsContent = document.getElementById('notifications-content');
            const connectedContent = document.getElementById('connected-content');
            
            // Sidebar links
            const sidebarProfile = document.getElementById('sidebar-profile');
            const sidebarPassword = document.getElementById('sidebar-password');
            const sidebarSecurity = document.getElementById('sidebar-security');
            const sidebarNotifications = document.getElementById('sidebar-notifications');
            const sidebarConnected = document.getElementById('sidebar-connected');
            
            // Mobile elements
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            const mobileNavLinks = document.querySelectorAll('.mobile-nav');
            
            // Function to activate a tab
            function activateTab(content, sidebarItem) {
                // Hide all content sections
                [profileContent, passwordContent, securityContent, notificationsContent, connectedContent].forEach(c => {
                    c.classList.add('hidden');
                });
                
                // Show the selected content
                content.classList.remove('hidden');
                
                // Update mobile tabs if they exist
                if (profileTab) {
                    [profileTab, passwordTab, securityTab].forEach(t => {
                        t.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                        t.classList.add('text-gray-500');
                    });
                    
                    if (content === profileContent && profileTab) {
                        profileTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                        profileTab.classList.remove('text-gray-500');
                    } else if (content === passwordContent && passwordTab) {
                        passwordTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                        passwordTab.classList.remove('text-gray-500');
                    } else if (content === securityContent && securityTab) {
                        securityTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                        securityTab.classList.remove('text-gray-500');
                    }
                }
                
                // Update sidebar active state
                document.querySelectorAll('.sidebar-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                if (sidebarItem) {
                    sidebarItem.classList.add('active');
                }
                
                // Close mobile sidebar if open
                mobileSidebar.classList.add('hidden');
            }
            
            // Sidebar click events
            sidebarProfile.addEventListener('click', function(e) {
                e.preventDefault();
                activateTab(profileContent, sidebarProfile);
            });
            
            sidebarPassword.addEventListener('click', function(e) {
                e.preventDefault();
                activateTab(passwordContent, sidebarPassword);
            });
            
            sidebarSecurity.addEventListener('click', function(e) {
                e.preventDefault();
                activateTab(securityContent, sidebarSecurity);
            });
            
            sidebarNotifications.addEventListener('click', function(e) {
                e.preventDefault();
                activateTab(notificationsContent, sidebarNotifications);
            });
            
            sidebarConnected.addEventListener('click', function(e) {
                e.preventDefault();
                activateTab(connectedContent, sidebarConnected);
            });
            
            // Mobile tab events
            if (profileTab) {
                profileTab.addEventListener('click', function() {
                    activateTab(profileContent, sidebarProfile);
                });
                
                passwordTab.addEventListener('click', function() {
                    activateTab(passwordContent, sidebarPassword);
                });
                
                securityTab.addEventListener('click', function() {
                    activateTab(securityContent, sidebarSecurity);
                });
            }
            
            // Mobile menu functionality
            mobileMenuButton.addEventListener('click', function() {
                mobileSidebar.classList.remove('hidden');
            });
            
            closeSidebar.addEventListener('click', function() {
                mobileSidebar.classList.add('hidden');
            });
            
            // Mobile navigation links
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href').substring(1);
                    
                    switch(href) {
                        case 'profile':
                            activateTab(profileContent, sidebarProfile);
                            break;
                        case 'password':
                            activateTab(passwordContent, sidebarPassword);
                            break;
                        case 'security':
                            activateTab(securityContent, sidebarSecurity);
                            break;
                        case 'notifications':
                            activateTab(notificationsContent, sidebarNotifications);
                            break;
                        case 'connected':
                            activateTab(connectedContent, sidebarConnected);
                            break;
                    }
                });
            });
            
            // Password visibility toggle
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
            if ('IntersectionObserver' in window) {
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
            } else {
                // Fallback for browsers without IntersectionObserver
                lazyImages.forEach(image => {
                    image.src = image.dataset.src;
                    image.classList.add('loaded');
                });
            }
        });
    </script>
</body>
</html>
