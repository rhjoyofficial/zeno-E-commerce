<nav x-data="{
    isMobileMenuOpen: false,
    searchOpen: false,
    activeMenu: null,
    activeSubmenu: null,
    activeUser: false
}"
    class="bg-white sticky top-0 z-50 py-2 text-lg font-semibold text-black border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search Bar Overlay -->
        <div x-show="searchOpen" x-cloak class="fixed inset-0 z-50 h-16" @click.away="searchOpen = false">
            <div class="container mx-auto flex items-center justify-between p-4">
                <form class="flex-1 flex max-w-3xl mx-auto" action="/search">
                    <input type="text" placeholder="Search products..."
                        class="w-full px-4 py-2 text-lg border-b-2 border-gray-300 focus:border-indigo-600 outline-none"
                        autofocus>
                    <button type="button" @click="searchOpen = false" class="ml-4 hover:text-gray-700">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex justify-between items-center h-16 relative">
            <!-- Logo -->
            <a href="/" class="flex-shrink-0">
                <img class="h-8 w-auto" src="{{ asset('images/Zeno.png') }}" alt="Zeno Logo">
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex md:items-center m-0 flex-1 justify-center relative">
                <!-- Men Menu -->
                <button @mouseenter="activeMenu = 'men'" @mouseleave="activeMenu = null"
                    class="hover:text-gray-700 px-3 py-2 transition-colors uppercase mr-2 last:mr-0">
                    Men
                </button>
                <button @mouseenter="activeMenu = 'women'" @mouseleave="activeMenu = null"
                    class="hover:text-gray-700 px-3 py-2 transition-colors uppercase mr-2 last:mr-0">
                    Women
                </button>
                <button @mouseenter="activeMenu = 'kid'" @mouseleave="activeMenu = null"
                    class="hover:text-gray-700 px-3 py-2 transition-colors uppercase mr-2 last:mr-0">
                    Kid
                </button>
                <button @mouseenter="activeMenu = 'accessories'" @mouseleave="activeMenu = null"
                    class="hover:text-gray-700 px-3 py-2 transition-colors uppercase mr-2 last:mr-0">
                    Accessories
                </button>
                <button @mouseenter="activeMenu = 'sale'" @mouseleave="activeMenu = null"
                    class="text-red-600 hover:text-red-700 px-3 py-2 transition-colors font-semibold uppercase mr-2 last:mr-0">
                    Sale
                </button>
            </div>

            <!-- Full-width Mega Menu -->
            <div @mouseenter="activeMenu = 'men'" @mouseleave="activeMenu = null"
                class="absolute top-3/4 left-0 right-0 w-full z-20" x-show="activeMenu === 'men'" x-cloak>
                <div class="bg-white shadow-xl py-8 px-4 border-t border-gray-100 -md mt-5">
                    <!-- Grid Container -->
                    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">
                        <!-- Categories Column -->
                        <div class="space-y-4">
                            <h3 class="font-semibold text-gray-900 text-lg mb-4">Men's Categories</h3>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                                <div class="relative group" @mouseenter="activeSubmenu = 'clothing'"
                                    @mouseleave="activeSubmenu = null">
                                    <button
                                        class="w-full text-left hover:text-gray-700 flex justify-between items-center">
                                        Clothing
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                    <!-- Submenu -->
                                    <div x-show="activeSubmenu === 'clothing'" @mouseenter="activeSubmenu = 'clothing'"
                                        @mouseleave="activeSubmenu = null"
                                        class="absolute left-full top-0 bg-white p-4 shadow-lg w-64 rounded-lg border border-gray-100 z-30">
                                        <a href="#" class="block py-2 px-4 hover: ">T-Shirts</a>
                                        <a href="#" class="block py-2 px-4 hover: ">Shirts</a>
                                        <a href="#" class="block py-2 px-4 hover: ">Jeans</a>
                                        <a href="#" class="block py-2 px-4 hover: ">Shorts</a>
                                    </div>
                                </div>

                                <!-- Repeat similar structure for other categories -->
                                <div class="relative group">
                                    <button class="w-full text-left hover:text-gray-700">Footwear</button>
                                </div>
                                <div class="relative group">
                                    <button class="w-full text-left hover:text-gray-700">Accessories</button>
                                </div>
                                <div class="relative group">
                                    <button class="w-full text-left hover:text-gray-700">Sportswear</button>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Collection -->
                        <div class="space-y-4">
                            <h3 class="font-semibold text-gray-900 text-lg mb-4">Featured Collections</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="#" class="group">
                                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                        <img src="{{ asset('images/men-summer.jpg') }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                            alt="Summer Collection">
                                    </div>
                                    <p class="mt-2 text-sm font-medium">Summer Essentials</p>
                                </a>
                                <a href="#" class="group">
                                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                        <img src="{{ asset('images/men-formal.jpg') }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                            alt="Formal Wear">
                                    </div>
                                    <p class="mt-2 text-sm font-medium">Formal Wear</p>
                                </a>
                            </div>
                        </div>

                        <!-- Brands & Offers -->
                        <div class="space-y-4">
                            <h3 class="font-semibold text-gray-900 text-lg mb-4">Top Brands</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <a href="#"
                                    class="p-1 hover:text-gray-700 hover:bg-gray-100 rounded-lg flex items-center justify-center">
                                    <img src="{{ asset('images/nike.png') }}"
                                        class="h-10 w-fit object-contain scale-150" alt="Nike">
                                </a>
                                <a href="#"
                                    class="p-1 hover:text-gray-700 hover:bg-gray-100 rounded-lg flex items-center justify-center">
                                    <img src="{{ asset('images/adidas.png') }}"
                                        class="h-10 w-fit object-contain scale-150" alt="Adidas">
                                </a>
                                <a href="#"
                                    class="p-1 hover:text-gray-700 hover:bg-gray-100 rounded-lg flex items-center justify-center">
                                    <img src="{{ asset('images/puma.png') }}"
                                        class="h-10 w-fit object-contain scale-150" alt="Puma">
                                </a>
                                <a href="#"
                                    class="p-1 hover:text-gray-700 hover:bg-gray-100 rounded-lg flex items-center justify-center">
                                    <img src="{{ asset('images/levis.png') }}"
                                        class="h-10 w-fit object-contain scale-150" alt="Levi's">
                                </a>
                            </div>
                        </div>

                        <!-- Promo Banner -->
                        <div class="relative -xl overflow-hidden bg-gray-100">
                            <img src="{{ asset('images/special-offer.jpg') }}" alt="Men's Special Offer"
                                class="w-full h-full object-cover absolute inset-0">
                            <div class="relative p-6 h-full flex flex-col justify-end bg-gradient-to-t from-black/60">
                                <h3 class="text-2xl font-bold text-white mb-2">Season Final Sale</h3>
                                <p class="text-white/90">Up to 60% off selected items</p>
                                <button
                                    class="mt-4 bg-white text-gray-900 px-6 py-2 -full w-fit text-sm font-mediumhover:text-gray-700 hover:bg-gray-100">
                                    Shop Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Icons -->
            <div class="flex items-center relative">
                <!-- Search Icon (Mobile) -->
                <button @click="searchOpen = true" class="p-2 hover:text-gray-700 md:hidden mr-2 last:mr-0">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                            stroke="#071630" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M21 21L16.65 16.65" stroke="#071630" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                <!-- Search Icon (Desktop) -->
                <div class="hidden md:flex items-center space-x-2">
                    <button @click="searchOpen = true" class="p-2 hover:text-gray-700 mr-2 last:mr-0">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                                stroke="#071630" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M21 21L16.65 16.65" stroke="#071630" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>

                    <!-- User Dropdown -->
                    @include('partials.user-dropdown')

                    <!-- Cart Icon -->
                    <button onclick="window.location.href='{{ route('cart.index') }}'"
                        class="p-2 hover:text-gray-700 relative mr-2 last:mr-0">
                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.25 7H3.636C4.146 7 4.591 7.343 4.723 7.835L5.106 9.272M5.106 9.272C10.6766 9.11589 16.2419 9.73515 21.642 11.112C20.818 13.566 19.839 15.95 18.718 18.25H7.5M5.106 9.272L7.5 18.25M7.5 18.25C6.70435 18.25 5.94129 18.5661 5.37868 19.1287C4.81607 19.6913 4.5 20.4544 4.5 21.25H20.25M6 24.25C6 24.4489 5.92098 24.6397 5.78033 24.7803C5.63968 24.921 5.44891 25 5.25 25C5.05109 25 4.86032 24.921 4.71967 24.7803C4.57902 24.6397 4.5 24.4489 4.5 24.25C4.5 24.0511 4.57902 23.8603 4.71967 23.7197C4.86032 23.579 5.05109 23.5 5.25 23.5C5.44891 23.5 5.63968 23.579 5.78033 23.7197C5.92098 23.8603 6 24.0511 6 24.25ZM18.75 24.25C18.75 24.4489 18.671 24.6397 18.5303 24.7803C18.3897 24.921 18.1989 25 18 25C17.8011 25 17.6103 24.921 17.4697 24.7803C17.329 24.6397 17.25 24.4489 17.25 24.25C17.25 24.0511 17.329 23.8603 17.4697 23.7197C17.6103 23.579 17.8011 23.5 18 23.5C18.1989 23.5 18.3897 23.579 18.5303 23.7197C18.671 23.8603 18.75 24.0511 18.75 24.25Z"
                                stroke="#071630" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M24 6.04972C23.7325 6.04972 23.5046 5.97692 23.3164 5.83132C23.1282 5.68454 22.9844 5.47206 22.8849 5.19389C22.7855 4.91454 22.7358 4.57718 22.7358 4.18182C22.7358 3.78883 22.7855 3.45324 22.8849 3.17507C22.9856 2.89571 23.13 2.68265 23.3182 2.53587C23.5076 2.3879 23.7348 2.31392 24 2.31392C24.2652 2.31392 24.4918 2.3879 24.68 2.53587C24.8694 2.68265 25.0138 2.89571 25.1133 3.17507C25.2139 3.45324 25.2642 3.78883 25.2642 4.18182C25.2642 4.57718 25.2145 4.91454 25.1151 5.19389C25.0156 5.47206 24.8718 5.68454 24.6836 5.83132C24.4954 5.97692 24.2675 6.04972 24 6.04972ZM24 5.65909C24.2652 5.65909 24.4711 5.53125 24.6179 5.27557C24.7647 5.01989 24.8381 4.6553 24.8381 4.18182C24.8381 3.86695 24.8043 3.59884 24.7369 3.37749C24.6706 3.15613 24.5747 2.98745 24.4492 2.87145C24.3249 2.75545 24.1752 2.69744 24 2.69744C23.7372 2.69744 23.5318 2.82706 23.3839 3.08629C23.2359 3.34434 23.1619 3.70952 23.1619 4.18182C23.1619 4.49669 23.1951 4.7642 23.2614 4.98438C23.3277 5.20455 23.4229 5.37204 23.5472 5.48686C23.6727 5.60168 23.8236 5.65909 24 5.65909Z"
                                fill="white" />
                        </svg>
                        <span
                            class="cart-counter absolute top-1 right-1 bg-red-500 text-white text-[0.65rem] text-center rounded-full h-3 w-3 flex items-center justify-center p-[6px]">{{ $cartCount ?? 0 }}</span>
                    </button>

                    <!-- Wishlist Icon -->
                    <button class="p-2 hover:text-gray-700 mr-2 last:mr-0">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20.84 4.61C20.3292 4.099 19.7228 3.69365 19.0554 3.41708C18.3879 3.14052 17.6725 2.99817 16.95 2.99817C16.2275 2.99817 15.5121 3.14052 14.8446 3.41708C14.1772 3.69365 13.5708 4.099 13.06 4.61L12 5.67L10.94 4.61C9.9083 3.57831 8.50903 2.99871 7.05 2.99871C5.59096 2.99871 4.19169 3.57831 3.16 4.61C2.1283 5.64169 1.54871 7.04097 1.54871 8.5C1.54871 9.95903 2.1283 11.3583 3.16 12.39L4.22 13.45L12 21.23L19.78 13.45L20.84 12.39C21.351 11.8792 21.7563 11.2728 22.0329 10.6054C22.3095 9.9379 22.4518 9.22249 22.4518 8.5C22.4518 7.77751 22.3095 7.0621 22.0329 6.39464C21.7563 5.72719 21.351 5.12076 20.84 4.61Z"
                                stroke="#071630" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden" x-show="isMobileMenuOpen" @click.away="isMobileMenuOpen = false" x-cloak>
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#" class="block px-3 py-2 hover:text-gray-700 hover:bg-gray-100 uppercase">Men</a>
                <a href="#" class="block px-3 py-2 hover:text-gray-700 hover:bg-gray-100 uppercase">Women</a>
                <a href="#" class="block px-3 py-2 hover:text-gray-700 hover:bg-gray-100 uppercase">Kid</a>
                <a href="#"
                    class="block px-3 py-2 hover:text-gray-700 hover:bg-gray-100 uppercase">Accessories</a>
                <a href="#"
                    class="block px-3 py-2 text-red-600 hover:text-gray-700 hover:bg-gray-100 uppercase">Sale</a>
            </div>
        </div>
</nav>
