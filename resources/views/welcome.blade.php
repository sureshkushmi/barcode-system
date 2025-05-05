<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Security Entrepreneurs Association</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen flex flex-col items-center justify-center px-4">

    <header class="w-full max-w-6xl flex justify-between items-center p-6">
        <div class="flex items-center gap-4">
            <img src="{{ asset('img/association_member.jpg') }}" alt="Logo" class="w-16 h-16 rounded-lg object-cover shadow">
            <div>
                <h1 class="text-2xl font-bold">Security Entrepreneurs Association</h1>
                <p class="text-sm text-gray-500 dark:text-gray-300">(सुरक्षा व्यवसायी संगठन)</p>
            </div>
        </div>

        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded transition">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white rounded transition">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="flex flex-col lg:flex-row items-center justify-between w-full max-w-6xl gap-8 mt-10">
        <!-- Left: Text and Tagline -->
        <div class="text-center lg:text-left lg:w-1/2">
            <h2 class="text-4xl font-extrabold leading-snug mb-4">
                Empowering Trust, Enabling Protection
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Welcome to our membership portal. Access your secure dashboard and manage your organization with confidence.
            </p>
        </div>

        <!-- Right: Image -->
        <div class="lg:w-1/2 flex justify-center">
            <img src="{{ asset('img/saileee.jpg') }}" alt="Association Members" class="w-full max-w-md rounded-xl shadow-lg object-cover">
        </div>
    </main>

    <footer class="text-center text-sm text-gray-500 py-8 w-full mt-10">
        © {{ date('Y') }} Security Entrepreneurs Association. All rights reserved.
    </footer>

</body>
</html>
