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

        <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>

        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/fireworks.css', 'resources/js/fireworks.js', 'resources/js/app.js'])

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

        <!-- Styles -->
        @filamentStyles
        @livewireStyles
        @laravelPWA

    </head>
    <body class="font-sans antialiased">
        @include('sweetalert::alert')
        <x-banner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <x-fireworks></x-fireworks>

        @stack('modals')

        @livewireScripts

        <!-- fireworks -->
        <script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/329180/fscreen%401.0.1.js'></script>
        <script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/329180/Stage%400.1.4.js'></script>
        <script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/329180/MyMath.js'></script>
    </body>
</html>
