@extends('my-account.layout')

@section('title', lang('title', [], 'setup-2fa'))

@section('content')
    <div class="mb-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900">
            {{ lang('header', [], 'setup-2fa') }}
        </h3>
        <p class="mt-2 text-sm text-gray-600">
            {{ lang('subheader', [], 'setup-2fa') }}
        </p>
    </div>

    <!-- Step 1: Install App -->
    <div class="mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="fas fa-mobile-alt text-blue-600"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-base font-medium text-gray-900 mb-2">
                    {{ lang('steps.install_app.title', [], 'setup-2fa') }}
                </h4>
                <p class="text-sm text-gray-600">
                    {{ lang('steps.install_app.description', [], 'setup-2fa') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Step 2: Scan QR Code -->
    <div class="mb-6 pb-6 border-b border-gray-200">
        <div class="flex items-start mb-6">
            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="fas fa-qrcode text-blue-600"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-base font-medium text-gray-900 mb-2">
                    {{ lang('steps.scan_qr.title', [], 'setup-2fa') }}
                </h4>
                <p class="text-sm text-gray-600">
                    {{ lang('steps.scan_qr.description', [], 'setup-2fa') }}
                </p>
            </div>
        </div>

        <div class="flex justify-center mb-6">
            <div class="p-6 bg-white rounded-lg border-2 border-gray-200">
                {!! QrCode::size(200)->margin(1)->generate($qrCodeUrl) !!}
            </div>
        </div>

        <div class="max-w-lg mx-auto">
            <p class="text-sm text-gray-600 mb-3 text-center">
                {{ lang('steps.scan_qr.manual_entry', [], 'setup-2fa') }}
            </p>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <code class="text-sm font-mono text-gray-900 break-all block text-center">{{ $secret }}</code>
            </div>
        </div>
    </div>

    <!-- Step 3: Verify Setup -->
    <div class="mb-6">
        <div class="flex items-start mb-6">
            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="fas fa-key text-blue-600"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-base font-medium text-gray-900 mb-2">
                    {{ lang('steps.verify.title', [], 'setup-2fa') }}
                </h4>
                <p class="text-sm text-gray-600">
                    {{ lang('steps.verify.description', [], 'setup-2fa') }}
                </p>
            </div>
        </div>

        <!-- Verification Form -->
        <form action="{{ route('account.security.confirm-2fa') }}" method="POST" class="space-y-6 max-w-lg mx-auto">
            @csrf

            <div>
                <label for="code" class="block text-sm font-medium text-gray-900 mb-2">
                    {{ lang('form.code_label', [], 'setup-2fa') }}
                </label>
                <input type="text" name="code" id="code" required
                    class="w-full px-4 py-3 text-center text-lg tracking-widest border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('code') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="000000" pattern="[0-9]{6}" maxlength="6"
                    autocomplete="one-time-code" inputmode="numeric">
                @error('code')
                    <p class="mt-2 text-sm text-red-600 flex items-center justify-center">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex justify-center pt-4">
                <button type="submit"
                    class="inline-flex items-center px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm font-medium transition-colors duration-150">
                    <i class="fas fa-check mr-2"></i>
                    {{ lang('form.verify_button', [], 'setup-2fa') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Warning Section -->
    <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-4 mt-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800 mb-1">{{ lang('warning.title', [], 'setup-2fa') }}</h3>
                <p class="text-sm text-yellow-700">
                    {{ lang('warning.description', [], 'setup-2fa') }}
                </p>
            </div>
        </div>
    </div>
@endsection