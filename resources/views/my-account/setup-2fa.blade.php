@extends('my-account.layout')

@section('title', lang('title', [], 'setup-2fa'))

@section('header', lang('header', [], 'setup-2fa'))
@section('subheader', lang('subheader', [], 'setup-2fa'))

@section('content')
<!-- Step 1: Install App -->
<div class="mb-8">
    <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
        <div class="flex items-center justify-center h-8 w-8 rounded-md bg-blue-100 text-blue-600 mr-3">
            <i class="fas fa-mobile-alt"></i>
        </div>
        {{ lang('steps.install_app.title', [], 'setup-2fa') }}
    </h2>
    <p class="text-sm text-gray-600 ml-11">
        {{ lang('steps.install_app.description', [], 'setup-2fa') }}
    </p>
</div>

<!-- Step 2: Scan QR Code -->
<div class="mb-8">
    <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
        <div class="flex items-center justify-center h-8 w-8 rounded-md bg-blue-100 text-blue-600 mr-3">
            <i class="fas fa-qrcode"></i>
        </div>
        {{ lang('steps.scan_qr.title', [], 'setup-2fa') }}
    </h2>
    <p class="text-sm text-gray-600 mb-6 ml-11">
        {{ lang('steps.scan_qr.description', [], 'setup-2fa') }}
    </p>
    
    <div class="flex justify-center mb-6">
        <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
            {!! QrCode::size(200)->margin(1)->generate($qrCodeUrl) !!}
        </div>
    </div>
    
    <div class="ml-11">
        <p class="text-sm text-gray-600 mb-3">
            {{ lang('steps.scan_qr.manual_entry', [], 'setup-2fa') }}
        </p>
        <div class="bg-gray-50 border border-gray-200 rounded-md p-4 max-w-md">
            <code class="text-sm font-mono text-gray-900 break-all">{{ $secret }}</code>
        </div>
    </div>
</div>

<!-- Step 3: Verify Setup -->
<div class="mb-8">
    <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
        <div class="flex items-center justify-center h-8 w-8 rounded-md bg-blue-100 text-blue-600 mr-3">
            <i class="fas fa-key"></i>
        </div>
        {{ lang('steps.verify.title', [], 'setup-2fa') }}
    </h2>
    <p class="text-sm text-gray-600 mb-6 ml-11">
        {{ lang('steps.verify.description', [], 'setup-2fa') }}
    </p>
</div>

<!-- Verification Form -->
<form action="{{ route('account.security.confirm-2fa') }}" method="POST" class="space-y-6">
    @csrf
    
    <div>
        <label for="code" class="block text-sm font-medium text-gray-700">
            {{ lang('form.code_label', [], 'setup-2fa') }}
        </label>
        <div class="mt-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-shield-alt text-gray-400"></i>
            </div>
            <input 
                type="text" 
                name="code" 
                id="code" 
                required
                class="w-full px-3 py-2.5 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none {{ $errors->has('code') ? 'border-red-500' : 'border-gray-300' }}"
                placeholder="{{ lang('form.code_placeholder', [], 'setup-2fa') }}"
                pattern="[0-9]{6}"
                maxlength="6"
                autocomplete="one-time-code">
        </div>
        @error('code')
            <p class="mt-1 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
            </p>
        @enderror
    </div>
    
    <div class="flex items-center justify-end">
        <button 
            type="submit"
            class="inline-flex items-center px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-colors duration-150">
            <i class="fas fa-check mr-2"></i>
            {{ lang('form.verify_button', [], 'setup-2fa') }}
        </button>
    </div>
</form>

<!-- Warning Section -->
<div class="rounded-md bg-yellow-50 p-4 mt-8">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">{{ lang('warning.title', [], 'setup-2fa') }}</h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>
                    {{ lang('warning.description', [], 'setup-2fa') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
