<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f9fafb] min-h-screen flex flex-col">
<div class="flex items-center justify-center flex-grow">
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="mb-8 flex flex-col items-start">
                <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto mb-3">
                <h2 class="text-2xl font-bold text-gray-900">Forgot password?</h2>
                <p class="text-sm text-gray-600 mt-2">Enter your email address to receive a password reset link.</p>
            </div>

            <!-- Formulier -->
            <form method="POST" action="" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input placeholder="john.doe@example.com" type="email" name="email" id="email" required
                               class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 border-gray-300">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Send
                    </button>
                </div>
            </form>


            <!-- Simple Text Footer -->
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
