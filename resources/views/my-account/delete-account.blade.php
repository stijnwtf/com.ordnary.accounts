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
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-red-900">{{ lang('warning.title', [], 'delete-account') }}</h3>
                <div class="mt-1 text-sm text-red-800">
                    {!! lang('warning.description', [], 'delete-account') !!}
                </div>
            </div>
        </div>
    </div>

    <!-- What Will Be Deleted Section -->
    <div class="mb-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">{{ lang('what_will_be_deleted.title', [], 'delete-account') }}
        </h2>

        <div class="space-y-3 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-user text-gray-600 text-sm"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-700">
                        {{ lang('what_will_be_deleted.items.personal_info', [], 'delete-account') }}</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-history text-gray-600 text-sm"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-700">
                        {{ lang('what_will_be_deleted.items.login_history', [], 'delete-account') }}</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-link text-gray-600 text-sm"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-700">
                        {{ lang('what_will_be_deleted.items.connected_services', [], 'delete-account') }}</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-gray-600 text-sm"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-700">
                        {{ lang('what_will_be_deleted.items.security_settings', [], 'delete-account') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Note -->
    <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-info-circle text-yellow-700"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-yellow-900">{{ lang('important_note.title', [], 'delete-account') }}
                </h3>
                <div class="mt-1 text-sm text-yellow-800">
                    {{ lang('important_note.description', [], 'delete-account') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ route('account.delete.process') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-red-900">{{ lang('errors.title', [], 'delete-account') }}</h3>
                        <div class="mt-2 text-sm text-red-800">
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

        <div class="space-y-6">
            <!-- Password Confirmation -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-900 mb-2">
                    {{ lang('form.password.label', [], 'delete-account') }}
                </label>
                <input id="password" name="password" type="password" required
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 border-gray-300 text-sm"
                    placeholder="Enter your password">
            </div>

            <!-- Type DELETE to confirm -->
            <div>
                <label for="confirmation" class="block text-sm font-medium text-gray-900 mb-2">
                    {{ lang('form.confirmation.label', [], 'delete-account') }}
                </label>
                <input id="confirmation" name="confirmation" type="text" required
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 border-gray-300 text-sm"
                    placeholder="{{ lang('form.confirmation.placeholder', [], 'delete-account') }}">
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <a href="{{ route('account.security') }}"
                    class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ lang('buttons.cancel', [], 'delete-account') }}
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm">
                    <i class="fas fa-trash-alt mr-2"></i>
                    {{ lang('buttons.delete', [], 'delete-account') }}
                </button>
            </div>
        </div>
    </form>
@endsection