<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" sizes="32x32" />
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}" sizes="180x180" />
    <link rel="stylesheet" href="{{ asset('css/preloader.css') }}">

    <title>Zeno - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-100">

    @include('components.notification')
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-60 border-r border-gray-200 bg-white">
                <div class="flex-1 flex flex-col pt-5 pb-4 fixed h-screen overflow-y-auto" style="width: inherit;">
                    <div class="flex items-center flex-shrink-0 px-5">
                        <x-application-logo class="h-8 w-auto" />
                    </div>
                    <nav class="mt-5 flex-1 pl-4 pr-2 space-y-1">
                        @include('admin.partials.sidebar-links')
                    </nav>
                </div>
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
                    <button id="admin-sidebar-open"
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
                @include('admin.partials.bashboard-header')
                @include('partials.flash-messages')

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Mobile sidebar -->
    <div class="md:hidden hidden" id="admin-sidebar-wrapper">
        <!-- Overlay -->
        <div class="fixed inset-0 z-40" id="admin-sidebar-overlay-wrapper">
            <div id="admin-sidebar-overlay" class="absolute inset-0 bg-gray-600 opacity-75"></div>
        </div>

        <!-- Sidebar container -->
        <div id="admin-mobile-sidebar" class="hidden fixed inset-y-0 left-0 z-50 flex max-w-xs w-full">
            <div class="relative flex-1 flex flex-col w-full bg-white">
                <!-- Close button -->
                <div class="absolute top-0 right-0 -mr-14 p-2">
                    <button data-sidebar-close
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
                    <a href="{{ route('profile') }}" class="flex-shrink-0 group block" data-sidebar-close>
                        <div class="flex items-center">
                            <div>
                                <img class="inline-block h-10 w-10 -full"
                                    src="{{ asset('images/default-avatar.png') }}"
                                    alt="{{ Auth::user()->name }}">
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
            <div class="flex-shrink-0 w-14"></div>
        </div>
    </div>

    <script>
    (function () {
        const openBtn = document.getElementById('admin-sidebar-open');
        const sidebar = document.getElementById('admin-mobile-sidebar');
        const overlay = document.getElementById('admin-sidebar-overlay');
        const wrapper = document.getElementById('admin-sidebar-wrapper');
        const closeBtns = document.querySelectorAll('[data-sidebar-close]');

        function openSidebar() {
            wrapper.classList.remove('hidden');
            sidebar.classList.remove('hidden');
        }

        function closeSidebar() {
            wrapper.classList.add('hidden');
            sidebar.classList.add('hidden');
        }

        if (openBtn) openBtn.addEventListener('click', openSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
        closeBtns.forEach(btn => btn.addEventListener('click', closeSidebar));

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
    })();
    </script>
    @stack('scripts')
</body>

</html>
