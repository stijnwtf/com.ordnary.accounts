<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-[#f9fafb] min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow">
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="mb-8 flex flex-col items-start">
                    <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto mb-3">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Enterprise Login</h2>
                        <p class="text-sm text-gray-600 mt-2">Enter your company domain to access your organization's portal.</p>
                    </div>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There was an error with your request</h3>
                                <div class="mt-1 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Success</h3>
                                <div class="mt-1 text-sm text-green-700">
                                    {{ session('success') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Enterprise Login Form -->
                <form method="POST" action="" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="domain" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-2 text-gray-500"></i>
                            Company Domain
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="domain" 
                                   name="domain" 
                                   value="{{ old('domain') }}"
                                   placeholder="example.com"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('domain') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Enter your organization's domain name (e.g., company.com)
                        </p>
                    </div>

                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Continue to Login
                        </button>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 text-lg mt-0.5 mr-3 flex-shrink-0"></i>
                            <div>
                                <h3 class="text-sm font-medium text-blue-800 mb-2">Enterprise Access</h3>
                                <p class="text-sm text-blue-700 mb-3">
                                    This portal is for enterprise customers only. You'll be redirected to your organization's single sign-on provider.
                                </p>
                                <ul class="text-xs text-blue-600 space-y-1">
                                    <li class="flex items-center">
                                        <i class="fas fa-check mr-2"></i>
                                        Secure single sign-on authentication
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check mr-2"></i>
                                        Access to enterprise features
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check mr-2"></i>
                                        Centralized user management
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Back to Regular Login -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        Not an enterprise user?
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                            Sign in with regular account
                        </a>
                    </p>
                </div>

                <!-- Help Section -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Need help?
                        <a href="https://support.numblio.com/enterprise" class="text-blue-600 hover:text-blue-500 transition-colors">
                            Contact Enterprise Support
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4 text-center text-xs text-gray-400">
        <div class="flex items-center justify-center space-x-4">
            <span>&copy; {{ date('Y') }} Numblio. All rights reserved.</span>
            <a href="https://numblio.com/documents/privacy" class="hover:text-gray-600 transition-colors">Privacy Policy</a>
            <a href="https://numblio.com/documents/terms" class="hover:text-gray-600 transition-colors">Terms of Service</a>
        </div>
    </footer>
</body>

</html>
