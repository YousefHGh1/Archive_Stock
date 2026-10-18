<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, user-scalable=yes">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#4f46e5">
        <meta name="description" content="تطبيق إدارة الأرشيف والمخزون - بلدية جباليا النزلة">
        
        <!-- PWA / Mobile -->
        <link rel="manifest" href="/manifest/manifest.json">
        <link rel="icon" type="image/svg+xml" href="/icons/icon-192x192.svg">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.svg">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="Archive-Stock">
        <meta name="mobile-web-app-capable" content="yes">
        
        <!-- Standard Favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/media/logos/favicon.png') }}">
        
        <title>{{ config('app.name', 'Baladia') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    // Only register service worker for guest users (not authenticated)
                    @if(!auth()->check())
                    navigator.serviceWorker.register('/sw.js')
                        .then(registration => {
                            console.log('ServiceWorker registration successful');
                        })
                        .catch(err => {
                            console.log('ServiceWorker registration failed: ', err);
                        });
                    @else
                    // Unregister service worker for authenticated users to prevent caching issues
                    navigator.serviceWorker.getRegistrations().then(function(registrations) {
                        registrations.forEach(function(registration) {
                            registration.unregister();
                        });
                    });
                    @endif
                });
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                <div class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
