@extends('my-account.layout')

@section('title', lang('profile.title', [], 'profile'))

@section('content')
    <div x-data="{ activeTab: 'gegevens' }"
        x-init="() => { activeTab = localStorage.getItem('profileTab') || 'gegevens' }">
        <div class="mb-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                {{ lang('personal_info', [], 'profile') }}
            </h3>
            <p class="mt-2 text-sm text-gray-600">
                {{ lang('subtitle', [], 'profile') }}
            </p>
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-6 border-b border-gray-200">
            <div class="flex -mb-px">
                <button @click="activeTab = 'gegevens'; localStorage.setItem('profileTab', 'gegevens')"
                    :class="activeTab === 'gegevens' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center"
                    data-tab="gegevens">
                    <i class="fas fa-user mr-2"></i> Gegevens
                </button>

                <button @click="activeTab = 'customization'; localStorage.setItem('profileTab', 'customization')"
                    :class="activeTab === 'customization' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center"
                    data-tab="customization">
                    <i class="fas fa-palette mr-2"></i> Customization
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <!-- Gegevens Tab -->
        <div x-show="activeTab === 'gegevens'" x-cloak data-tab-content="gegevens">
            <form method="POST" action="{{ route('my-account.profile') }}" class="space-y-6">
                @csrf

                <!-- Name, Email, Phone & Language -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Name Input -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.first_name', [], 'profile') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" placeholder="John" name="first_name" id="first_name"
                                    value="{{ old('first_name', $user->first_name) }}" required
                                    class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('first_name') ? 'border-red-500' : 'border-gray-300' }}">
                            </div>
                            @error('first_name')
                                <p class="mt-1 text-xs text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Last Name Input -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.last_name', [], 'profile') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" placeholder="Doe" name="last_name" id="last_name"
                                    value="{{ old('last_name', $user->last_name) }}" required
                                    class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('last_name') ? 'border-red-500' : 'border-gray-300' }}">
                            </div>
                            @error('last_name')
                                <p class="mt-1 text-xs text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.email', [], 'profile') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input placeholder="john.doe@example.com" type="email" name="email" id="email"
                                value="{{ old('email', $user->email) }}" required
                                class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">{{ lang('fields.email_helper', [], 'profile') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Phone Number Input -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ lang('fields.phone', [], 'profile') }} <span class="text-gray-400">{{ lang('fields.phone_optional', [], 'profile') }}</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input placeholder="+31 6 12345678" type="tel" name="phone" id="phone"
                                    value="{{ old('phone', $user->phone ?? '') }}"
                                    class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}">
                            </div>
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">{{ lang('fields.phone_helper', [], 'profile') }}</p>
                        </div>

                        <!-- Language Preference -->
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.language', [], 'profile') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-globe text-gray-400"></i>
                                </div>
                                <select name="language" id="language" onchange="this.form.submit()"
                                    class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none {{ $errors->has('language') ? 'border-red-500' : 'border-gray-300' }}">
                                    @foreach(config('app.available_locales', ['en', 'nl']) as $locale)
                                        <option value="{{ $locale }}" {{ (old('language', $user->lang ?? config('app.locale')) == $locale) ? 'selected' : '' }}>
                                            @if($locale == 'nl')
                                                Nederlands
                                            @elseif($locale == 'en')
                                                English
                                            @elseif($locale == 'de')
                                                Deutsch
                                            @elseif($locale == 'fr')
                                                Français
                                            @else
                                                {{ strtoupper($locale) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ lang('fields.language_helper', [], 'profile') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-1.5"></i> {{ lang('buttons.save', [], 'profile') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Customization Tab -->
        <div x-show="activeTab === 'customization'" x-cloak data-tab-content="customization">
            <div class="space-y-6">
                <!-- Profile Picture -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h4 class="text-base font-medium text-gray-900 flex items-center mb-4">
                        <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                        Profile Picture
                    </h4>
                    <div class="flex items-center gap-6">
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <img class="h-24 w-24 rounded-full object-cover border-2 border-gray-200"
                                    src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=200&d=mp&r=pg"
                                    alt="{{ $user->first_name }} {{ $user->last_name }}">
                                <div class="absolute bottom-0 right-0 h-5 w-5 bg-green-400 rounded-full border-2 border-white"></div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 mb-3">Update your profile picture. We use Gravatar for profile images.</p>
                            <div class="flex gap-3">
                                <button type="button" 
                                    class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <i class="fas fa-upload mr-1.5"></i>Upload
                                </button>
                                <button type="button" 
                                    class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-red-600 bg-white border border-red-300 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    <i class="fas fa-trash-alt mr-1.5"></i>Remove
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">JPG or PNG. Max 2MB.</p>
                        </div>
                    </div>
                </div>

                <!-- Banner Customization -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h4 class="text-base font-medium text-gray-900 flex items-center mb-4">
                        <i class="fas fa-image text-blue-500 mr-2"></i>
                        Banner Customization
                    </h4>
                    <p class="text-sm text-gray-600 mb-4">Customize your profile banner to make your account unique.</p>
                    <div class="w-full h-32 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg overflow-hidden relative mb-4">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium opacity-75">Coming Soon</span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" disabled
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-gray-400 cursor-not-allowed">
                            <i class="fas fa-upload mr-1.5"></i>Upload Banner
                        </button>
                        <button type="button" disabled
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-gray-600 bg-white border border-gray-300 cursor-not-allowed">
                            <i class="fas fa-trash-alt mr-1.5"></i>Remove
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">JPG or PNG. Max 5MB. Recommended: 1500x500px</p>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Wait for Alpine.js to be available, or use fallback after 500ms
            setTimeout(function () {
                if (typeof Alpine === 'undefined') {
                    console.log('Alpine.js not loaded, using fallback');

                    // Get all tab buttons and content sections
                    const tabButtons = document.querySelectorAll('[data-tab]');
                    const tabContents = document.querySelectorAll('[data-tab-content]');

                    // Get saved tab from localStorage or default to gegevens
                    const savedTab = localStorage.getItem('profileTab') || 'gegevens';

                    // Function to activate a tab
                    function activateTab(tabId) {
                        // Hide all tab contents
                        tabContents.forEach(content => {
                            content.style.display = 'none';
                        });

                        // Show the selected tab content
                        const activeContent = document.querySelector(`[data-tab-content="${tabId}"]`);
                        if (activeContent) {
                            activeContent.style.display = 'block';
                        }

                        // Update tab button styles
                        tabButtons.forEach(button => {
                            if (button.getAttribute('data-tab') === tabId) {
                                button.classList.add('border-blue-500', 'text-blue-600');
                                button.classList.remove('border-transparent', 'text-gray-500');
                            } else {
                                button.classList.remove('border-blue-500', 'text-blue-600');
                                button.classList.add('border-transparent', 'text-gray-500');
                            }
                        });

                        // Save to localStorage
                        localStorage.setItem('profileTab', tabId);
                    }

                    // Add click event listeners to tab buttons
                    tabButtons.forEach(button => {
                        button.addEventListener('click', function () {
                            const tabId = this.getAttribute('data-tab');
                            activateTab(tabId);
                        });
                    });

                    // Activate the saved tab on page load
                    activateTab(savedTab);
                }
            }, 500);
        });
    </script>
@endsection