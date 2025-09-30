<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" sizes="32x32" />
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}" sizes="180x180" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/preloader.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <title>Zeno - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-100" x-data="{ mobileMenuOpen: false }">

    <!-- Preloader -->
    {{-- @include('partials.preloader') --}}
    @include('components.notification')
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-60 border-r border-gray-200 bg-white">
                <!-- Sidebar content -->
                <div class="flex-1 flex flex-col pt-5 pb-4 fixed h-screen overflow-y-auto" style="width: inherit;">
                    <!-- Logo -->
                    <div class="flex items-center flex-shrink-0 px-5">
                        <x-application-logo class="h-8 w-auto" />
                    </div>
                    <!-- Sidebar Navigation -->
                    <nav class="mt-5 flex-1 pl-4 pr-2 space-y-1">
                        @include('admin.partials.sidebar-links')
                    </nav>
                </div>
                <!-- Admin Profile Section -->
                {{-- <div class="flex-shrink-0 flex border-t bg-white border-gray-200 p-4 fixed bottom-0"
                    style="width: inherit;">
                    <a href="{{ route('profile') }}" class="flex-shrink-0 w-full group block">
                        <div class="flex items-center">
                            <div>
                                <img class="inline-block h-9 w-9 -full"
                                    src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                    alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                                    {{ Auth::user()->name }}</p>
                                <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">View profile</p>
                            </div>
                        </div>
                    </a>
                </div> --}}
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile top navigation -->
            <div class="md:hidden">
                <div class="flex items-center justify-between bg-white border-b border-gray-200 p-4">
                    <div>
                        <x-application-logo class="h-8 w-auto" />
                    </div>
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="text-gray-500 hover:text-gray-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Main content area -->
            <div class="flex-1 overflow-y-auto">
                {{-- Admin Header --}}
                @include('admin.partials.bashboard-header')
                @include('partials.flash-messages')

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Mobile sidebar -->
    <div class="md:hidden" x-show="mobileMenuOpen" x-cloak @keydown.escape="mobileMenuOpen = false">
        <!-- Overlay -->
        <div class="fixed inset-0 z-40">
            <div class="absolute inset-0 bg-gray-600 opacity-75" @click="mobileMenuOpen = false"></div>
        </div>

        <!-- Sidebar container -->
        <div class="fixed inset-y-0 left-0 z-50 flex max-w-xs w-full">
            <!-- Sidebar content -->
            <div class="relative flex-1 flex flex-col w-full bg-white">
                <!-- Close button -->
                <div class="absolute top-0 right-0 -mr-14 p-2">
                    <button @click="mobileMenuOpen = false"
                        class="flex items-center justify-center h-12 w-12 -full focus:outline-none">
                        <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Sidebar content -->
                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-shrink-0 flex items-center px-4">
                        <x-application-logo class="h-8 w-auto" />
                    </div>
                    <nav class="mt-5 px-2 space-y-1">
                        @include('admin.partials.sidebar-links')

                    </nav>
                </div>

                <!-- Profile section -->
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <a href="{{ route('profile') }}" class="flex-shrink-0 group block" @click="mobileMenuOpen = false">
                        <div class="flex items-center">
                            <div>
                                <img class="inline-block h-10 w-10 -full"
                                    src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                    alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-base font-medium text-gray-700 group-hover:text-gray-900">
                                    {{ Auth::user()->name }}</p>
                                <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">View profile</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Empty div to push sidebar to left -->
            <div class="flex-shrink-0 w-14">
                <!-- Force sidebar to shrink to fit close icon -->
            </div>
        </div>
    </div>

    <script src="{{ asset('js/preloader.js') }}"></script>
    <script src="{{ asset('js/notification.js') }}"></script>
    @stack('scripts')
</body>

</html>