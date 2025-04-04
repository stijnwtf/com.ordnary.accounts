<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numblio Identity Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">
    <div class="container max-w-5xl mx-auto px-6 py-16">
        <!-- Enterprise Header -->
        <div class="mb-14">
            <div class="flex items-center mb-1">
                <svg class="w-8 h-8 text-blue-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M12 15V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M9 11L12 8L15 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="text-sm font-medium ml-2 text-blue-600">IDENTITY PLATFORM</span>
            </div>
            <h1 class="text-4xl font-bold tracking-tight mb-3">Numblio Authentication</h1>
            <p class="text-xl text-gray-600 max-w-3xl">Enterprise-grade identity management built for today's application ecosystems</p>
        </div>

        <!-- Technical Overview Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-12">
            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Left side - Value proposition -->
                    <div>
                        <h2 class="text-2xl font-semibold mb-6">Secure Authentication Infrastructure</h2>
                        <ul class="space-y-4">
                            <li class="flex">
                                <div class="mr-4 mt-1">
                                    <svg class="w-5 h-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium">JWT-based token architecture</h3>
                                    <p class="text-gray-600 text-sm mt-1">Stateless authentication with configurable expiration and refresh tokens</p>
                                </div>
                            </li>
                            <li class="flex">
                                <div class="mr-4 mt-1">
                                    <svg class="w-5 h-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium">Multi-factor Authentication (MFA)</h3>
                                    <p class="text-gray-600 text-sm mt-1">TOTP implementation with recovery code fallback system</p>
                                </div>
                            </li>
                            <li class="flex">
                                <div class="mr-4 mt-1">
                                    <svg class="w-5 h-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium">OAuth 2.0 Protocol Support</h3>
                                    <p class="text-gray-600 text-sm mt-1">Standardized authorization flows for third-party applications</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Right side - Technical stats -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Technical Specifications</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">Encryption</span>
                                    <span class="text-sm text-gray-500">AES-256</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">Uptime SLA</span>
                                    <span class="text-sm text-gray-500">99.99%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: 98%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">Response Time</span>
                                    <span class="text-sm text-gray-500">&lt;200ms</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: 90%"></div>
                                </div>
                            </div>
                            
                            <div class="pt-4 mt-4 border-t border-gray-200">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                                    <span>SOC 2 Type II Compliant</span>
                                </div>
                                <div class="flex items-center text-sm mt-2">
                                    <i class="fas fa-lock text-blue-500 mr-2"></i>
                                    <span>GDPR & Privacy Shield Framework</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-10">
                    <a href="/login" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center">
                        <span>Access Platform</span>
                        <svg class="ml-2 w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    
                    <a href="/register" class="px-6 py-3 bg-white text-gray-700 font-medium rounded-md border border-gray-300 hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center">
                        <span>Create Account</span>
                    </a>
                    
                    <a href="https://docs.numblio.com" class="px-6 py-3 text-gray-600 font-medium hover:text-gray-900 transition-colors duration-200 flex items-center justify-center">
                        <span>Developer Docs</span>
                        <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- System status banner -->
            <div class="bg-gray-50 p-4 border-t border-gray-200 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="relative">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <div class="absolute -inset-0.5 bg-green-500 rounded-full animate-ping opacity-75"></div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">All systems operational</p>
                    </div>
                </div>
                
                <a href="#" class="text-xs text-blue-600 hover:underline">View status page</a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-xs text-gray-500">
            <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-4">
                    <span>&copy; {{ date('Y') }} Numblio Technologies, Inc.</span>
                    <a href="https://numblio.com/documents/privacy" class="hover:text-gray-700">Privacy Policy</a>
                    <a href="https://numblio.com/documents/terms" class="hover:text-gray-700">Terms of Service</a>
                    <a href="https://support.numblio.com" class="hover:text-gray-700">Support</a>
                </div>
                <span>Numblio Identity Platform is protected by international copyright and intellectual property laws.</span>
            </div>
        </footer>
    </div>
</body>
</html>
