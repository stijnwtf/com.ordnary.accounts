@extends('my-account.layout')

@section('title', lang('profile.title', [], 'profile'))

@section('content')
    <div x-data="{ 
        activeTab: (() => {
            const navigation = performance.getEntriesByType('navigation')[0];
            const isReload = navigation && navigation.type === 'reload';
            if (!isReload) {
                sessionStorage.removeItem('profileTab');
            }
            return isReload ? (sessionStorage.getItem('profileTab') || 'details') : 'details';
        })()
    }">
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
                <button @click="activeTab = 'details'; sessionStorage.setItem('profileTab', 'details')"
                    :class="activeTab === 'details' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center"
                    data-tab="details">
                    <i class="fas fa-user mr-2"></i> {{ lang('tabs.details', [], 'profile') }}
                </button>

                <button @click="activeTab = 'customization'; sessionStorage.setItem('profileTab', 'customization')"
                    :class="activeTab === 'customization' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center"
                    data-tab="customization">
                    <i class="fas fa-palette mr-2"></i> {{ lang('tabs.customization', [], 'profile') }}
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <!-- Details Tab -->
        <div x-show="activeTab === 'details'" x-cloak data-tab-content="details">
            <form method="POST" action="{{ route('my-account.profile') }}" class="space-y-6">
                @csrf

                <!-- Name, Email, Phone & Language -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First Name Input -->
                    <div>
                        <label for="first_name"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.first_name', [], 'profile') }}</label>
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
                        <label for="last_name"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.last_name', [], 'profile') }}</label>
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
                    <label for="email"
                        class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.email', [], 'profile') }}</label>
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
                            {{ lang('fields.phone', [], 'profile') }} <span
                                class="text-gray-400">{{ lang('fields.phone_optional', [], 'profile') }}</span>
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
                        <label for="language"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ lang('fields.language', [], 'profile') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                            <select name="language" id="language" onchange="this.form.submit()"
                                class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none {{ $errors->has('language') ? 'border-red-500' : 'border-gray-300' }}">
                                @foreach (config('app.available_locales', ['en', 'nl']) as $locale)
                                    <option value="{{ $locale }}"
                                        {{ old('language', $user->lang ?? config('app.locale')) == $locale ? 'selected' : '' }}>
                                        @if ($locale == 'nl')
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
            <form method="POST" action="{{ route('account.update-bio') }}">
                @csrf
                <div class="space-y-6">
                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                            Bio
                        </label>
                        <textarea id="bio" name="bio" rows="4" maxlength="500"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Tell us about yourself...">{!! old('bio', $user->bio) !!}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Max 500 characters</p>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i>Save Bio
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check immediately for Alpine.js fallback
            if (typeof Alpine === 'undefined') {
                console.log('Alpine.js not loaded, using fallback');

                // Get all tab buttons and content sections
                const tabButtons = document.querySelectorAll('[data-tab]');
                const tabContents = document.querySelectorAll('[data-tab-content]');

                // Remove x-cloak from all tab contents
                tabContents.forEach(content => {
                    content.removeAttribute('x-cloak');
                });

                // Check if this is a page reload
                const navigation = performance.getEntriesByType('navigation')[0];
                const isReload = navigation && navigation.type === 'reload';
                
                // Clear sessionStorage if not a reload
                if (!isReload) {
                    sessionStorage.removeItem('profileTab');
                }
                
                // Get saved tab from sessionStorage only if it's a reload, otherwise default to details
                const savedTab = isReload ? (sessionStorage.getItem('profileTab') || 'details') : 'details';

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

                    // Save to sessionStorage
                    sessionStorage.setItem('profileTab', tabId);
                }

                // Add click event listeners to tab buttons
                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const tabId = this.getAttribute('data-tab');
                        activateTab(tabId);
                    });
                });

                // Activate the saved tab on page load
                activateTab(savedTab);
            }
        });
    </script>
@endsection
