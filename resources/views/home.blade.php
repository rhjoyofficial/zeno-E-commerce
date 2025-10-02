<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-scrollbar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Fav Icon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" sizes="32x32" />
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}" sizes="180x180" />
    {{-- Font Awesome Icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Swiper Slider --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    {{-- Alpine JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- google albert font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    {{-- Preloader CSS --}}
    <link rel="stylesheet" href="{{ asset('css/preloader.css') }}">

    <title>Zeno - Shopping</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-white text-black font-sans antialiased no-scrollbar" x-data="{
    isMobileMenuOpen: false,
    searchOpen: false,
    activeMenu: null,
    activeSubmenu: null,
    activeUser: false,
    productCart: false
}">
    <!-- Preloader -->
    @include('partials.preloader')
    @include('partials.loading-overlay')
    @include('components.notification')
    {{-- @include('components.dynamic-navigation') --}}

    @include('frontend.navbar')
    @include('partials.flash-messages')
    @include('frontend.heroSection')
    {{-- --}}

    @foreach ($homeSections as $section)
    @if ($section->type === 'new_arrivals')
    @include('frontend.dynamic-new-arrivals', ['section' => $section])
    @elseif ($section->type === 'fashion')
    @include('frontend.dynamic-fashion', ['section' => $section])
    @endif
    <hr>
    @endforeach

    @include('partials.membership')
    <hr>
    @include('frontend.footer')
    @include('components.product-cart-popup')

    <div id="notification-container" class="fixed top-20 right-4 z-[9999] space-y-3 w-80 max-w-[90vw]"></div>

    <!-- Scripts -->
    <script src="{{ asset('js/preloader.js') }}"></script>
    <script src="{{ asset('js/helper.js') }}"></script>
    {{-- Swiper JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Progress Bar as Indicator (Non-interactive) ---
            const progressBarThumb = document.querySelector('.custom-slider-thumb');

            // Initial update to set the progress bar before Swiper init
            if (progressBarThumb) {
                progressBarThumb.style.width = '0%';
            }

            const fashionCategorySlider = new Swiper('.fashionCategorySlider', {
                slidesPerView: 3,
                spaceBetween: 24,
                loop: false,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 24,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 24,
                    },
                },
                on: {
                    init: function () {
                        console.log('Swiper initialized, updating progress bar');
                        updateProgressBar(this);
                    },
                    slideChange: function () {
                        updateProgressBar(this);
                    },
                    slideChangeTransitionEnd: function () {
                        updateProgressBar(this);
                    },
                },
            });

            // Function to update the progress bar as an indicator of slides left
            function updateProgressBar(swiperInstance) {
                if (!progressBarThumb) return;

                const totalSlides = swiperInstance.slides.length;
                const slidesPerView = swiperInstance.params.slidesPerView;
                const maxIndex = Math.max(0, totalSlides - slidesPerView);

                console.log('Total slides:', totalSlides, 'Slides per view:', slidesPerView, 'Max index:', maxIndex);

                if (maxIndex === 0) {
                    // If all slides are visible, fill completely
                    progressBarThumb.style.width = '100%';
                    return;
                }

                const currentIndex = swiperInstance.activeIndex;
                console.log('Current index:', currentIndex);

                // NEW CALCULATION: Progress based on how much content we've viewed
                // We start with some progress (since we're already viewing slidesPerView slides)
                // and end at 100% when we reach the maximum scroll position

                // Calculate how many slides we've "consumed" (viewed + scrolled past)
                const consumedSlides = currentIndex + slidesPerView;

                // Calculate progress based on total consumable content
                const progressPercentage = (consumedSlides / totalSlides) * 100;

                console.log('Consumed slides:', consumedSlides, 'Progress percentage:', progressPercentage);

                // Update the progress bar width
                progressBarThumb.style.width = `${Math.min(100, Math.max(0, progressPercentage))}%`;
                console.log('Final width:', progressBarThumb.style.width);
            }

            // Force an initial update after a small delay to ensure Swiper is ready
            setTimeout(() => {
                updateProgressBar(fashionCategorySlider);
            }, 100);
        });
    </script>
    {{-- Swiper JS Fashion Slider Close --}}
    <!-- Global Config -->
    <script>
        window.appConfig = {
            routes: {
                productVariants: "{{ route('products.variants') }}",
                cartAdd: "{{ route('cart.add') }}",
            },
            csrfToken: "{{ csrf_token() }}",
        };
    </script>
    <script src="{{ asset('js/product-popup.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Update quantity
            function updateQuantity(itemId, quantity) {
                const url = '{{ route("cart.update", ":item") }}'.replace(':item', itemId);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ qty: quantity })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notifications.success('Cart updated successfully');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            notifications.error('Error updating cart');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        notifications.error('Error updating cart');
                    });
            }

            // Remove item
            function removeItem(itemId) {
                const url = '{{ route("cart.remove", ":item") }}'.replace(':item', itemId);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notifications.success('Item removed from cart');
                            document.querySelectorAll('.cart-counter').forEach(el => {
                                el.textContent = data.cart_count;
                            });

                            document.querySelector(`.cart-item[data-id="${itemId}"]`).remove();

                            if (document.querySelectorAll('.cart-item').length === 0) {
                                setTimeout(() => location.reload(), 1000);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        notifications.error('Error removing item from cart');
                    });
            }

            // Event listeners
            document.querySelectorAll('.increase-qty').forEach(button => {
                button.addEventListener('click', function () {
                    const itemId = this.dataset.itemId;
                    const input = this.parentNode.querySelector('.quantity-input');
                    input.value = parseInt(input.value) + 1;
                    updateQuantity(itemId, input.value);
                });
            });

            document.querySelectorAll('.decrease-qty').forEach(button => {
                button.addEventListener('click', function () {
                    const itemId = this.dataset.itemId;
                    const input = this.parentNode.querySelector('.quantity-input');
                    if (input.value > 1) {
                        input.value = parseInt(input.value) - 1;
                        updateQuantity(itemId, input.value);
                    }
                });
            });

            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function () {
                    const itemId = this.dataset.itemId;
                    if (this.value < 1) this.value = 1;
                    updateQuantity(itemId, this.value);
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function () {
                    const itemId = this.dataset.itemId;
                    if (confirm('Are you sure you want to remove this item from your cart?')) {
                        removeItem(itemId);
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>