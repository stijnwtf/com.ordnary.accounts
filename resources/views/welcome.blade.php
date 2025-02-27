<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSO</title>
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
                    <h2 class="text-2xl font-bold text-gray-900">Welcome to Numblio Single Sign-On.</h2>
                    <p class="text-sm text-gray-600 mt-2">Easily access all your Numblio services with one secure login..</p>
                </div>
            </div>

            <!-- Error Alert -->
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">SSO Service Disruption</h3>
                        <div class="mt-1 text-sm text-yellow-700">
                            We are currently experiencing some issues with the Single Sign-On (SSO) service. You may encounter difficulties logging in. Our team is actively working on a fix.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Home Button -->
            <div>
                <a href="/" class="w-full block text-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Sign in with Numblio
                </a>
            </div>

            <!-- Footer -->
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
</body>
</html>
