@extends('my-account.layout')

@section('title', lang('title', [], 'security'))

@section('content')
    <div x-data="{ 
        activeTab: (() => {
            const navigation = performance.getEntriesByType('navigation')[0];
            const isReload = navigation && navigation.type === 'reload';
            if (!isReload) {
                sessionStorage.removeItem('securityTab');
            }
            return isReload ? (sessionStorage.getItem('securityTab') || 'password') : 'password';
        })()
    }">
        <div class="mb-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                {{ lang('header', [], 'security') }}
            </h3>
            <p class="mt-2 text-sm text-gray-600">
                {{ lang('subtitle', [], 'security') }}
            </p>
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-6 border-b border-gray-200">
            <div class="flex -mb-px overflow-x-auto">
                <button @click="activeTab = 'password'; sessionStorage.setItem('securityTab', 'password')"
                    :class="activeTab === 'password' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center whitespace-nowrap">
                    <i class="fas fa-key mr-2"></i> Password
                </button>

                <button @click="activeTab = 'authentication'; sessionStorage.setItem('securityTab', 'authentication')"
                    :class="activeTab === 'authentication' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center whitespace-nowrap">
                    <i class="fas fa-shield-alt mr-2"></i> {{ lang('tabs.authentication', [], 'security') }}
                </button>

                <button @click="activeTab = 'activity'; sessionStorage.setItem('securityTab', 'activity')"
                    :class="activeTab === 'activity' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center whitespace-nowrap">
                    <i class="fas fa-history mr-2"></i> {{ lang('tabs.activity', [], 'security') }}
                </button>

                <button @click="activeTab = 'recovery'; sessionStorage.setItem('securityTab', 'recovery')"
                    :class="activeTab === 'recovery' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center whitespace-nowrap">
                    <i class="fas fa-life-ring mr-2"></i> {{ lang('tabs.recovery', [], 'security') }}
                </button>

                <button @click="activeTab = 'privacy'; sessionStorage.setItem('securityTab', 'privacy')"
                    :class="activeTab === 'privacy' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center whitespace-nowrap">
                    <i class="fas fa-user-shield mr-2"></i> {{ lang('tabs.privacy', [], 'security') }}
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <!-- Password Tab Content -->
        <div x-show="activeTab === 'password'" x-cloak>
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Change your password</h3>
                <p class="text-sm text-gray-600">Make sure you have a strong password that you don't use on other websites.</p>
            </div>
            <form method="POST" action="{{ route('my-account.password') }}" class="space-y-6">
                    @csrf

                    <!-- Current Password Section -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-900 mb-2">Current Password</label>
                        <div class="relative">
                            <input placeholder="Enter your current password" type="password"
                                name="current_password" id="current_password" required
                                class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300' }}">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        @error('current_password')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password Section -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900 mb-2">New Password</label>
                        <div class="relative">
                            <input placeholder="Enter your new password" type="password"
                                name="password" id="password" required
                                class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                                oninput="checkPasswordStrength()">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Password Strength Meter -->
                        <div class="mt-3">
                            <div class="flex justify-between mb-1">
                                <span class="text-xs text-gray-500">Password Strength</span>
                                <span id="password-strength-text" class="text-xs font-medium text-red-600">Too Weak</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div id="password-strength-meter" class="bg-red-500 h-1.5 rounded-full transition-all duration-300" style="width: 10%"></div>
                            </div>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-500">Must be at least 8 characters</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input placeholder="Confirm your new password" type="password"
                                name="password_confirmation" id="password_confirmation" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" class="password-toggle text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Tips -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800">Password Security Tips</h4>
                                <ul class="mt-2 text-xs text-blue-700 list-disc space-y-1 pl-5">
                                    <li>Use a mix of uppercase and lowercase letters</li>
                                    <li>Include numbers and special characters</li>
                                    <li>Avoid common words and personal information</li>
                                    <li>Use at least 12 characters for better security</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Update Password
                        </button>
                    </div>
            </form>
        </div>

        <!-- Authentication Tab Content -->
        <div x-show="activeTab === 'authentication'" x-cloak>
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ lang('two_factor.title', [], 'security') }}</h3>
                <p class="text-sm text-gray-600">{{ lang('subtitle', [], 'security') }}</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-lock text-blue-500 mr-2"></i>
                        <span class="text-base font-medium text-gray-900">{{ lang('two_factor.title', [], 'security') }}</span>
                    </div>
                    <div>
                        @if($user->hasTwoFactorEnabled())
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> {{ lang('two_factor.status.enabled', [], 'security') }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                {{ lang('two_factor.status.not_enabled', [], 'security') }}
                            </span>
                        @endif
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-5">
                    {{ lang('two_factor.description', [], 'security') }}
                </p>

                <div class="flex justify-end">
                    @if($user->hasTwoFactorEnabled())
                        <div class="flex space-x-3">
                            <button type="button"
                                onclick="document.getElementById('disable-2fa-modal').classList.remove('hidden')"
                                class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-red-600 bg-white border border-red-300 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <i class="fas fa-times-circle mr-1.5"></i>
                                {{ lang('two_factor.disable_button', [], 'security') }}
                            </button>
                            <form action="{{ route('account.security.regenerate-recovery-codes') }}" method="POST"
                                class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <i class="fas fa-sync-alt mr-1.5"></i>
                                    {{ lang('two_factor.regenerate_button', [], 'security') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('account.security.setup-2fa') }}"
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-shield-alt mr-1.5"></i> {{ lang('two_factor.enable_button', [], 'security') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Activity Tab Content -->
        <div x-show="activeTab === 'activity'" x-cloak>
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ lang('activity.title', [], 'security') }}</h3>
                <p class="text-sm text-gray-600">{{ lang('activity.description', [], 'security') }}</p>
            </div>

            <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ lang('activity.table.date_time', [], 'security') }}</th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ lang('activity.table.device', [], 'security') }}</th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ lang('activity.table.location', [], 'security') }}</th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ lang('activity.table.status', [], 'security') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($user->getRecentLoginActivities() as $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                        {{ $activity->created_at->format('d-m-Y H:i') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                        <div class="flex items-center">
                                            @if($activity->device_type === 'Mobile')
                                                <i class="fas fa-mobile-alt mr-1.5 text-gray-400"></i>
                                            @elseif($activity->device_type === 'Tablet')
                                                <i class="fas fa-tablet-alt mr-1.5 text-gray-400"></i>
                                            @else
                                                <i class="fas fa-laptop mr-1.5 text-gray-400"></i>
                                            @endif
                                            {{ $activity->browser }} on {{ $activity->platform }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">{{ $activity->location }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i
                                                class="fas fa-{{ $activity->status === 'success' ? 'check' : 'times' }}-circle mr-1"></i>
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center py-4">
                                            <i class="fas fa-search text-gray-300 text-3xl mb-2"></i>
                                            <p>{{ lang('activity.table.no_activity', [], 'security') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recovery Tab Content -->
        <div x-show="activeTab === 'recovery'" x-cloak>
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ lang('recovery.title', [], 'security') }}</h3>
                <p class="text-sm text-gray-600">{{ lang('recovery.description', [], 'security') }}</p>
            </div>

            <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                        <div>
                            <h5 class="text-sm font-medium text-gray-900 flex items-center">
                                <i class="fas fa-envelope text-gray-500 mr-1.5"></i>
                                {{ lang('recovery.email.title', [], 'security') }}
                            </h5>
                            <p class="text-xs text-gray-500 mt-1">{{ lang('recovery.email.description', [], 'security') }}
                            </p>
                        </div>
                        @if($user->recovery_email)
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-600">{{ $user->recovery_email }}</span>
                                @if($user->recovery_email_verified)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ lang('recovery.email.status.verified', [], 'security') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ lang('recovery.email.status.pending', [], 'security') }}
                                    </span>
                                @endif
                                <form action="{{ route('account.security.remove-recovery-email') }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center text-xs text-red-600 hover:text-red-800">
                                        <i class="fas fa-times mr-1"></i>
                                        {{ lang('recovery.email.actions.remove', [], 'security') }}
                                    </button>
                                </form>
                            </div>
                        @else
                            <button type="button"
                                onclick="document.getElementById('add-recovery-email-modal').classList.remove('hidden')"
                                class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none">
                                <i class="fas fa-plus mr-1"></i> {{ lang('recovery.email.actions.add', [], 'security') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Privacy & Data Tab Content -->
        <div x-show="activeTab === 'privacy'" x-cloak>
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ lang('privacy.title', [], 'security') }}</h3>
                <p class="text-sm text-gray-600">Manage your data and privacy settings.</p>
            </div>
            <div class="space-y-6">
                <!-- GDPR Data Export -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h4 class="text-base font-medium text-gray-900 flex items-center mb-4">
                        <i class="fas fa-download text-blue-500 mr-2"></i>
                        {{ lang('privacy.export.title', [], 'security') }}
                    </h4>

                    <p class="text-sm text-gray-600 mb-5">
                        {{ lang('privacy.export.description', [], 'security') }}
                    </p>

                    <div class="p-4 bg-blue-50 rounded-md mb-5 border border-blue-100">
                        <h5 class="text-sm font-medium text-blue-800 mb-2">
                            {{ lang('privacy.export.includes.title', [], 'security') }}</h5>
                        <ul class="text-xs text-blue-700 list-disc pl-5 space-y-1">
                            <li>{{ lang('privacy.export.includes.items.0', [], 'security') }}</li>
                            <li>{{ lang('privacy.export.includes.items.1', [], 'security') }}</li>
                            <li>{{ lang('privacy.export.includes.items.2', [], 'security') }}</li>
                            <li>{{ lang('privacy.export.includes.items.3', [], 'security') }}</li>
                        </ul>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('account.export-data') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-file-download mr-1.5"></i> {{ lang('privacy.export.button', [], 'security') }}
                        </a>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-right">{{ lang('privacy.export.format', [], 'security') }}</p>
                </div>

                <!-- Account Deletion -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h4 class="text-base font-medium text-gray-900 flex items-center mb-4">
                        <i class="fas fa-user-slash text-red-500 mr-2"></i>
                        {{ lang('privacy.delete.title', [], 'security') }}
                    </h4>

                    <p class="text-sm text-gray-600 mb-5">
                        {{ lang('privacy.delete.description', [], 'security') }}
                    </p>

                    <div class="p-4 bg-red-50 rounded-md mb-5 border border-red-100">
                        <h5 class="text-sm font-medium text-red-800 mb-2">
                            {{ lang('privacy.delete.warning.title', [], 'security') }}</h5>
                        <ul class="text-xs text-red-700 list-disc pl-5 space-y-1">
                            <li>{{ lang('privacy.delete.warning.items.0', [], 'security') }}</li>
                            <li>{{ lang('privacy.delete.warning.items.1', [], 'security') }}</li>
                            <li>{{ lang('privacy.delete.warning.items.2', [], 'security') }}</li>
                            <li>{{ lang('privacy.delete.warning.items.3', [], 'security') }}</li>
                        </ul>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('account.delete') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash-alt mr-1.5"></i> {{ lang('privacy.delete.button', [], 'security') }}
                        </a>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-right">
                        {{ lang('privacy.delete.confirmation', [], 'security') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Recovery Email Modal -->
    <div id="add-recovery-email-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ lang('recovery.modal.title', [], 'security') }}</h3>
                    <button type="button"
                        onclick="document.getElementById('add-recovery-email-modal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('account.security.add-recovery-email') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="recovery_email"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ lang('recovery.modal.label', [], 'security') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="recovery_email" id="recovery_email" required
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="{{ lang('recovery.modal.placeholder', [], 'security') }}">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">{{ lang('recovery.modal.helper', [], 'security') }}</p>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button"
                            onclick="document.getElementById('add-recovery-email-modal').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ lang('recovery.modal.cancel', [], 'security') }}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ lang('recovery.modal.add', [], 'security') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Disable 2FA Modal -->
    <div id="disable-2fa-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ lang('disable_2fa.title', [], 'security') }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ lang('disable_2fa.description', [], 'security') }}</p>

                <form action="{{ route('account.security.disable-2fa') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="code"
                            class="block text-sm font-medium text-gray-700">{{ lang('disable_2fa.code.label', [], 'security') }}</label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="text" name="code" id="code" required
                                class="block w-full pl-10 pr-3 py-2 text-sm border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
                                placeholder="{{ lang('disable_2fa.code.placeholder', [], 'security') }}" pattern="[0-9]{6}"
                                maxlength="6">
                        </div>
                        @error('code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('disable-2fa-modal').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            {{ lang('disable_2fa.buttons.cancel', [], 'security') }}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            {{ lang('disable_2fa.buttons.disable', [], 'security') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script>
        // Check for new login and clear security tab preference
        @if(session('new_login'))
            localStorage.removeItem('securityTab');
            @php session()->forget('new_login'); @endphp
        @endif

        // Fallback JavaScript for tab switching if Alpine.js fails to load
        document.addEventListener('DOMContentLoaded', function () {
            // Check if Alpine is not loaded after 500ms
            setTimeout(function () {
                if (typeof Alpine === 'undefined') {
                    console.log('Alpine.js not loaded, using fallback');

                    // Get all tab buttons and content sections
                    const tabButtons = document.querySelectorAll('[data-tab]');
                    const tabContents = document.querySelectorAll('[data-tab-content]');

                    // Get saved tab from localStorage or default to authentication
                    const savedTab = localStorage.getItem('securityTab') || 'authentication';

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
                        localStorage.setItem('securityTab', tabId);
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

        // Password Toggle Functionality
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.password-toggle').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const input = this.closest('.relative').querySelector('input');
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });

        // Password Strength Checker
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const meter = document.getElementById('password-strength-meter');
            const strengthText = document.getElementById('password-strength-text');

            // Default - too weak
            let strength = 0;
            let strengthClass = 'bg-red-500';
            let text = 'Too Weak';

            // Check password strength
            if (password.length >= 8) {
                strength += 20;

                // Check for mixed case
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
                    strength += 20;
                }

                // Check for numbers
                if (password.match(/\d/)) {
                    strength += 20;
                }

                // Check for special characters
                if (password.match(/[^a-zA-Z0-9]/)) {
                    strength += 20;
                }

                // Check password length
                if (password.length >= 12) {
                    strength += 20;
                }
            }

            // Update the strength text and color
            if (strength >= 80) {
                strengthClass = 'bg-green-500';
                text = 'Very Strong';
            } else if (strength >= 60) {
                strengthClass = 'bg-green-400';
                text = 'Strong';
            } else if (strength >= 40) {
                strengthClass = 'bg-yellow-500';
                text = 'Medium';
            } else if (strength >= 20) {
                strengthClass = 'bg-orange-500';
                text = 'Weak';
            }

            // Update UI
            meter.className = `h-1.5 rounded-full transition-all duration-300 ${strengthClass}`;
            meter.style.width = `${strength}%`;
            strengthText.textContent = text;

            // Update text color based on strength
            if (strength >= 60) {
                strengthText.className = 'text-xs font-medium text-green-600';
            } else if (strength >= 40) {
                strengthText.className = 'text-xs font-medium text-yellow-600';
            } else if (strength >= 20) {
                strengthText.className = 'text-xs font-medium text-orange-600';
            } else {
                strengthText.className = 'text-xs font-medium text-red-600';
            }
        }
    </script>
@endsection