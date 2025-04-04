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
    <!-- GDPR Cookie Consent Banner -->
    <div id="cookie-consent-banner" class="fixed bottom-0 left-0 right-0 bg-white shadow-lg border-t border-gray-200 z-50 transform transition-transform duration-300">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-600"><strong>Privacy en cookies:</strong> Deze website gebruikt cookies om uw ervaring te verbeteren en voor essentiële functionaliteit. We verzamelen en verwerken gegevens in overeenstemming met de AVG (GDPR).</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <button id="cookie-preferences" class="text-xs px-4 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50">Voorkeuren aanpassen</button>
                    <button id="accept-necessary" class="text-xs px-4 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50">Alleen noodzakelijk</button>
                    <button id="accept-all-cookies" class="text-xs px-4 py-2 bg-blue-600 border border-blue-600 rounded-md text-white hover:bg-blue-700">Alles accepteren</button>
                </div>
            </div>
        </div>
    </div>

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

        <!-- GDPR Notice Bar -->
        <div class="mb-8 bg-blue-50 border border-blue-100 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 flex-1 md:flex md:justify-between">
                    <p class="text-sm text-blue-700">
                        Wij voldoen aan de AVG (GDPR) en beschermen uw persoonlijke gegevens. Wij verzamelen alleen gegevens die noodzakelijk zijn voor de dienstverlening.
                    </p>
                    <p class="mt-3 text-sm md:mt-0 md:ml-6">
                        <a href="/privacy" class="whitespace-nowrap font-medium text-blue-700 hover:text-blue-600">Lees meer <span aria-hidden="true">&rarr;</span></a>
                    </p>
                </div>
            </div>
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
                            <li class="flex">
                                <div class="mr-4 mt-1">
                                    <svg class="w-5 h-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium">Privacy by Design</h3>
                                    <p class="text-gray-600 text-sm mt-1">GDPR-compliant architecture with data minimization principles</p>
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
                                <div class="flex items-center text-sm mt-2">
                                    <i class="fas fa-server text-blue-500 mr-2"></i>
                                    <span>EU-based Data Centers</span>
                                </div>
                                <div class="flex items-center text-sm mt-2">
                                    <i class="fas fa-file-contract text-blue-500 mr-2"></i>
                                    <span>Data Processing Agreements</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- GDPR Features Section -->
                <div class="mt-10 pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Privacy & Compliance Features</h3>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-user-shield text-blue-500 mr-2"></i>
                                <h4 class="font-medium text-gray-900">Data Subject Rights</h4>
                            </div>
                            <p class="text-xs text-gray-600">Access, rectification, erasure, portability, and restriction of your personal data</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                                <h4 class="font-medium text-gray-900">Data Retention</h4>
                            </div>
                            <p class="text-xs text-gray-600">Configurable data retention periods and automated data deletion</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-download text-blue-500 mr-2"></i>
                                <h4 class="font-medium text-gray-900">Data Portability</h4>
                            </div>
                            <p class="text-xs text-gray-600">Export your data in machine-readable format at any time</p>
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

        <!-- Data Protection Links Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-10">
            <h3 class="text-lg font-semibold mb-4">Data Protection & Privacy</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/privacy" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-file-alt text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Privacy Policy</h4>
                        <p class="text-sm text-gray-600">Uitgebreide informatie over hoe wij uw gegevens verzamelen en verwerken</p>
                    </div>
                </a>
                <a href="/gdpr" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-gavel text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">GDPR Compliance</h4>
                        <p class="text-sm text-gray-600">Informatie over uw rechten onder de Algemene Verordening Gegevensbescherming</p>
                    </div>
                </a>
                <a href="/data-processing" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-file-contract text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Data Processing Agreements</h4>
                        <p class="text-sm text-gray-600">Informatie over onze overeenkomsten met derde partijen en subverwerkers</p>
                    </div>
                </a>
                <a href="/contact-dpo" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-user-tie text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Contact DPO</h4>
                        <p class="text-sm text-gray-600">Neem contact op met onze Data Protection Officer voor privacy-gerelateerde vragen</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-xs text-gray-500">
            <div class="flex flex-col space-y-2">
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <span>&copy; {{ date('Y') }} Numblio Technologies, Inc.</span>
                    <a href="/privacy" class="hover:text-gray-700">Privacy Policy</a>
                    <a href="/terms" class="hover:text-gray-700">Terms of Service</a>
                    <a href="/cookies" class="hover:text-gray-700">Cookie Policy</a>
                    <a href="/gdpr" class="hover:text-gray-700">GDPR Compliance</a>
                    <a href="/data-subject-rights" class="hover:text-gray-700">Your Data Rights</a>
                    <a href="/support" class="hover:text-gray-700">Support</a>
                </div>
                <span>Numblio Identity Platform is protected by international copyright and intellectual property laws.</span>
                <span>Data processing occurs in EU-based data centers in compliance with GDPR regulations. Data Verwerker: Numblio B.V. - KvK 12345678.</span>
            </div>
        </footer>
    </div>

    <!-- GDPR-related JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cookie consent functionality
            const consentBanner = document.getElementById('cookie-consent-banner');
            const acceptAllBtn = document.getElementById('accept-all-cookies');
            const acceptNecessaryBtn = document.getElementById('accept-necessary');
            const preferencesBtn = document.getElementById('cookie-preferences');
            
            // Check if consent was previously given
            if (!localStorage.getItem('cookie-consent')) {
                consentBanner.classList.remove('translate-y-full');
            } else {
                consentBanner.classList.add('hidden');
            }
            
            // Accept all cookies
            acceptAllBtn.addEventListener('click', function() {
                localStorage.setItem('cookie-consent', 'all');
                localStorage.setItem('cookie-consent-timestamp', new Date().toISOString());
                consentBanner.classList.add('translate-y-full');
                setTimeout(() => {
                    consentBanner.classList.add('hidden');
                }, 300);
            });
            
            // Accept only necessary cookies
            acceptNecessaryBtn.addEventListener('click', function() {
                localStorage.setItem('cookie-consent', 'necessary');
                localStorage.setItem('cookie-consent-timestamp', new Date().toISOString());
                consentBanner.classList.add('translate-y-full');
                setTimeout(() => {
                    consentBanner.classList.add('hidden');
                }, 300);
            });
            
            // Open cookie preferences modal
            preferencesBtn.addEventListener('click', function() {
                // Here you would typically open a modal with detailed cookie settings
                alert('Cookie voorkeuren worden binnenkort geïmplementeerd.');
            });
        });
    </script>
</body>
</html>
