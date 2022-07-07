<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="corporate">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        @stack('head')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @if (env('APP_ENV') == 'local')
            <script src="script.js"
                data-recolus-site-id="0182a590-0b43-33bd-87eb-329033e88820"
                data-recolus-host-url="{{ config('app.url') }}"
                data-recolus-variable="Youpi">
            </script>
        @endif
    </head>
    <body>
        <div class="min-h-screen bg-gray-100 text-gray-900font-sans antialiased">

            @include('layouts.navigation')

            {{ $slot }}
        </div>

        @stack('bottom')
    </body>
</html>
