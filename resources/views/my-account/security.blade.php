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
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ now()->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-laptop mr-1 text-gray-400"></i> Chrome on macOS
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">Amsterdam, NL</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Current
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ now()->subDays(2)->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-mobile-alt mr-1 text-gray-400"></i> Mobile App
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">Amsterdam, NL</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-check-circle mr-1"></i> Success
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ now()->subDays(5)->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-laptop mr-1 text-gray-400"></i> Chrome on Windows
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">Rotterdam, NL</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-check-circle mr-1"></i> Success
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <button class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fas fa-list mr-1"></i> View all login activity
            </button>
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
                <button class="px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 focus:outline-none">
                    <i class="fas fa-plus-circle mr-1"></i> Add
                </button>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-sm font-medium text-gray-900">Recovery Phone</h5>
                    <p class="text-xs text-gray-500">A phone number for account recovery via SMS</p>
                </div>
                <button class="px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 focus:outline-none">
                    <i class="fas fa-plus-circle mr-1"></i> Add
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
