<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barcode Scanner System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen flex items-center justify-center px-4">

    @if (Route::has('login'))
        <div class="text-center">
            <h1 class="text-2xl font-semibold mb-6">Welcome to Barcode Scanner System</h1>
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="px-6 py-3 text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-6 py-3 text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                    Log in
                </a>
            @endauth
        </div>
    @endif

</body>
</html>
