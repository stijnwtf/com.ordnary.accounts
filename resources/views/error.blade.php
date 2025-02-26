<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fout | Numblio</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full">
        <div class="text-center mb-8">
            <svg class="mx-auto h-20 w-20 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h1 class="mt-4 text-3xl font-semibold text-gray-900">Oops! Er is iets mis gegaan.</h1>
        </div>

        <div class="text-center">
            <p class="text-gray-600 text-lg">{{ $message }}</p>
            <a href="/" class="mt-6 inline-block text-blue-600 hover:text-blue-800 text-xl font-medium transition-colors">Terug naar home</a>
        </div>
    </div>
</div>
</body>
</html>
