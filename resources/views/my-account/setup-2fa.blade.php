@extends('my-account.layout')

@section('title', 'Set Up Two-Factor Authentication')

@section('header', 'Set Up Two-Factor Authentication')
@section('subheader', 'Add an extra layer of security to your account by requiring both your password and a verification code from your mobile phone to log in.')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Setup Steps -->
    <div class="bg-white shadow-sm rounded-xl overflow-hidden">
        <!-- Step 1 -->
        <div class="p-8 border-b border-gray-100">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-blue-50 text-blue-600 ring-4 ring-blue-50">
                        <i class="fas fa-mobile-alt text-2xl"></i>
                    </div>
                </div>
                <div class="ml-6">
                    <h3 class="text-xl font-semibold text-gray-900">Install an Authenticator App</h3>
                    <p class="mt-2 text-base text-gray-600">
                        Install an authenticator app like Google Authenticator, Authy, or Microsoft Authenticator on your mobile device.
                    </p>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="p-8 border-b border-gray-100">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-blue-50 text-blue-600 ring-4 ring-blue-50">
                        <i class="fas fa-qrcode text-2xl"></i>
                    </div>
                </div>
                <div class="ml-6">
                    <h3 class="text-xl font-semibold text-gray-900">Scan the QR Code</h3>
                    <p class="mt-2 text-base text-gray-600">
                        Open your authenticator app and scan the QR code below:
                    </p>
                    <div class="mt-6 flex justify-center">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 ring-4 ring-gray-50">
                            {!! QrCode::size(200)->margin(1)->generate($qrCodeUrl) !!}
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">
                            If you can't scan the QR code, you can manually enter this code in your authenticator app:
                        </p>
                        <code class="mt-2 block px-3 py-2 bg-white rounded-md text-sm font-mono text-gray-900 border border-gray-200">{{ $user->two_factor_secret }}</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="p-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-blue-50 text-blue-600 ring-4 ring-blue-50">
                        <i class="fas fa-key text-2xl"></i>
                    </div>
                </div>
                <div class="ml-6">
                    <h3 class="text-xl font-semibold text-gray-900">Verify the Setup</h3>
                    <p class="mt-2 text-base text-gray-600">
                        Enter the 6-digit code from your authenticator app to verify the setup:
                    </p>
                    <form action="{{ route('account.security.confirm-2fa') }}" method="POST" class="mt-6">
                        @csrf
                        <div class="max-w-xs">
                            <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                            <div class="mt-2">
                                <input type="text" name="code" id="code" required
                                    class="shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg"
                                    placeholder="Enter 6-digit code"
                                    pattern="[0-9]{6}"
                                    maxlength="6">
                            </div>
                            @error('code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Verify and Enable 2FA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Warning Alert -->
    <div class="mt-12 mb-8">
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-base text-yellow-700">
                        Make sure to save your recovery codes in a secure place. You'll need them if you lose access to your authenticator app.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 