@extends('my-account.layout')

@section('title', 'Security Settings')

@section('header', 'Account Security')
@section('subheader', 'Enhance the security of your Numblio account with additional protection measures.')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-2">Account security</h3>
    <p class="text-sm text-gray-600">Enhance the security of your Numblio account with additional protection measures.</p>
</div>

<!-- Two-Factor Authentication Card -->
<div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
    <div class="p-4 bg-gray-50 border-b border-gray-200">
        <h4 class="text-base font-medium text-gray-900 flex items-center">
            <i class="fas fa-shield-alt mr-2 text-blue-500"></i>
            Two-Factor Authentication (2FA)
        </h4>
    </div>
    <div class="p-4">
        <p class="text-sm text-gray-600 mb-4">Add an extra layer of security to your account by requiring both your password and a verification code from your mobile phone to log in.</p>
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-times-circle mr-1"></i> Not enabled
                </span>
            </div>
            <button class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-plus-circle mr-1"></i> Enable 2FA
            </button>
        </div>
    </div>
</div>

<!-- Login History Card -->
<div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
    <div class="p-4 bg-gray-50 border-b border-gray-200">
        <h4 class="text-base font-medium text-gray-900 flex items-center">
            <i class="fas fa-history mr-2 text-blue-500"></i>
            Recent login activity
        </h4>
    </div>
    <div class="p-4">
        <p class="text-sm text-gray-600 mb-4">Monitor your recent login activity to ensure your account hasn't been compromised.</p>
        <div class="overflow-hidden rounded-lg border border-gray-200">
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
                                @if($activity->device_type === 'Mobile')
                                    <i class="fas fa-mobile-alt mr-1 text-gray-400"></i>
                                @elseif($activity->device_type === 'Tablet')
                                    <i class="fas fa-tablet-alt mr-1 text-gray-400"></i>
                                @else
                                    <i class="fas fa-laptop mr-1 text-gray-400"></i>
                                @endif
                                {{ $activity->browser }} on {{ $activity->platform }}
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ $activity->location }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-{{ $activity->status === 'success' ? 'check' : 'times' }}-circle mr-1"></i>
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
</div>

<!-- Account Recovery -->
<div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
    <div class="p-4 bg-gray-50 border-b border-gray-200">
        <h4 class="text-base font-medium text-gray-900 flex items-center">
            <i class="fas fa-life-ring mr-2 text-blue-500"></i>
            Account Recovery Options
        </h4>
    </div>
    <div class="p-4">
        <p class="text-sm text-gray-600 mb-4">Set up recovery options to help you regain access to your account if you lose your password.</p>
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
                                <i class="fas fa-check-circle mr-1"></i> Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        @endif
                        <form action="{{ route('account.security.remove-recovery-email') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </form>
                    </div>
                @else
                    <button type="button" onclick="document.getElementById('add-recovery-email-modal').classList.remove('hidden')"
                        class="px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 focus:outline-none">
                        <i class="fas fa-plus-circle mr-1"></i> Add
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Recovery Email Modal -->
<div id="add-recovery-email-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add Recovery Email</h3>
            <form action="{{ route('account.security.add-recovery-email') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="recovery_email" class="block text-sm font-medium text-gray-700">Recovery Email Address</label>
                    <input type="email" name="recovery_email" id="recovery_email" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="Enter your recovery email">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('add-recovery-email-modal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
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
@endsection
