<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Suspended</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-white min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow">
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="mb-8 flex flex-col items-start">
                    <img src="https://cdn.ordnary.com/site_assets/ordnary-black.png" alt="Ordnary Logo" class="h-8 w-auto mb-3">
                    <h2 class="text-2xl font-bold text-gray-900">Account Suspended</h2>
                    <p class="text-sm text-gray-600 mt-2">Your access has been restricted.</p>
                </div>

                <!-- Suspension Alert -->
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-ban text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 mb-2">Your account has been suspended</h3>
                            <p class="text-sm text-red-700 mb-2">
                                @if(isset($user) && $user && $user->suspension_reason)
                                    {{ $user->suspension_reason }}
                                @else
                                    Your account has been suspended due to a violation of our terms of service. This action was taken after careful review of your account activity.
                                @endif
                            </p>
                            <p class="text-xs text-red-600 mt-3">
                                Suspended since: 
                                @if(isset($user) && $user && $user->suspended_at)
                                    {{ $user->suspended_at->format('M j, Y') }}
                                @else
                                    {{ date('M j, Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="bg-[#E7F3FF] border-l-4 border-[#0866FF] p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-[#0866FF]"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-700 mb-3">
                                If you believe this is a mistake or would like to appeal this decision, please contact our support team.
                            </p>
                            <a href="https://support.ordnary.com" 
                                class="inline-flex items-center px-4 py-2 bg-[#0866FF] text-white text-sm font-medium rounded-full hover:bg-[#0952CC] focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-headset mr-2"></i>
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center text-sm text-[#0866FF] hover:underline font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to login
                    </a>
                </div>

                <!-- Footer -->
                <footer class="mt-8 text-xs text-gray-400">
                    <div class="flex flex-col space-y-1">
                        <span>&copy; {{ date('Y') }} Ordnary. All rights reserved. Ordnary and all associated services
                            are protected by copyright and intellectual property laws.</span>
                        <div class="flex items-center space-x-2 mt-1">
                            <a href="https://ordnary.com/documents/privacy-policy" class="hover:text-gray-600">Privacy Policy</a>
                            <span class="text-gray-500">•</span>
                            <a href="https://ordnary.com/documents/terms-and-conditions" class="hover:text-gray-600">Terms & Conditions</a>
                            <span class="text-gray-500">•</span>
                            <a href="https://ordnary.com/documents/disclaimer" class="hover:text-gray-600">Disclaimer</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>
