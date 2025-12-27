@extends('layouts.app')

@section('title', 'Account Deleted')

@section('content')
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <img class="mx-auto h-12 w-auto" src="{{ asset('img/logo.svg') }}" alt="Ordnary">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Account Successfully Deleted
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Your account and data have been permanently removed.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Account Deletion Complete</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>
                                    Your account and personal data have been successfully deleted in accordance with GDPR
                                    requirements. Any remaining data has been anonymized and is no longer personally
                                    identifiable.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">What Happens Next?</h3>
                        <p class="text-sm text-gray-600">
                            Your session has been terminated and you have been logged out of all devices. If you wish to use
                            Ordnary services again in the future, you will need to create a new account.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Questions?</h3>
                        <p class="text-sm text-gray-600">
                            If you have any questions about the data deletion process or need further assistance, please
                            contact our support team at <a href="mailto:privacy@ordnary.com"
                                class="text-indigo-600 hover:text-indigo-500">privacy@ordnary.com</a>.
                        </p>
                    </div>

                    <div class="mt-8 text-center">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Back to Homepage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection