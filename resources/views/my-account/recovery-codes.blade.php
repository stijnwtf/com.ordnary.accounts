@extends('my-account.layout')

@section('title', 'Recovery Codes')

@section('header', 'Recovery Codes')
@section('subheader', 'Store these recovery codes in a secure place. You can use them to access your account if you lose your authenticator device.')

@section('content')
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Your Recovery Codes</h3>
        <p class="text-sm text-gray-600">These codes can be used to access your account if you lose your authenticator
            device. Each code can only be used once.</p>
    </div>

    <div class="space-y-6 pb-8">
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <i class="fas fa-key mr-2 text-blue-500"></i>
                    Recovery Codes
                </h4>
            </div>
            <div class="p-4">
                <div class="bg-gray-50 p-4 rounded-md">
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($codes as $code)
                            <div class="font-mono text-sm bg-white p-2 rounded border border-gray-200">{{ $code }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Store these recovery codes in a secure place. You can use them to access your account if you lose
                        your authenticator device.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('account.security') }}"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Back to Security Settings
            </a>
        </div>
    </div>
@endsection