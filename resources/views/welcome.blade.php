<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numblio Single Sign-On</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center">
    <div class="container max-w-4xl mx-auto px-6 py-12">
        <!-- Logo & Header -->
        <div class="text-center mb-10">
            <div class="flex justify-center mb-6">
                <i class="fas fa-lock text-blue-600 text-5xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Welcome to Numblio Single Sign-On</h1>
            <p class="text-gray-600 max-w-xl mx-auto">Access all your Numblio services securely with a single account</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-8">
                <!-- Features section -->
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center p-4">
                        <div class="w-12 h-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-4">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="font-medium text-gray-900">Enhanced Security</h3>
                        <p class="text-sm text-gray-500 mt-2">State-of-the-art protection for your account</p>
                    </div>
                    
                    <div class="text-center p-4">
                        <div class="w-12 h-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-4">
                            <i class="fas fa-sync"></i>
                        </div>
                        <h3 class="font-medium text-gray-900">Seamless Access</h3>
                        <p class="text-sm text-gray-500 mt-2">One login for all Numblio services</p>
                    </div>
                    
                    <div class="text-center p-4">
                        <div class="w-12 h-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-4">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3 class="font-medium text-gray-900">User Control</h3>
                        <p class="text-sm text-gray-500 mt-2">Manage your permissions in one place</p>
                    </div>
                </div>
                
                <!-- Action buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
                    <a href="/login" class="flex-1 bg-blue-600 text-white text-center py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-150 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                    </a>
                    <a href="/register" class="flex-1 bg-white border border-gray-300 text-gray-700 text-center py-3 px-6 rounded-lg hover:bg-gray-50 transition duration-150 flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i> Create Account
                    </a>
                </div>
            </div>
            
            <!-- Info banner -->
            <div class="bg-blue-50 p-4 border-t border-blue-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0 text-blue-500">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            We've upgraded our SSO with two-factor authentication for enhanced security.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>&copy; 2023 Numblio. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
