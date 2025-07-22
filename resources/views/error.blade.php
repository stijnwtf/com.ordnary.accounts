<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Numblio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'segoe': ['Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                    colors: {
                        'numblio': {
                            'blue': '#0078d4',
                            'light-blue': '#e3f2fd',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        .error-icon {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            box-shadow: 0 4px 20px rgba(255, 107, 107, 0.3);
        }
        .professional-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 4px 8px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="font-segoe bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 min-h-screen flex flex-col">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-[0.02] bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23000000" fill-opacity="1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
    
    <div class="flex items-center justify-center flex-grow px-4 py-8">
        <div class="w-full max-w-lg fade-in">
            <!-- Main Error Card -->
            <div class="professional-card rounded-2xl p-8 text-center">
                <!-- Error Icon -->
                <div class="mx-auto w-16 h-16 error-icon rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>

                <!-- Error Message -->
                <div class="mb-8">
                    <h1 class="text-3xl font-semibold text-gray-900 mb-3">
                        @if(str_contains($message, 'Invalid client_id'))
                            Authentication Error
                        @else
                            Something went wrong
                        @endif
                    </h1>
                    <p class="text-lg text-gray-600 mb-4">
                        @if(str_contains($message, 'Invalid client_id'))
                            The application you're trying to access is not properly configured or doesn't exist.
                        @elseif(str_contains($message, 'Redirect URI not allowed'))
                            The application is trying to redirect to an unauthorized location.
                        @else
                            We encountered an unexpected error while processing your request.
                        @endif
                    </p>
                    
                    <!-- Technical Details (collapsible) -->
                    <details class="text-left">
                        <summary class="cursor-pointer text-sm text-gray-500 hover:text-gray-700 mb-2 font-medium">
                            Technical Details
                        </summary>
                        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 font-mono border">
                            <div class="flex items-start space-x-2">
                                <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        </div>
                    </details>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button onclick="window.history.back()" 
                            class="w-full bg-numblio-blue hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Go Back
                    </button>
                    
                    <button onclick="window.location.reload()" 
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg transition-all duration-200 hover:shadow-md">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Try Again
                    </button>
                </div>

                <!-- Additional Help -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="flex items-center justify-center space-x-6 text-sm">
                        <a href="mailto:support@numblio.com" class="text-numblio-blue hover:text-blue-600 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contact Support
                        </a>
                        <a href="https://docs.numblio.com" class="text-gray-500 hover:text-gray-700 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Documentation
                        </a>
                    </div>
                </div>
            </div>

            <!-- Numblio Branding -->
            <div class="text-center mt-8">
                <div class="flex items-center justify-center mb-4">
                    <img src="/img/dark.png" alt="Numblio" class="h-6 opacity-60">
                </div>
                <p class="text-sm text-gray-500">
                    Secure authentication powered by Numblio
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-6 px-4">
        <div class="text-xs text-gray-400 space-x-4">
            <span>&copy; 2025 Numblio</span>
            <a href="/privacy" class="hover:text-gray-600 transition-colors">Privacy</a>
            <a href="/terms" class="hover:text-gray-600 transition-colors">Terms</a>
            <a href="/status" class="hover:text-gray-600 transition-colors">Status</a>
        </div>
    </footer>

    <script>
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Animate error icon
            const errorIcon = document.querySelector('.error-icon');
            if (errorIcon) {
                setTimeout(() => {
                    errorIcon.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        errorIcon.style.transform = 'scale(1)';
                    }, 300);
                }, 500);
            }

            // Auto-focus on go back button for accessibility
            const goBackBtn = document.querySelector('button[onclick="window.history.back()"]');
            if (goBackBtn) {
                goBackBtn.focus();
            }
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.history.back();
            } else if (e.key === 'r' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                window.location.reload();
            }
        });
    </script>
</body>
</html>
