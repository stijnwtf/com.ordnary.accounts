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
</head>
<body class="bg-[#f9fafb] min-h-screen flex flex-col">
<div class="flex items-center justify-center flex-grow">
    <!-- Main Content Section -->
    <div class="w-full lg:w-2/3 flex items-center justify-center p-8">
        <div class="w-full max-w-3xl">
            <div class="mb-8 flex flex-col items-start">
                <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto mb-3">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">My Numblio-account.</h2>
                    <p class="text-sm text-gray-600 mt-2">You can view your personal account details below, including your profile information, and account settings.</p>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Succes!</h3>
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

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Tabs -->
                <div class="flex border-b">
                    <button id="profile-tab" class="px-6 py-3 text-blue-600 border-b-2 border-blue-600 font-medium text-sm">
                        Profile
                    </button>
                    <button id="password-tab" class="px-6 py-3 text-gray-500 font-medium text-sm">
                        Password
                    </button>
                </div>

                <!-- Profile Form -->
                <div id="profile-content" class="p-6">
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
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button
                                type="submit"
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Confirm changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Form -->
                <div id="password-content" class="p-6 hidden">
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
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button
                                type="submit"
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Confirm changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Logout Button -->
            <div class="mt-6 text-center">
                <a href="{{ route('logout') }}" class="text-sm text-red-600 hover:text-red-700">Log out</a>
            </div>

            <!-- Simple Text Footer -->
            <div class="mt-12 text-center text-sm text-gray-500">
                <p>&copy; 2025 Numblio. All rights reserved.</p>
                <div class="mt-2 space-x-4">
                    <a href="#" class="text-gray-500 hover:text-gray-700">Privacy Policy</a>
                    <a href="#" class="text-gray-500 hover:text-gray-700">Terms of Service</a>
                    <a href="#" class="text-gray-500 hover:text-gray-700">Contact</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple tab functionality
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
