@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <img class="mx-auto h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="Numblio">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Recovery Code
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Please enter one of your recovery codes to access your account
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="{{ route('2fa.recovery.verify') }}" method="POST">
                @csrf
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">
                        Recovery Code
                    </label>
                    <div class="mt-1">
                        <input id="code" name="code" type="text" required 
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter recovery code">
                    </div>
                </div>

                @if($direct_login)
                    <input type="hidden" name="direct_login" value="1">
                @endif

                @if($client_id)
                    <input type="hidden" name="client_id" value="{{ $client_id }}">
                @endif

                @if($redirect_uri)
                    <input type="hidden" name="redirect_uri" value="{{ $redirect_uri }}">
                @endif

                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Verify Recovery Code
                    </button>
                </div>

                <div class="text-sm text-center">
                    <a href="{{ route('2fa') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Back to 2FA verification
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 