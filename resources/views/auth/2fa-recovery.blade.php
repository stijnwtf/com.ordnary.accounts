<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSO - Recovery Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-[#f9fafb] min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow">
        <!-- Left Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="mb-8 flex flex-col items-start">
                    <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto mb-3">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Recovery Code</h2>
                        <p class="text-sm text-gray-600 mt-2">Enter one of your recovery codes to regain access to your
                            account.</p>
                    </div>
                </div>

                <!-- Warning Alert -->
                @if (isset($client_id) && !$direct_login)
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">You are currently logging in to
                                    <b>{{ $client_name }}</b>.</h3>
                                <div class="mt-1 text-sm text-yellow-700">
                                    Complete the recovery process to proceed and gain access to {{ $client_name }}.
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
                                <h3 class="text-sm font-medium text-red-800">Recovery failed</h3>
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

                <!-- Form -->
                <form method="POST" action="{{ route('2fa.recovery.verify') }}" class="space-y-6">
                    @csrf

                    <!-- Recovery Code Input -->
                    <div>
                        <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-1">Recovery
                            Code</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input placeholder="Enter recovery code" type="text" name="recovery_code" id="recovery_code"
                                required
                                class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('recovery_code') ? 'border-red-500' : 'border-gray-300' }}">
                        </div>
                        @error('recovery_code')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    @if($direct_login)
                        <input type="hidden" name="direct_login" value="1">
                    @endif

                    @if($client_id)
                        <input type="hidden" name="client_id" value="{{ $client_id }}">
                    @endif

                    @if($redirect_uri)
                        <input type="hidden" name="redirect_uri" value="{{ $redirect_uri }}">
                    @endif

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Use Recovery Code
                        </button>
                    </div>

                    <!-- Back to 2FA Link -->
                    <div class="text-sm text-center">
                        <a href="{{ route('2fa') }}" class="text-blue-600 hover:text-blue-700">
                            Back to 2FA verification
                        </a>
                    </div>
                </form>

                <!-- Footer -->
                <footer class="mt-8 text-xs text-gray-400">
                    <div class="flex flex-col space-y-1">
                        <span>&copy; {{ date('Y') }} Numblio. All rights reserved. Numblio and all associated services
                            are protected by copyright and intellectual property laws.</span>
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
</body>

</html>