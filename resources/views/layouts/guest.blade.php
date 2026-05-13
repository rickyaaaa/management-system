<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-950 min-h-screen flex items-center justify-center">
        <div class="w-full sm:max-w-md px-6 py-12 lg:px-8 flex flex-col justify-center">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm flex justify-center mb-8">
                <a href="/" wire:navigate>
                    <div class="w-12 h-12 bg-amber-500 rounded-xl shadow-sm flex items-center justify-center font-bold text-white text-xl ring-1 ring-black/10">
                        3D
                    </div>
                </a>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-[28rem]">
                <div class="bg-white px-6 py-8 shadow-sm ring-1 ring-gray-950/5 rounded-2xl sm:px-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
