@extends('my-account.layout')

@section('title', 'Set Up Two-Factor Authentication')

@section('header', 'Set Up Two-Factor Authentication')
@section('subheader', 'Add an extra layer of security to your account by requiring both your password and a verification code from your mobile phone to log in.')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-2">Two-Factor Authentication Setup</h3>
    <p class="text-sm text-gray-600">Follow the steps below to set up two-factor authentication for your account.</p>
</div>

<div class="space-y-6">
    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h4 class="text-base font-medium text-gray-900 flex items-center">
                <i class="fas fa-mobile-alt mr-2 text-blue-500"></i>
                Step 1: Install an Authenticator App
            </h4>
        </div>
        <div class="p-4">
            <p class="text-sm text-gray-600">Install an authenticator app like Google Authenticator, Authy, or Microsoft Authenticator on your mobile device.</p>
        </div>
    </div>

    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h4 class="text-base font-medium text-gray-900 flex items-center">
                <i class="fas fa-qrcode mr-2 text-blue-500"></i>
                Step 2: Scan the QR Code
            </h4>
        </div>
        <div class="p-4">
            <p class="text-sm text-gray-600 mb-4">Open your authenticator app and scan the QR code below:</p>
            <div class="flex justify-center">
                <img src="data:image/png;base64,{{ base64_encode(QrCode::size(200)->generate($qrCodeUrl)) }}" alt="QR Code" class="border border-gray-200 rounded-lg p-2">
            </div>
        </div>
    </div>

    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h4 class="text-base font-medium text-gray-900 flex items-center">
                <i class="fas fa-key mr-2 text-blue-500"></i>
                Step 3: Enter the Code
            </h4>
        </div>
        <div class="p-4">
            <p class="text-sm text-gray-600 mb-4">Enter the 6-digit code from your authenticator app to verify the setup:</p>
            <form action="{{ route('account.security.confirm-2fa') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                    <div class="mt-1">
                        <input type="text" name="code" id="code" required
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            placeholder="Enter 6-digit code">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Verify and Enable 2FA
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Make sure to save your recovery codes in a secure place. You'll need them if you lose access to your authenticator app.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 