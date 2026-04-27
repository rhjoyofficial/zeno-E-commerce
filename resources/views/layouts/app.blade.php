<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-scrollbar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" sizes="32x32" />
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}" sizes="180x180" />

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    {{-- Preloader CSS --}}
    <link rel="stylesheet" href="{{ asset('css/preloader.css') }}">

    {{-- Page-specific styles (Swiper, Splide, etc.) --}}
    @stack('styles')

    <title>Zeno - @yield('title', 'Shopping')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-black font-sans antialiased no-scrollbar">
    @include('partials.preloader')
    @include('partials.loading-overlay')
    @include('components.notification')

    @include('frontend.navbar')
    @include('partials.flash-messages')

    @yield('content')

    @include('partials.membership')
    <hr>
    @include('frontend.footer')
    @include('components.product-cart-popup')

    <div id="notification-container" class="fixed top-20 right-4 z-[9999] space-y-3 w-80 max-w-[90vw]"></div>

    <script src="{{ asset('js/preloader.js') }}"></script>
    <script src="{{ asset('js/helper.js') }}"></script>

    {{-- Global app config for JS --}}
    <script>
        window.appConfig = {
            routes: {
                productVariants:  "{{ route('products.variants') }}",
                cartAdd:          "{{ route('cart.add') }}",
                getVariantPrice:  "{{ route('cart.get-variant-price') }}",
            },
            csrfToken: "{{ csrf_token() }}",
        };
    </script>

    <script src="{{ asset('js/product-popup.js') }}"></script>

    @stack('scripts')
</body>

</html>
