{{-- resources/views/auth/password/success.blade.php --}}
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-[#f9fafb] min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow">
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="mb-8 flex flex-col items-start">
                    <img src="https://cdn.ordnary.com/site_assets/ordnary-black.png" alt="Ordnary Logo" class="h-8 w-auto mb-3">
                    <h2 class="text-2xl font-bold text-gray-900">Password Reset Successful</h2>
                    <p class="text-sm text-gray-600 mt-2">Your password has been reset successfully.</p>
                </div>

                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                You can now log in with your new password.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                        Go to Login
                    </a>
                </div>
                <!-- Footer -->
                <footer class="mt-8 text-xs text-gray-400">
                    <div class="flex flex-col space-y-1">
                        <span>&copy; {{ date('Y') }} Ordnary. All rights reserved. Ordnary and all associated services
                            are protected by copyright and intellectual property laws.</span>
                        <div class="flex items-center space-x-2 mt-1">
                            <a href="https://ordnary.com/documents/privacy" class="hover:text-gray-600">Privacy</a>
                            <span class="text-gray-500">•</span>
                            <a href="https://ordnary.com/documents/terms" class="hover:text-gray-600">Terms</a>
                            <span class="text-gray-500">•</span>
                            <a href="https://support.ordnary.com" class="hover:text-gray-600">Help</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>