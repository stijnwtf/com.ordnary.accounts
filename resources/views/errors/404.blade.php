<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-white min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow">
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="mb-8 flex flex-col items-start">
                    <img src="https://cdn.ordnary.com/site_assets/ordnary-black.png" alt="Ordnary Logo" class="h-8 w-auto mb-3">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">404 - Page Not Found</h2>
                        <p class="text-sm text-gray-600 mt-2">The page you're looking for doesn't exist or has been moved.</p>
                    </div>
                </div>

                <!-- 404 Icon Display -->
                <div class="mb-6 flex justify-center">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-4">
                            <i class="fas fa-search text-gray-400 text-4xl"></i>
                        </div>
                        <p class="text-6xl font-bold text-gray-200">404</p>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">What can you do?</h3>
                            <div class="mt-1 text-sm text-blue-700">
                                Check the URL for typos or return to the homepage to find what you need.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ route('login') }}" 
                        class="block w-full bg-[#0866FF] text-white py-3 px-4 rounded-full hover:bg-[#0952CC] focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:ring-offset-2 transition-colors duration-200 font-medium text-center">
                        <i class="fas fa-home mr-2"></i>Back to Login
                    </a>
                    <button onclick="window.history.back()" 
                        class="block w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200 font-medium text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Go Back
                    </button>
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
