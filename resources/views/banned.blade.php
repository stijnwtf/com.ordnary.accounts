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
    <div class="flex-grow p-8">
        <!-- Header with logo and title -->
        <div class="mb-8 text-center">
            <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto mx-auto mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Account Suspended</h1>
                <p class="text-lg text-gray-600 mt-2">Your access to this application has been restricted.</p>
            </div>
        </div>

        <!-- Two cards side by side -->
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left card - General suspension information -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-amber-50 border-b border-amber-200 px-6 py-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-amber-500 text-xl mr-3"></i>
                        <h2 class="text-lg font-semibold text-amber-800">Common Suspension Reasons</h2>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Accounts are typically suspended for the following reasons:</p>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-amber-500 text-sm mt-1 mr-3 flex-shrink-0"></i>
                            <span>Violation of terms of service or community guidelines</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-shield-alt text-amber-500 text-sm mt-1 mr-3 flex-shrink-0"></i>
                            <span>Suspicious or unauthorized account activity</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-user-times text-amber-500 text-sm mt-1 mr-3 flex-shrink-0"></i>
                            <span>Inappropriate behavior or content sharing</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lock text-amber-500 text-sm mt-1 mr-3 flex-shrink-0"></i>
                            <span>Security concerns or compromised account</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-ban text-amber-500 text-sm mt-1 mr-3 flex-shrink-0"></i>
                            <span>Repeated violations despite warnings</span>
                        </li>
                    </ul>

                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-headset text-blue-500 text-lg mt-0.5 mr-3 flex-shrink-0"></i>
                            <div>
                                <h3 class="text-sm font-medium text-blue-800 mb-2">Need Help?</h3>
                                <p class="text-sm text-blue-700 mb-3">
                                    If you believe this is a mistake or would like to appeal this decision, please contact our support team.
                                </p>
                                <a href="https://support.numblio.com" class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Contact Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right card - Specific suspension details -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-red-50 border-b border-red-200 px-6 py-4">
                    <div class="flex items-center">
                        <i class="fas fa-ban text-red-500 text-xl mr-3"></i>
                        <h2 class="text-lg font-semibold text-red-800">Your Suspension Details</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-ban mr-1"></i>
                                Suspended
                            </span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-500">Suspended Since</span>
                            <span class="text-sm text-gray-900">{{ date('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Type</span>
                            <span class="text-sm text-gray-900">Permanent</span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Reason for Suspension</h3>
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <p class="text-sm text-gray-700">
                                Your account has been permanently suspended due to a violation of our terms of service. This action was taken after careful review of your account activity.
                            </p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">What This Means</h3>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-times-circle text-red-500 mr-3"></i>
                                <span>You cannot access your account</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-times-circle text-red-500 mr-3"></i>
                                <span>You cannot use any of our services</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-times-circle text-red-500 mr-3"></i>
                                <span>You cannot create new accounts</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500">
                            This decision is final. If you believe this is an error, please contact support with your account details.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="max-w-6xl mx-auto mt-8 text-center">
            <a href="/" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Homepage
            </a>
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
