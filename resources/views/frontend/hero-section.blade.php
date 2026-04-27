<!-- Hero Section -->
<div class="relative overflow-hidden h-[90vh] min-h-[400px]">
    <!-- Single Slide Container -->
    <div class="h-full">
        <div class="relative w-full h-full">
            <!-- Background Image -->
            <img src="{{ asset('images/Hero.jpg') }}" alt="Hero Image"
                class="absolute inset-0 w-full h-full object-cover">

            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>

            <!-- Content -->
            <div
                class="relative z-10 h-full max-w-7xl mx-auto items-center flex flex-col justify-end pb-10 px-6 md:px-10">
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center w-full text-white gap-6 md:gap-0">
                    <div class="max-w-xl">
                        <h6 class="text-2xl md:text-3xl font-medium leading-tight mb-6 uppercase">
                            Trendsetting looks await,<br>
                            step into the spotlight
                        </h6>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="window.location.href='{{ route('products.list') }}'"
                            class="bg-white text-black px-6 md:px-10 py-2 md:py-3 text-base md:text-xl font-semibold uppercase hover:bg-gray-200 transition">
                            Men
                        </button>
                        <button onclick="window.location.href='{{ route('products.list') }}'"
                            class="bg-white text-black px-6 md:px-10 py-2 md:py-3 text-base md:text-xl font-semibold uppercase hover:bg-gray-200 transition">
                            Women
                        </button>
                        <button onclick="window.location.href='{{ route('products.list') }}'"
                            class="bg-white text-black px-6 md:px-10 py-2 md:py-3 text-base md:text-xl font-semibold uppercase hover:bg-gray-200 transition">
                            Kid
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->
