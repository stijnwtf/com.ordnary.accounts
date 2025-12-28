{{-- resources/views/auth/password/forgot.blade.php --}}
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google reCAPTCHA v2 Invisible -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="bg-white min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow">
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="mb-8 flex flex-col items-start">
                    <img src="https://cdn.ordnary.com/site_assets/ordnary-black.png" alt="Ordnary Logo" class="h-8 w-auto mb-3">
                    <h2 class="text-2xl font-bold text-gray-900">Forgot password?</h2>
                    <p class="text-sm text-gray-600 mt-2">Enter your email address to receive a password reset link.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Something went wrong.</h3>
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

                <!-- Formulier -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input placeholder="john.doe@example.com" type="email" name="email" id="email" required
                                class="w-full px-3 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:border-transparent"
                                value="{{ request('email') ?? old('email') }}">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="g-recaptcha w-full bg-[#0866FF] text-white py-3 px-4 rounded-full hover:bg-[#0952CC] focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:ring-offset-2 transition-colors duration-200 font-medium"
                            data-sitekey="{{ config('services.recaptcha.site_key') }}"
                            data-callback="onSubmit"
                            data-action="forgot_password">
                            Send Reset Link
                        </button>
                    </div>
                </form>

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

    <script>
        // reCAPTCHA v2 invisible callback
        function onSubmit(token) {
            document.querySelector('form').submit();
        }

        // Auto-submit if email is pre-filled from query parameter
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const urlParams = new URLSearchParams(window.location.search);
            const emailParam = urlParams.get('email');
            
            if (emailParam && emailInput.value) {
                // Trigger reCAPTCHA verification automatically
                setTimeout(function() {
                    document.querySelector('button[type="submit"]').click();
                }, 500);
            }
        });
    </script>
</body>

</html>