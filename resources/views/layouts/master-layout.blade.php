<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Fav Icon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" sizes="32x32" />
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}" sizes="180x180" />
    {{-- Font Awesome Icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Swiper Slider --}}
    {{--
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> --}}
    {{-- Splide Slider --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css">
    {{-- Alpine JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- google albert font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    {{-- Preloader CSS --}}
    <link rel="stylesheet" href="{{asset('css/preloader.css')}}">

    <title>Zeno - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head-scripts')
    @stack('styles')
</head>

<body class="bg-white text-black font-sans antialiased">
    {{-- @include('partials.preloader') --}}
    @include('frontend.navbar')
    @include('partials.flash-messages')
    {{-- @include('components.dynamic-navigation') --}}
    @yield('content')
    {{-- Footer --}}
    <hr>
    @include('frontend.footer')

    <script src="{{ asset('js/preloader.js')}}"></script>
    {{-- Splide Slider --}}
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>
    <!-- Initialize Swiper JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> --}}
    <!-- Global Config -->
    <script>
        window.appConfig = {
        routes: {
            getVariantPrice: "{{ route('cart.get-variant-price') }}",      
        },
        csrfToken: "{{ csrf_token() }}",
    };
    </script>
    @stack('scripts')
</body>

</html>