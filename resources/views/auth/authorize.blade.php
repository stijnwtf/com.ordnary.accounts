<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorize Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-white min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Header with logos -->
            <div class="mb-8 flex flex-col items-center">
                <div class="flex items-center justify-center space-x-4 mb-6">
                    <!-- Ordnary Logo -->
                    <div class="w-16 h-16 bg-[#0866FF] rounded-full flex items-center justify-center">
                        <img src="https://cdn.ordnary.com/site_assets/ordnary-white-icon.png" alt="Ordnary" class="h-10 w-10">
                    </div>
                    
                    <!-- Connection indicator -->
                    <div class="flex items-center">
                        <div class="w-8 h-0.5 bg-gray-300"></div>
                        <i class="fas fa-check-circle text-green-500 mx-1"></i>
                        <div class="w-8 h-0.5 bg-gray-300"></div>
                    </div>
                    
                    <!-- Client App Logo -->
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center border-2 border-gray-200">
                        @if(!empty($client->logo_url))
                            <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="h-10 w-10 object-contain rounded-full">
                        @else
                            <i class="fas fa-cube text-gray-400 text-2xl"></i>
                        @endif
                    </div>
                </div>

                <!-- Title -->
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900">Authorize {{ $client->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1"><span class="font-medium">{{ $client->name }}</span> by 
                        <a href="#" class="text-[#0866FF] hover:underline">{{ $client->owner_name ?? 'Ordnary' }}</a>wants to access your Ordnary account</p>
                </div>
            </div>

            <!-- Authorization Details Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
                <!-- User Info Section -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-[#0866FF] rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Section -->
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Personal user data</h3>
                    <div class="space-y-2">
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-0.5 mr-2"></i>
                            <div class="text-sm text-gray-700">
                                <span class="font-medium">Email address</span> <span class="text-gray-500">(read-only)</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-0.5 mr-2"></i>
                            <div class="text-sm text-gray-700">
                                <span class="font-medium">Profile information</span> <span class="text-gray-500">(read-only)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                @if(!empty($client->description))
                <div class="px-4 pb-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-600">{{ $client->description }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Authorization Message -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">What happens next?</p>
                        <p class="text-blue-700">Authorizing will redirect you to <span class="font-medium">{{ parse_url($redirect_uri, PHP_URL_HOST) }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <form method="POST" action="{{ route('authorize.approve') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->client_id }}">
                <input type="hidden" name="redirect_uri" value="{{ $redirect_uri }}">
                
                <button type="submit"
                    class="w-full bg-[#0866FF] text-white py-3 px-4 rounded-full hover:bg-[#0952CC] focus:outline-none focus:ring-2 focus:ring-[#0866FF] focus:ring-offset-2 transition-colors duration-200 font-medium shadow-sm">
                    Authorize {{ $client->name }}
                </button>
            </form>

            <form method="POST" action="{{ route('authorize.cancel') }}">
                @csrf
                <button type="submit"
                    class="w-full mt-3 bg-white text-gray-700 py-3 px-4 rounded-full border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                    Cancel
                </button>
            </form>

            <!-- Footer Info -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500 mt-3">
                    Created <span class="font-medium">{{ $client->created_at->diffForHumans() }}</span>
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    <a href="#" class="text-[#0866FF] hover:underline">Learn more about OAuth</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
