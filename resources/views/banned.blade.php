<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Suspended</title>
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
                        <h2 class="text-2xl font-bold text-gray-900">Account Suspended</h2>
                        <p class="text-sm text-gray-600 mt-2">Your access to this application has been restricted.</p>
                    </div>
                </div>

                <!-- Ban Alert -->
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-ban text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Account Access Denied</h3>
                            <div class="mt-1 text-sm text-red-700">
                                Your account has been suspended and you are no longer allowed to use this application.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Section -->
                <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-gray-500 text-lg mt-0.5"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-800">What does this mean?</h3>
                            <div class="mt-1 text-sm text-gray-600">
                                <p>Your account has been permanently suspended due to a violation of our terms of service. This means you can no longer:</p>
                                <ul class="list-disc list-inside mt-2 space-y-1">
                                    <li>Access your account</li>
                                    <li>Use any of our services</li>
                                    <li>Create new accounts</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-headset text-blue-500 text-lg mt-0.5"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Need Help?</h3>
                            <div class="mt-1 text-sm text-blue-700">
                                If you believe this is a mistake or would like to appeal this decision, please contact our support team.
                            </div>
                            <div class="mt-3">
                                <a href="https://support.numblio.com" class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Contact Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center">
                    <a href="/" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Homepage
                    </a>
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
