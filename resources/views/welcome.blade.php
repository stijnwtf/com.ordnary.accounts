<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numblio Single Sign-On</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-b from-[#f9fafb] to-white min-h-screen flex flex-col">
    <!-- Header -->
    <header class="w-full py-4 px-6 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <img src="/img/dark.png" alt="Numblio Logo" class="h-8 w-auto">
            <nav class="hidden md:flex space-x-6">
                <a href="#" class="text-gray-600 hover:text-gray-900">Documentation</a>
                <a href="#" class="text-gray-600 hover:text-gray-900">Support</a>
                <a href="#" class="text-gray-600 hover:text-gray-900">Contact</a>
            </nav>
        </div>
    </header>

    <div class="flex-grow container mx-auto px-4 py-12">
        <div class="max-w-6xl mx-auto">
            <!-- Main Content -->
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Column -->
                <div class="space-y-8">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Numblio Single Sign-On</h1>
                        <p class="text-xl text-gray-600">Your secure gateway to all Numblio services with one login.</p>
                    </div>

                    <!-- Primary Alert -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Enhanced Security Update</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    We've upgraded our SSO service with advanced security features including JWT tokens and multi-factor authentication support for enhanced protection of your account.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature List -->
                    <div class="space-y-4">
                        <h2 class="text-2xl font-semibold text-gray-900">Key Features</h2>
                        <div class="grid gap-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-shield-alt text-green-500 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Enhanced Security</h3>
                                    <p class="text-gray-600">State-of-the-art encryption and security protocols to protect your data.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-sync text-blue-500 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Seamless Integration</h3>
                                    <p class="text-gray-600">Access all Numblio services with a single authentication.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-shield text-purple-500 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">User Control</h3>
                                    <p class="text-gray-600">Manage your permissions and access levels across all services.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <div class="space-y-6">
                        <h2 class="text-2xl font-semibold text-gray-900">Getting Started</h2>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4 text-gray-700">
                                <span class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold">1</span>
                                <span>Create your Numblio account or sign in if you already have one</span>
                            </div>
                            <div class="flex items-center space-x-4 text-gray-700">
                                <span class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold">2</span>
                                <span>Access any Numblio service with your single account</span>
                            </div>
                            <div class="flex items-center space-x-4 text-gray-700">
                                <span class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold">3</span>
                                <span>Manage your account settings and permissions in one place</span>
                            </div>
                        </div>
                        <div class="pt-4">
                            <a href="/login" class="block w-full bg-blue-600 text-white text-center py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-150">
                                Get Started
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-8">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Products</h3>
                        <ul class="mt-4 space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Numblio CRM</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Numblio Analytics</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Numblio Insights</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Support</h3>
                        <ul class="mt-4 space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Documentation</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">API Reference</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Status</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Company</h3>
                        <ul class="mt-4 space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">About</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Blog</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Careers</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Legal</h3>
                        <ul class="mt-4 space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Privacy Policy</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Terms of Service</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-100">
                    <p class="text-center text-gray-500">&copy; 2025 Numblio. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
