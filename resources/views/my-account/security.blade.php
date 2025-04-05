@extends('my-account.layout')

@section('title', 'Security Settings')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-medium leading-6 text-gray-900">
        Account Security
    </h3>
    <p class="mt-2 text-sm text-gray-600">
        Manage your security settings, activity, and data preferences.
    </p>
</div>

<!-- Tabs Navigation -->
<div class="mb-6 border-b border-gray-200">
    <div class="flex -mb-px" x-data="{ activeTab: localStorage.getItem('securityActiveTab') || 'authentication' }">
        <button @click="activeTab = 'authentication'; localStorage.setItem('securityActiveTab', 'authentication')" 
                :class="activeTab === 'authentication' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center">
            <i class="fas fa-shield-alt mr-2"></i> Authentication
        </button>
        
        <button @click="activeTab = 'activity'; localStorage.setItem('securityActiveTab', 'activity')"
                :class="activeTab === 'activity' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center">
            <i class="fas fa-history mr-2"></i> Account Activity
        </button>
        
        <button @click="activeTab = 'recovery'; localStorage.setItem('securityActiveTab', 'recovery')"
                :class="activeTab === 'recovery' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center">
            <i class="fas fa-life-ring mr-2"></i> Recovery Options
        </button>
        
        <button @click="activeTab = 'privacy'; localStorage.setItem('securityActiveTab', 'privacy')"
                :class="activeTab === 'privacy' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none flex items-center">
            <i class="fas fa-user-shield mr-2"></i> Privacy & Data
        </button>
    </div>
</div>

