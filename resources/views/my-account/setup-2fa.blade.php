@extends('my-account.layout')

@section('title', 'Set Up Two-Factor Authentication')

@section('header', 'Set Up Two-Factor Authentication')
@section('subheader', 'Add an extra layer of security to your account by requiring both your password and a verification code from your mobile phone to log in.')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <!-- Setup Steps -->
    <div class="mb-8 space-y-8">
        <!-- Step 1 -->
        <div class="flex items-start space-x-6">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-50 text-blue-600 ring-2 ring-blue-100">
                    <i class="fas fa-mobile-alt text-xl"></i>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Install an Authenticator App</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Install an authenticator app like Google Authenticator, Authy, or Microsoft Authenticator on your mobile device.
                </p>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="flex items-start space-x-6">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-50 text-blue-600 ring-2 ring-blue-100">
                    <i class="fas fa-qrcode text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">Scan the QR Code</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Open your authenticator app and scan the QR code below:
                </p>
                <div class="mt-4 flex justify-center">
                    <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                        {!! QrCode::size(200)->margin(1)->generate($qrCodeUrl) !!}
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-600">
                        If you can't scan the QR code, you can manually enter this code in your authenticator app:
                    </p>
                    <div class="mt-2">
                        <code class="block px-4 py-2 bg-gray-50 rounded-md text-sm font-mono text-gray-900 border border-gray-200">{{ $user->two_factor_secret }}</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="flex items-start space-x-6">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-50 text-blue-600 ring-2 ring-blue-100">
                    <i class="fas fa-key text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">Verify the Setup</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Enter the 6-digit code from your authenticator app to verify the setup:
                </p>
                <form action="{{ route('account.security.confirm-2fa') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="max-w-xs">
                        <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                        <div class="mt-1">
                            <input type="text" name="code" id="code" required
                                class="block w-full px-4 py-2 text-base border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter 6-digit code"
                                pattern="[0-9]{6}"
                                maxlength="6">
                        </div>
                        @error('code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Verify and Enable 2FA
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
