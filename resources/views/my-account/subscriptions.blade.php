@extends('my-account.layout')

@section('title', 'Subscriptions & Usage')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-medium leading-6 text-gray-900">
        Subscriptions & Usage
    </h3>
    <p class="mt-2 text-sm text-gray-600">
        Manage your Numblio product subscriptions and monitor your usage.
    </p>
</div>

@if(count($subscriptions) > 0)
    <div class="space-y-6">
        @foreach($subscriptions as $subscription)
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <!-- Subscription Header -->
                <div class="flex items-center justify-between border-b border-gray-200 bg-gradient-to-r from-white to-{{ $subscription['status'] === 'active' ? 'blue-50' : 'gray-50' }} px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-{{ $subscription['status'] === 'active' ? 'blue-100' : 'gray-100' }} rounded-full p-2">
                            <i class="fas fa-cube text-{{ $subscription['status'] === 'active' ? 'blue-500' : 'gray-500' }} text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-medium text-gray-900">{{ $subscription['product_name'] }}</h4>
                            <div class="flex items-center mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subscription['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas fa-{{ $subscription['status'] === 'active' ? 'check-circle' : 'clock' }} mr-1"></i>
                                    {{ ucfirst($subscription['status']) }}
                                </span>
                                <span class="text-xs text-gray-500 ml-3">
                                    {{ $subscription['plan'] }} • {{ $subscription['price'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($subscription['status'] === 'active')
                            <button class="px-3 py-1.5 rounded-md text-sm font-medium text-red-600 bg-white border border-red-300 hover:bg-red-50 focus:outline-none">
                                Cancel Subscription
                            </button>
                        @else
                            <button class="px-3 py-1.5 rounded-md text-sm font-medium text-blue-600 bg-white border border-blue-300 hover:bg-blue-50 focus:outline-none">
                                Reactivate
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Subscription Details -->
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Subscription Info -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-700 mb-3">Subscription Details</h5>
                            <div class="bg-gray-50 rounded-md p-4 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Started on</span>
                                    <span class="text-xs font-medium">{{ $subscription['started_at']->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Next billing</span>
                                    <span class="text-xs font-medium">{{ $subscription['next_billing']->format('d M Y') }}</span>
                                </div>
                                @if($subscription['expires_at'])
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Expires on</span>
                                    <span class="text-xs font-medium">{{ $subscription['expires_at']->format('d M Y') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="text-xs text-gray-500">Subscription ID</span>
                                    <span class="text-xs font-mono">{{ $subscription['id'] }}</span>
                                </div>
                            </div>

                            <h5 class="text-sm font-medium text-gray-700 mt-4 mb-3">Features</h5>
                            <ul class="bg-gray-50 rounded-md p-4 space-y-2">
                                @foreach($subscription['features'] as $feature)
                                <li class="text-xs text-gray-600 flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    {{ $feature }}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Usage Info -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-700 mb-3">Current Usage</h5>
                            <div class="bg-gray-50 rounded-md p-4 space-y-4">
                                @foreach($subscription['usage'] as $resourceName => $usage)
                                <div>
                                    <div class="flex justify-between mb-1 items-center">
                                        <span class="text-xs font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $resourceName) }}</span>
                                        <span class="text-xs text-gray-500">
                                            {{ $usage['used'] }} / {{ $usage['total'] }}
                                        </span>
                                    </div>
                                    @if(isset($usage['percentage']))
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full 
                                            {{ $usage['percentage'] < 50 ? 'bg-green-500' : ($usage['percentage'] < 80 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                            style="width: {{ min($usage['percentage'], 100) }}%">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <h5 class="text-sm font-medium text-gray-700 mb-3">Need More?</h5>
                                <div class="bg-blue-50 border border-blue-100 rounded-md p-4">
                                    <p class="text-xs text-blue-700 mb-3">
                                        Upgrade your plan to get more resources and additional features.
                                    </p>
                                    <button class="w-full px-3 py-1.5 rounded-md text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                        View Upgrade Options
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Billing History -->
    <div class="mt-8">
        <h3 class="text-base font-medium text-gray-900 mb-3">
            Billing History
        </h3>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Example invoice items - would be dynamic in production -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-900">{{ now()->subMonth()->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">MockScene Pro - Monthly subscription</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-900">€99.00</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Paid
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                <a href="#" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-download mr-1"></i> PDF
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-900">{{ now()->subMonths(2)->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">Numblio Forms - Monthly subscription</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-900">€9.99</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Paid
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                <a href="#" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-download mr-1"></i> PDF
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@else
    <!-- No Subscriptions State -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 bg-blue-50 rounded-full mb-4">
            <i class="fas fa-credit-card text-blue-400 text-2xl"></i>
        </div>
        <h4 class="text-lg font-medium text-gray-900 mb-2">No Active Subscriptions</h4>
        <p class="text-gray-600 max-w-md mx-auto mb-6">
            You don't have any active subscriptions with Numblio products. Explore our range of products to find tools that can help you.
        </p>
        <a href="https://numblio.com/products" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Explore Products
        </a>
    </div>
@endif

@endsection 