<!-- Tab Content -->
<div x-data="{ activeTab: localStorage.getItem('securityActiveTab') || 'authentication' }">
    <!-- Authentication Tab Content -->
    <div x-show="activeTab === 'authentication'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <i class="fas fa-lock text-blue-500 mr-2"></i>
                    Two-Factor Authentication (2FA)
                </h4>
                <div>
                    @if($user->hasTwoFactorEnabled())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Enabled
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i> Not enabled
                        </span>
                    @endif
                </div>
            </div>
            
            <p class="text-sm text-gray-600 mb-5">
                Two-factor authentication adds an extra layer of security to your account by requiring both your password and a verification code from your mobile phone to log in.
            </p>
            
            <div class="flex justify-end">
                @if($user->hasTwoFactorEnabled())
                    <div class="flex space-x-3">
                        <button type="button" onclick="document.getElementById('disable-2fa-modal').classList.remove('hidden')"
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-red-600 bg-white border border-red-300 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <i class="fas fa-times-circle mr-1.5"></i> Disable 2FA
                        </button>
                        <form action="{{ route('account.security.regenerate-recovery-codes') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-sync-alt mr-1.5"></i> Regenerate Recovery Codes
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('account.security.setup-2fa') }}"
                        class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-shield-alt mr-1.5"></i> Enable 2FA
                    </a>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Activity Tab Content -->
    <div x-show="activeTab === 'activity'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <h4 class="text-base font-medium text-gray-900 flex items-center mb-4">
                <i class="fas fa-history text-blue-500 mr-2"></i>
                Recent Login Activity
            </h4>
            
            <p class="text-sm text-gray-600 mb-5">
                Monitor your recent login activity to ensure your account hasn't been compromised. We recommend reviewing this regularly.
            </p>
            
            <div class="overflow-hidden rounded-md border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($user->getRecentLoginActivities() as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">{{ $activity->created_at->format('d-m-Y H:i') }}</td>
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-{{ $activity->status === 'success' ? 'check' : 'times' }}-circle mr-1"></i>
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center py-4">
                                    <i class="fas fa-search text-gray-300 text-3xl mb-2"></i>
                                    <p>No login activity found</p>
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
    <div x-show="activeTab === 'recovery'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <h4 class="text-base font-medium text-gray-900 flex items-center mb-4">
                <i class="fas fa-life-ring text-blue-500 mr-2"></i>
                Account Recovery Options
            </h4>
            
            <p class="text-sm text-gray-600 mb-5">
                Set up recovery options to help you regain access to your account if you lose your password or can't log in.
            </p>
            
            <div class="border border-gray-200 rounded-md p-4 hover:bg-gray-50 transition-colors duration-150">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-sm font-medium text-gray-900 flex items-center">
                            <i class="fas fa-envelope text-gray-500 mr-1.5"></i>
                            Recovery Email
                        </h5>
                        <p class="text-xs text-gray-500 mt-1">A backup email address for account recovery</p>
                    </div>
                    @if($user->recovery_email)
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600">{{ $user->recovery_email }}</span>
                            @if($user->recovery_email_verified)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @endif
                            <form action="{{ route('account.security.remove-recovery-email') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center text-xs text-red-600 hover:text-red-800">
                                    <i class="fas fa-times mr-1"></i> Remove
                                </button>
                            </form>
                        </div>
                    @else
                        <button type="button" onclick="document.getElementById('add-recovery-email-modal').classList.remove('hidden')"
                            class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none">
                            <i class="fas fa-plus mr-1"></i> Add
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Privacy & Data Tab Content -->
    <div x-show="activeTab === 'privacy'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="space-y-6">
            <!-- GDPR Data Export -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h4 class="text-base font-medium text-gray-900 flex items-center mb-4">
                    <i class="fas fa-download text-blue-500 mr-2"></i>
                    Data Export (GDPR)
                </h4>
                
                <p class="text-sm text-gray-600 mb-5">
                    Under the GDPR (General Data Protection Regulation), you have the right to download your personal data in a machine-readable format.
                </p>
                
                <div class="p-4 bg-blue-50 rounded-md mb-5 border border-blue-100">
                    <h5 class="text-sm font-medium text-blue-800 mb-2">Your data export will include:</h5>
                    <ul class="text-xs text-blue-700 list-disc pl-5 space-y-1">
                        <li>Personal account information</li>
                        <li>Login activity history</li>
                        <li>Connected services and applications</li>
                        <li>Security settings</li>
                    </ul>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('account.export-data') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-file-download mr-1.5"></i> Download My Data
                    </a>
                </div>
                <p class="text-xs text-gray-500 mt-2 text-right">The data will be exported as a JSON file that you can download.</p>
            </div>
            
            <!-- Account Deletion -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h4 class="text-base font-medium text-gray-900 flex items-center mb-4">
                    <i class="fas fa-user-slash text-red-500 mr-2"></i>
                    Delete Account (GDPR)
                </h4>
                
                <p class="text-sm text-gray-600 mb-5">
                    Under the GDPR (General Data Protection Regulation), you have the right to request the deletion of your personal data.
                </p>
                
                <div class="p-4 bg-red-50 rounded-md mb-5 border border-red-100">
                    <h5 class="text-sm font-medium text-red-800 mb-2">Important information about account deletion:</h5>
                    <ul class="text-xs text-red-700 list-disc pl-5 space-y-1">
                        <li>This action <strong>cannot be undone</strong>. All your personal data will be permanently deleted or anonymized.</li>
                        <li>You will lose access to all connected services and applications.</li>
                        <li>Some data may be retained for legal compliance purposes, but will be fully anonymized.</li>
                        <li>Any active subscriptions will be canceled.</li>
                    </ul>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('account.delete') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash-alt mr-1.5"></i> Delete My Account
                    </a>
                </div>
                <p class="text-xs text-gray-500 mt-2 text-right">You'll need to confirm this action by entering your password.</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Recovery Email Modal -->
<div id="add-recovery-email-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add Recovery Email</h3>
                <button type="button" onclick="document.getElementById('add-recovery-email-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('account.security.add-recovery-email') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="recovery_email" class="block text-sm font-medium text-gray-700 mb-1">Recovery Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="recovery_email" id="recovery_email" required
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="name@example.com">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">We'll send a verification link to this email address.</p>
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="document.getElementById('add-recovery-email-modal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Add Recovery Email
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
            <h3 class="text-lg font-medium text-gray-900 mb-4">Disable Two-Factor Authentication</h3>
            <p class="text-sm text-gray-500 mb-4">Please enter your 2FA verification code to disable two-factor authentication.</p>

            <form action="{{ route('account.security.disable-2fa') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input type="text" name="code" id="code" required
                            class="block w-full pl-10 pr-3 py-2 text-sm border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
                            placeholder="Enter 6-digit code"
                            pattern="[0-9]{6}"
                            maxlength="6">
                    </div>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('disable-2fa-modal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Disable 2FA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
@endsection
