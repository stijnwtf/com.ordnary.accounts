<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fout | Numblio</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ms-blue': '#0078d4',
                        'ms-gray': '#f5f5f5',
                        'ms-dark': '#505050'
                    },
                    fontFamily: {
                        'segoe': ['Segoe UI', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white font-segoe text-ms-dark">
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <!-- Microsoft-style icon -->
            <svg class="mx-auto h-16 w-16 text-ms-blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12" y2="16" />
            </svg>

            <h1 class="mt-6 text-2xl font-light text-ms-dark">Oops! Er is iets mis gegaan.</h1>
        </div>

        <div class="text-center">
            <p class="text-ms-dark text-base">{{ $message }}</p>

            <a href="/" class="mt-8 inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-md text-white bg-ms-blue hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ms-blue transition-colors">
                Terug naar home
            </a>
        </div>

        <div class="mt-12 text-center text-sm text-gray-400">
            <p>Numblio &copy; 2025</p>
        </div>
    </div>
</div>
</body>
</html>
