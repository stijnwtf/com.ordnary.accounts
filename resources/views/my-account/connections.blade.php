@extends('my-account.layout')

@section('title', 'Connected Services')

@section('header', 'Connected Services')
@section('subheader', 'Manage the services and applications connected to your Numblio account.')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-2">Connected services</h3>
    <p class="text-sm text-gray-600">Manage the services and applications connected to your Numblio account.</p>
</div>

<!-- Connected Services Overview -->
<div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
    <div class="flex items-center">
        <div class="mr-4">
            <i class="fas fa-link text-blue-500 text-xl"></i>
        </div>
        <div>
            <h4 class="text-md font-semibold text-gray-900">Connected applications</h4>
            <p class="text-sm text-gray-600">View and manage third-party applications that have access to your Numblio account.</p>
        </div>
    </div>
</div>

<!-- Connected Apps List -->
<div class="space-y-4">
    <!-- Google -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="https://cdn.cdnlogo.com/logos/g/35/google-icon.svg" alt="Google" class="h-8 w-8 mr-3">
                    <div>
                        <h5 class="text-sm font-medium text-gray-900">Google</h5>
                        <p class="text-xs text-gray-500">Connected on {{ now()->subMonths(rand(1, 6))->format('d-m-Y') }}</p>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Connected
                    </span>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    <p>This connection allows you to log in using your Google account.</p>
                </div>
                <button class="px-3 py-1 rounded text-xs font-medium text-red-600 hover:text-red-800 focus:outline-none border border-red-200 hover:bg-red-50">
                    <i class="fas fa-unlink mr-1"></i> Disconnect
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add More Connections Button -->
<div class="pb-6 flex justify-end mt-6">
    <button class="flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm">
        <i class="fas fa-plus-circle mr-2"></i> Connect more services
    </button>
</div>
@endsection 