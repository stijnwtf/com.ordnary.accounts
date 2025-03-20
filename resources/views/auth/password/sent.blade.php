{{-- resources/views/auth/password/sent.blade.php --}}
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Link Sent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f9fafb] min-h-screen flex flex-col">
<div class="flex items-center justify-center flex-grow">
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="mb-8 flex flex-col items-start">
                <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto mb-3">
                <h2 class="text-2xl font-bold text-gray-900">Check your email</h2>
                <p class="text-sm text-gray-600 mt-2">
                    {{ session('status') ?? 'If your email exists in our system, you will receive a password reset link shortly.' }}
                </p>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            The reset link will expire in 60 minutes. If you don't receive an email, check your spam folder or try again.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800">Back to login</a>
            </div>
            <!-- Footer -->
            <footer class="mt-16 text-xs text-gray-400">
                <div class="flex flex-col space-y-1">
                    <span>&copy; {{ date('Y') }} Numblio. All rights reserved. Numblio and all associated services are protected by copyright and intellectual property laws.</span>
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
