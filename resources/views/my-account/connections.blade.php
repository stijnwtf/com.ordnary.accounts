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

<!-- Status Messages -->
<div id="status-message" class="mb-6 hidden">
    <!-- Will be populated by JavaScript -->
</div>

<!-- Connected Apps List -->
<div class="space-y-4" id="services-container">
    @if(count($services ?? []) > 0)
        @foreach($services as $service)
        <div class="border border-gray-200 rounded-lg overflow-hidden service-item" data-service-id="{{ $service['service_id'] }}" data-service-name="{{ $service['service_name'] }}">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-plug text-gray-500"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-medium text-gray-900">{{ $service['service_name'] }}</h5>
                            <p class="text-xs text-gray-500">Connected on {{ \Carbon\Carbon::parse($service['first_login'])->format('d-m-Y') }}</p>
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
                        <p>Last used on {{ \Carbon\Carbon::parse($service['last_used'])->format('d-m-Y H:i') }}</p>
                    </div>
                    <button class="disconnect-btn px-3 py-1 rounded text-xs font-medium text-red-600 hover:text-red-800 focus:outline-none border border-red-200 hover:bg-red-50">
                        <i class="fas fa-unlink mr-1"></i> Disconnect
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="text-center py-8 border border-gray-200 rounded-lg">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-plug text-gray-400 text-4xl mb-3"></i>
                <h5 class="text-md font-medium text-gray-900 mb-1">No connected services</h5>
                <p class="text-sm text-gray-500">You haven't connected any services to your account yet.</p>
            </div>
        </div>
    @endif
</div>

<!-- No Services Template (hidden, will be shown when all services are disconnected) -->
<template id="no-services-template">
    <div class="text-center py-8 border border-gray-200 rounded-lg">
        <div class="flex flex-col items-center justify-center">
            <i class="fas fa-plug text-gray-400 text-4xl mb-3"></i>
            <h5 class="text-md font-medium text-gray-900 mb-1">No connected services</h5>
            <p class="text-sm text-gray-500">You haven't connected any services to your account yet.</p>
        </div>
    </div>
</template>

<!-- Add More Connections Button -->
<div class="pb-6 flex justify-end mt-6">
    <button class="flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm">
        <i class="fas fa-plus-circle mr-2"></i> Connect more services
    </button>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all disconnect buttons
    const disconnectButtons = document.querySelectorAll('.disconnect-btn');
    
    // Add click event listener to each button
    disconnectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const serviceItem = this.closest('.service-item');
            const serviceId = serviceItem.dataset.serviceId;
            const serviceName = serviceItem.dataset.serviceName;
            
            if (confirm(`Are you sure you want to disconnect ${serviceName}? This will revoke access to your Numblio account.`)) {
                disconnectService(serviceId, serviceItem);
            }
        });
    });
    
    // Function to disconnect a service
    function disconnectService(serviceId, serviceItem) {
        // Show loading state
        serviceItem.classList.add('opacity-50');
        
        // Debug: Log what we're sending
        console.log('Disconnecting service ID:', serviceId);
        console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Make API request to disconnect service
        fetch('/api/user/unlink-service', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ service_id: serviceId }),
            credentials: 'same-origin' // Include cookies
        })
        .then(response => {
            // Debug: Log the raw response
            console.log('Status:', response.status);
            console.log('OK:', response.ok);
            
            // Handle non-JSON responses
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server returned non-JSON response');
            }
            
            return response.json();
        })
        .then(data => {
            // Debug: Log the parsed data
            console.log('Response data:', data);
            
            if (data.success) {
                // Remove the service item from the DOM
                serviceItem.remove();
                
                // Show success message
                showStatusMessage('success', `Successfully disconnected ${serviceItem.dataset.serviceName}`);
                
                // Check if there are no more services
                const remainingServices = document.querySelectorAll('.service-item');
                if (remainingServices.length === 0) {
                    // Show the "no services" message
                    const noServicesTemplate = document.getElementById('no-services-template');
                    const servicesContainer = document.getElementById('services-container');
                    servicesContainer.innerHTML = noServicesTemplate.innerHTML;
                }
            } else {
                // Show error message
                showStatusMessage('error', data.message || 'Failed to disconnect service. Please try again.');
                serviceItem.classList.remove('opacity-50');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showStatusMessage('error', 'An unexpected error occurred. Please try again.');
            serviceItem.classList.remove('opacity-50');
        });
    }
    
    // Function to show status messages
    function showStatusMessage(type, message) {
        const statusMessage = document.getElementById('status-message');
        statusMessage.innerHTML = '';
        statusMessage.classList.remove('hidden', 'bg-green-50', 'bg-red-50');
        
        let backgroundColor = 'bg-green-50';
        let borderColor = 'border-green-200';
        let textColor = 'text-green-800';
        let icon = 'fa-check-circle';
        let iconColor = 'text-green-500';
        
        if (type === 'error') {
            backgroundColor = 'bg-red-50';
            borderColor = 'border-red-200';
            textColor = 'text-red-800';
            icon = 'fa-exclamation-circle';
            iconColor = 'text-red-500';
        }
        
        statusMessage.classList.add(backgroundColor, 'p-4', 'rounded-lg', 'border', borderColor);
        
        const content = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas ${icon} ${iconColor} text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium ${textColor}">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button onclick="this.parentElement.parentElement.parentElement.parentElement.classList.add('hidden')" class="inline-flex rounded-md p-1.5 ${textColor} hover:bg-${type === 'error' ? 'red' : 'green'}-100 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        statusMessage.innerHTML = content;
        statusMessage.classList.remove('hidden');
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            statusMessage.classList.add('hidden');
        }, 5000);
    }
});
</script>
@endsection 