@extends('my-account.layout')

@section('title', 'Set Up Two-Factor Authentication')

@section('header', 'Set Up Two-Factor Authentication')
@section('subheader', 'Add an extra layer of security to your account by requiring both your password and a verification code from your mobile phone to log in.')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="space-y-8">
        <!-- Setup Steps -->
        <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
            <!-- Step 1 -->
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                            <i class="fas fa-mobile-alt text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Install an Authenticator App</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Install an authenticator app like Google Authenticator, Authy, or Microsoft Authenticator on your mobile device.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                            <i class="fas fa-qrcode text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Scan the QR Code</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Open your authenticator app and scan the QR code below:
                        </p>
                        <div class="mt-4 flex justify-center">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <img src="data:image/png;base64,{{ base64_encode(QrCode::size(200)->margin(1)->generate($qrCodeUrl)) }}" alt="QR Code">
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-gray-500">
                            If you can't scan the QR code, you can manually enter this code in your authenticator app:
                            <code class="ml-2 px-2 py-1 bg-gray-100 rounded text-sm font-mono">{{ $user->two_factor_secret }}</code>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                            <i class="fas fa-key text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Verify the Setup</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Enter the 6-digit code from your authenticator app to verify the setup:
                        </p>
                        <form action="{{ route('account.security.confirm-2fa') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="max-w-xs">
                                <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                                <div class="mt-1">
                                    <input type="text" name="code" id="code" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Enter 6-digit code"
                                        pattern="[0-9]{6}"
                                        maxlength="6">
                                </div>
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4 flex justify-end">
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

        <!-- Warning Alert -->
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
</div>
@endsection 