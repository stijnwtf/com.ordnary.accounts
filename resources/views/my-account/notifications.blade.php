@extends('my-account.layout')

@section('title', 'Notification Settings')

@section('header', 'Notification Settings')
@section('subheader', 'Manage your notification preferences and settings.')

@section('content')
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Notifications</h3>
        <p class="text-sm text-gray-600">Manage your notification preferences and settings.</p>
    </div>

    <!-- Notifications Overview Card -->
    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
        <div class="flex items-center">
            <div class="mr-4">
                <i class="fas fa-bell text-blue-500 text-xl"></i>
            </div>
            <div>
                <h4 class="text-md font-semibold text-gray-900">Notification settings</h4>
                <p class="text-sm text-gray-600">Customize your notification preferences to receive relevant updates and
                    alerts.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="#" class="space-y-6">
        @csrf

        <!-- Email Notifications -->
        <div>
            <label for="email_notifications" class="block text-sm font-medium text-gray-700 mb-1">Email
                Notifications</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <select name="email_notifications" id="email_notifications"
                    class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('email_notifications') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="daily">Daily summary</option>
                    <option value="weekly">Weekly summary</option>
                    <option value="none">No email notifications</option>
                </select>
            </div>
            <p class="mt-1 text-xs text-gray-500">Choose the frequency of email notifications.</p>
        </div>

        <!-- Push Notifications -->
        <div>
            <label for="push_notifications" class="block text-sm font-medium text-gray-700 mb-1">Push Notifications</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-mobile-alt text-gray-400"></i>
                </div>
                <select name="push_notifications" id="push_notifications"
                    class="w-full px-3 py-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('push_notifications') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="all">All notifications</option>
                    <option value="important">Important notifications only</option>
                    <option value="none">No push notifications</option>
                </select>
            </div>
            <p class="mt-1 text-xs text-gray-500">Choose the types of notifications you want to receive via push
                notifications.</p>
        </div>

        <!-- Submit Button for Notifications Form -->
        <div class="pb-6 flex justify-end">
            <button type="submit"
                class="flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm">
                <i class="fas fa-save mr-2"></i> Confirm changes
            </button>
        </div>
    </form>
@endsection