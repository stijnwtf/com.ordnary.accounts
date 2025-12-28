@extends('my-account.layout')

@section('title', lang('title', [], 'delete-account'))

@section('content')
    <div class="mb-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900">
            {{ lang('header', [], 'delete-account') }}
        </h3>
        <p class="mt-2 text-sm text-gray-600">
            {{ lang('subheader', [], 'delete-account') }}
        </p>
    </div>

    <!-- Warning Alert -->
    <div class="mb-6 p-5 bg-red-50 border border-red-100 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-red-500 mr-3 mt-0.5"></i>
            <div>
                <h3 class="text-sm font-medium text-red-800">{{ lang('warning.title', [], 'delete-account') }}</h3>
                <div class="mt-2 text-sm text-red-700">
                    {!! lang('warning.description', [], 'delete-account') !!}
                </div>
            </div>
        </div>
    </div>

    <!-- What Will Be Deleted Section -->
    <div class="mb-6 border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-medium text-gray-900 mb-4">{{ lang('what_will_be_deleted.title', [], 'delete-account') }}
        </h2>

        <div class="space-y-3">
            <div class="flex items-start">
                <i class="fas fa-user text-gray-400 mr-3 mt-0.5"></i>
                <div>
                    <p class="text-sm text-gray-900">
                        {{ lang('what_will_be_deleted.items.personal_info', [], 'delete-account') }}</p>
                </div>
            </div>

            <div class="flex items-start">
                <i class="fas fa-history text-gray-400 mr-3 mt-0.5"></i>
                <div>
                    <p class="text-sm text-gray-900">
                        {{ lang('what_will_be_deleted.items.login_history', [], 'delete-account') }}</p>
                </div>
            </div>

            <div class="flex items-start">
                <i class="fas fa-link text-gray-400 mr-3 mt-0.5"></i>
                <div>
                    <p class="text-sm text-gray-900">
                        {{ lang('what_will_be_deleted.items.connected_services', [], 'delete-account') }}</p>
                </div>
            </div>

            <div class="flex items-start">
                <i class="fas fa-shield-alt text-gray-400 mr-3 mt-0.5"></i>
                <div>
                    <p class="text-sm text-gray-900">
                        {{ lang('what_will_be_deleted.items.security_settings', [], 'delete-account') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Note -->
    <div class="mb-6 p-5 bg-blue-50 border border-blue-100 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mr-3 mt-0.5"></i>
            <div>
                <h3 class="text-sm font-medium text-blue-800">{{ lang('important_note.title', [], 'delete-account') }}
                </h3>
                <div class="mt-2 text-sm text-blue-700">
                    {{ lang('important_note.description', [], 'delete-account') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ route('account.delete.process') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="mb-6 p-5 bg-red-50 border border-red-100 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-times-circle text-red-500 mr-3 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">{{ lang('errors.title', [], 'delete-account') }}</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-6 border border-gray-200 rounded-lg p-6">
            <!-- Password Confirmation -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-900 mb-2">
                    {{ lang('form.password.label', [], 'delete-account') }}
                </label>
                <input id="password" name="password" type="password" required
                    class="w-full px-4 py-2.5 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent border-gray-300 text-sm"
                    placeholder="Enter your password">
            </div>

            <!-- Type DELETE to confirm -->
            <div>
                <label for="confirmation" class="block text-sm font-medium text-gray-900 mb-2">
                    {{ lang('form.confirmation.label', [], 'delete-account') }}
                </label>
                <input id="confirmation" name="confirmation" type="text" required
                    class="w-full px-4 py-2.5 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent border-gray-300 text-sm"
                    placeholder="{{ lang('form.confirmation.placeholder', [], 'delete-account') }}">
            </div>

            <div class="flex items-center justify-between pt-5 border-t border-gray-200">
                <a href="{{ route('account.security') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 shadow-sm">
                    <i class="fas fa-arrow-left mr-1.5"></i>
                    {{ lang('buttons.cancel', [], 'delete-account') }}
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm">
                    <i class="fas fa-trash-alt mr-1.5"></i>
                    {{ lang('buttons.delete', [], 'delete-account') }}
                </button>
            </div>
        </div>
    </form>
@endsection