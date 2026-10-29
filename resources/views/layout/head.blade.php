<link href="{{ asset('assets/media/logos/favicon.ico') }}" rel="shortcut icon">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Baladia Jabalia">
<meta name="theme-color" content="#1E1E2D">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Archive-Stock">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="manifest" href="{{ asset('manifest/manifest.json') }}">
<link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
<link rel="canonical" href="{{ url('/dashboard') }}">

<style>
    * {
        font-family: 'Cairo', sans-serif;
    }
</style>

<!-- Cairo Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">

<!-- Page Title -->
<title>@yield('title')</title>

<!--begin::Global Theme Styles(used by all pages)-->
<link href="{{ asset('assets/plugins/global/plugins.bundle.rtl.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.rtl.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/style.bundle.rtl.css') }}" rel="stylesheet" type="text/css">
<!--end::Global Theme Styles-->
<!--begin::Layout Themes(used by all pages)-->
<link href="{{ asset('assets/css/themes/layout/header/base/light.rtl.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/themes/layout/header/menu/light.rtl.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/themes/layout/brand/dark.rtl.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/themes/layout/aside/dark.rtl.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/mobile-aside.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/app-theme.css') }}?v={{ @filemtime(public_path('css/app-theme.css')) }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/transaction-animations.css') }}?v={{ @filemtime(public_path('css/transaction-animations.css')) }}" rel="stylesheet" type="text/css">
<!--end::Layout Themes-->

@yield('css')
