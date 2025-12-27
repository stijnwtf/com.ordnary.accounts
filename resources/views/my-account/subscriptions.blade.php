@extends('my-account.layout')

@section('title', 'Subscriptions & Usage')

@section('content')
    <div class="mb-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900">
            Subscriptions & Usage
        </h3>
        <p class="mt-2 text-sm text-gray-600">
            Manage your Ordnary product subscriptions and monitor your usage.
        </p>
    </div>

    @if(count($subscriptions) > 0)
        <!-- Subscriptions Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next
                                billing</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($subscriptions as $index => $subscription)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-9 w-9 bg-{{ $subscription['status'] === 'active' ? 'blue-100' : 'gray-100' }} rounded-lg flex items-center justify-center">
                                            <i
                                                class="fas fa-cube text-{{ $subscription['status'] === 'active' ? 'blue-500' : 'gray-500' }}"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $subscription['product_name'] }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $subscription['id'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $subscription['plan'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subscription['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i
                                            class="fas fa-{{ $subscription['status'] === 'active' ? 'check-circle' : 'clock' }} mr-1"></i>
                                        {{ ucfirst($subscription['status']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $subscription['next_billing']->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $subscription['price'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <button type="button" class="text-blue-600 hover:text-blue-800"
                                        onclick="toggleDetails('subscription-details-{{ $index }}')">
                                        <i class="fas fa-chevron-down mr-1" id="icon-subscription-details-{{ $index }}"></i>
                                        Details
                                    </button>
                                    @if($subscription['status'] === 'active')
                                        <button class="ml-3 text-red-600 hover:text-red-800">
                                            Cancel
                                        </button>
                                    @else
                                        <button class="ml-3 text-blue-600 hover:text-blue-800">
                                            Reactivate
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            <!-- Expandable Details Row -->
                            <tr class="hidden details-row bg-gray-50" id="subscription-details-{{ $index }}">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="grid grid-cols-2 gap-8">
                                        <!-- Subscription Info -->
                                        <div>
                                            <h5 class="text-sm font-medium text-gray-700 mb-3">Subscription Details</h5>
                                            <div class="bg-white rounded-md border border-gray-200 p-4 space-y-3 text-xs">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">Started on</span>
                                                    <span
                                                        class="font-medium">{{ $subscription['started_at']->format('d M Y') }}</span>
                                                </div>
                                                @if($subscription['expires_at'])
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500">Expires on</span>
                                                        <span
                                                            class="font-medium">{{ $subscription['expires_at']->format('d M Y') }}</span>
                                                    </div>
                                                @endif

                                                <div class="pt-2 pb-1 border-t border-gray-200">
                                                    <span class="font-medium text-gray-700">Features:</span>
                                                </div>
                                                <ul class="space-y-1 pl-1">
                                                    @foreach($subscription['features'] as $feature)
                                                        <li class="text-gray-600 flex items-center">
                                                            <i class="fas fa-check text-green-500 mr-2"></i>
                                                            {{ $feature }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Usage Info -->
                                        <div>
                                            <h5 class="text-sm font-medium text-gray-700 mb-3">Current Usage</h5>
                                            <div class="bg-white rounded-md border border-gray-200 p-4 space-y-4">
                                                @foreach($subscription['usage'] as $resourceName => $usage)
                                                    <div>
                                                        <div class="flex justify-between mb-1 items-center">
                                                            <span
                                                                class="text-xs font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $resourceName) }}</span>
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

                                                <!-- Upgrade CTA -->
                                                <div class="pt-2 mt-2 border-t border-gray-200">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs text-blue-700">Need more resources?</span>
                                                        <button
                                                            class="px-3 py-1 rounded-md text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                                            Upgrade Plan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Billing History -->
        <div class="mt-8">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-medium text-gray-900">Billing History</h3>
                <a href="#" class="text-xs text-blue-600 hover:text-blue-800">
                    <i class="fas fa-download mr-1"></i> Export All Invoices
                </a>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Invoice</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Example invoice items - would be dynamic in production -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-900">
                                    {{ now()->subMonth()->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">MockScene Pro - Monthly
                                    subscription</td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-900">€99.00</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs text-gray-600">
                                    <a href="#" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-download mr-1"></i> PDF
                                    </a>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-900">
                                    {{ now()->subMonths(2)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">Ordnary Forms - Monthly
                                    subscription</td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-900">€9.99</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs text-gray-600">
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
                You don't have any active subscriptions with Ordnary products. Explore our range of products to find tools that
                can help you.
            </p>
            <a href="https://ordnary.com/products"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Explore Products
            </a>
        </div>
    @endif

@endsection

@section('scripts')
    <script>
        function toggleDetails(id) {
            const detailsRow = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);

            if (detailsRow.classList.contains('hidden')) {
                detailsRow.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                detailsRow.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    </script>
@endsection