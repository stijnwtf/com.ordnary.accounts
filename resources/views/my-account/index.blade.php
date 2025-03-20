<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Using reCAPTCHA v2 Invisible instead of v3 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .tab-content {
            min-height: 380px; /* Zorgt voor consistente hoogte tussen tabbladen */
        }
    </style>
</head>
<body class="bg-[#f9fafb] min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow py-12">
        <!-- Main Content Section -->
        <div class="w-full lg:w-2/3 flex items-center justify-center p-4 md:p-8">
            <div class="w-full max-w-3xl">
                <div class="mb-8 flex flex-col items-start">
                    <div class="flex items-center mb-3">
                        <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto mr-3">
                        <span class="text-sm text-gray-500 border-l border-gray-300 pl-3">My Account</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->name }}</h2>
                        <p class="text-sm text-gray-600 mt-2">Manage your personal information and security settings for your Numblio-account.</p>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg animate-fade-in-down">
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
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
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

                <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
                    <!-- Tabs -->
                    <div class="flex border-b">
                        <button id="profile-tab" class="px-6 py-4 text-blue-600 border-b-2 border-blue-600 font-medium text-sm flex items-center">
                            <i class="fas fa-user mr-2"></i> Profile
                        </button>
                        <button id="password-tab" class="px-6 py-4 text-gray-500 font-medium text-sm flex items-center">
                            <i class="fas fa-lock mr-2"></i> Password
                        </button>
                    </div>

                    <!-- Profile Form -->
                    <div id="profile-content" class="p-6 tab-content">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Personal information</h3>
                            <p class="text-sm text-gray-600">Update your personal information to personalize your Numblio experience.</p>
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

                            <!-- Account Info -->
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500">
                                    <span class="font-medium">Account created at:</span> {{ $user->created_at->format('d-m-Y') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span class="font-medium">Last update:</span> {{ $user->updated_at->format('d-m-Y') }}
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
                    <div id="password-content" class="p-6 hidden tab-content">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Change your password</h3>
                            <p class="text-sm text-gray-600">Make sure you have a strong password that you don't use on other websites.</p>
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
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-between">
                    <a href="{{ route('logout') }}" class="text-sm text-red-600 hover:text-red-700 flex items-center">
                        <i class="fas fa-sign-out-alt mr-1"></i> Log out
                    </a>
                    <a href="/" class="text-sm text-blue-600 hover:text-blue-700 flex items-center">
                        <i class="fas fa-home mr-1"></i> Back to home
                    </a>
                </div>

                <!-- Footer -->
                <footer class="mt-8 text-xs text-gray-400">
                    <div class="flex flex-col space-y-1">
                        <span>&copy; {{ date('Y') }} Numblio. All rights reserved. Numblio and all associated services are protected by copyright and intellectual property laws.</span>
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

    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileTab = document.getElementById('profile-tab');
            const passwordTab = document.getElementById('password-tab');
            const profileContent = document.getElementById('profile-content');
            const passwordContent = document.getElementById('password-content');

            profileTab.addEventListener('click', function() {
                // Activate profile tab
                profileTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                profileTab.classList.remove('text-gray-500');

                // Deactivate password tab
                passwordTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                passwordTab.classList.add('text-gray-500');

                // Show profile content, hide password content
                profileContent.classList.remove('hidden');
                passwordContent.classList.add('hidden');
            });

            passwordTab.addEventListener('click', function() {
                // Activate password tab
                passwordTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                passwordTab.classList.remove('text-gray-500');

                // Deactivate profile tab
                profileTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                profileTab.classList.add('text-gray-500');

                // Show password content, hide profile content
                passwordContent.classList.remove('hidden');
                profileContent.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
