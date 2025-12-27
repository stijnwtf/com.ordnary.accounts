<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Ordnary SSO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-[#f9fafb] min-h-screen flex flex-col">
    <div class="flex items-center justify-center flex-grow">
        <div class="w-full max-w-md p-8">
            <div class="mb-8 flex flex-col items-start">
                <img src="/img/dark.png" alt="Ordnary Logo" class="h-8 w-auto mb-3">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Verify your email address</h2>
                    <p class="text-sm text-gray-600 mt-2">We've sent a verification link to your email address. Please
                        check your inbox and click the link to verify your account.</p>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                @if (session('resent'))
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        <span class="font-medium">Success!</span> A fresh verification link has been sent to your email
                        address.
                    </div>
                @endif

                <p class="mb-4 text-gray-700">
                    Before proceeding, please check your email for a verification link. If you didn't receive the email,
                    click the button below to request another.
                </p>

                <div class="flex items-center justify-between mt-6">
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-paper-plane mr-2"></i> Resend Verification Email
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Didn't receive the email?</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span>Check your spam or junk folder</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span>Verify that your email address <strong>{{ auth()->user()->email }}</strong> is
                            correct</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span>If you still don't see it, click the resend button above</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>