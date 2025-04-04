@extends('my-account.layout')

@section('title', 'Security Settings')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-medium leading-6 text-gray-900">
        Account Security
    </h3>
    <p class="mt-2 text-sm text-gray-600">
        Enhance the security of your Numblio account with additional protection measures.
    </p>
</div>

<!-- Two-Factor Authentication Section -->
<div class="pt-4 pb-8 border-b border-gray-200">
    <h4 class="text-base font-medium text-gray-900 mb-3">
        Two-Factor Authentication (2FA)
    </h4>
    <p class="text-sm text-gray-600 mb-4">
        Add an extra layer of security to your account by requiring both your password and a verification code from your mobile phone to log in.
    </p>
    <div class="flex items-center justify-between">
        <div>
            @if($user->hasTwoFactorEnabled())
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Enabled
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Not enabled
                </span>
            @endif
        </div>
        <div class="flex space-x-2">
            @if($user->hasTwoFactorEnabled())
                <button type="button" onclick="document.getElementById('disable-2fa-modal').classList.remove('hidden')"
                    class="px-4 py-2 rounded text-sm font-medium bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Disable 2FA
                </button>
                <form action="{{ route('account.security.regenerate-recovery-codes') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Regenerate Recovery Codes
                    </button>
                </form>
            @else
                <a href="{{ route('account.security.setup-2fa') }}"
                    class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Enable 2FA
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Login History Section -->
<div class="py-8 border-b border-gray-200">
    <h4 class="text-base font-medium text-gray-900 mb-3">
        Recent Login Activity
    </h4>
    <p class="text-sm text-gray-600 mb-4">
        Monitor your recent login activity to ensure your account hasn't been compromised.
    </p>
    <div class="overflow-hidden rounded-md border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($user->getRecentLoginActivities() as $activity)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ $activity->created_at->format('d-m-Y H:i') }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">
                        <div class="flex items-center">
                            {{ $activity->browser }} on {{ $activity->platform }}
                        </div>
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ $activity->location }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-xs text-gray-500">
                        No login activity found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Account Recovery Section -->
<div class="py-8 border-b border-gray-200">
    <h4 class="text-base font-medium text-gray-900 mb-3">
        Account Recovery Options
    </h4>
    <p class="text-sm text-gray-600 mb-4">
        Set up recovery options to help you regain access to your account if you lose your password.
    </p>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h5 class="text-sm font-medium text-gray-900">Recovery Email</h5>
                <p class="text-xs text-gray-500">A backup email address for account recovery</p>
            </div>
            @if($user->recovery_email)
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">{{ $user->recovery_email }}</span>
                    @if($user->recovery_email_verified)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @endif
                    <form action="{{ route('account.security.remove-recovery-email') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-red-600 hover:text-red-800">
                            Remove
                        </button>
                    </form>
                </div>
            @else
                <button type="button" onclick="document.getElementById('add-recovery-email-modal').classList.remove('hidden')"
                    class="px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 focus:outline-none">
                    Add
                </button>
            @endif
        </div>
    </div>
</div>

<!-- GDPR Data Portability Section -->
<div class="py-8 border-b border-gray-200">
    <h4 class="text-base font-medium text-gray-900 mb-3">
        Data Export (GDPR Data Portability)
    </h4>
    <p class="text-sm text-gray-600 mb-4">
        Under the GDPR (General Data Protection Regulation), you have the right to download your personal data in a machine-readable format.
    </p>

    <div class="p-4 bg-blue-50 rounded-md mb-4">
        <h5 class="text-sm font-medium text-blue-800 mb-2">Your data export will include:</h5>
        <ul class="text-xs text-blue-700 list-disc pl-5 space-y-1">
            <li>Personal account information</li>
            <li>Login activity history</li>
            <li>Connected services and applications</li>
            <li>Security settings</li>
        </ul>
    </div>

    <a href="{{ route('account.export-data') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        Download My Data
    </a>
    <p class="text-xs text-gray-500 mt-2">The data will be exported as a JSON file that you can download.</p>
</div>

<!-- GDPR Right to be Forgotten Section -->
<div class="py-8">
    <h4 class="text-base font-medium text-gray-900 mb-3">
        Delete Account (GDPR Right to be Forgotten)
    </h4>
    <p class="text-sm text-gray-600 mb-4">
        Under the GDPR (General Data Protection Regulation), you have the right to request the deletion of your personal data.
    </p>

    <div class="p-4 bg-red-50 rounded-md mb-4">
        <h5 class="text-sm font-medium text-red-800 mb-2">Important information about account deletion:</h5>
        <ul class="text-xs text-red-700 list-disc pl-5 space-y-1">
            <li>This action <strong>cannot be undone</strong>. All your personal data will be permanently deleted or anonymized.</li>
            <li>You will lose access to all connected services and applications.</li>
            <li>Some data may be retained for legal compliance purposes, but will be fully anonymized.</li>
            <li>Any active subscriptions will be canceled.</li>
        </ul>
    </div>

    <a href="{{ route('account.delete') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        Delete My Account
    </a>
    <p class="text-xs text-gray-500 mt-2">You'll need to confirm this action by entering your password.</p>
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
                    <input type="email" name="recovery_email" id="recovery_email" required
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="name@example.com">
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
                    <div class="mt-1">
                        <input type="text" name="code" id="code" required
                            class="block w-full px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
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
