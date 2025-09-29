<!-- New Arrivals Section -->
<section class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16 ">
    <div class="text-left mb-10">
        <h2 class="text-[40px] font-megumi font-semibold mb-4">New Arrivals You Can’t Miss</h2>
        <p class="text-gray-700 text-base">Fresh drops, bold designs, and iconic fits—get them before they’re gone!</p>
    </div>

    <!-- Category Filters -->
    <div class="flex flex-wrap justify-start gap-4 mb-10">
        <button onclick="filterProducts('all', event)"
            class="px-6 py-4 bg-black text-white text-base font-medium active hover:bg-black hover:text-white filter-btn uppercase tracking-[2px]">
            All
        </button>
        <button onclick="filterProducts('men', event)"
            class="px-6 py-4 bg-gray-100 text-black hover:bg-gray-800 hover:text-white text-base font-medium filter-btn uppercase tracking-[2px]">
            Men
        </button>
        <button onclick="filterProducts('women', event)"
            class="px-6 py-4 bg-gray-100 text-black hover:bg-gray-800 hover:text-white text-base font-medium filter-btn uppercase tracking-[2px]">
            Women
        </button>
        <button onclick="filterProducts('kids', event)"
            class="px-6 py-4 bg-gray-100 text-black hover:bg-gray-800 hover:text-white text-base font-medium filter-btn uppercase tracking-[2px]">
            Kids
        </button>
        <button onclick="filterProducts('accessories', event)"
            class="px-6 py-4 bg-gray-100 text-black hover:bg-gray-800 hover:text-white text-base font-medium filter-btn uppercase tracking-[2px]">
            Accessories
        </button>
    </div>

    @include('products.index', ['products' => $products])
   
    <!-- Show More Button -->
    <div class="mt-16 text-center">
        <button onclick="window.location.href='{{ route('products.list') }}'"
            class="bg-black text-white px-10 py-3 text-xl transition-colors tracking-[2px] font-semibold uppercase">
            Load More
        </button>
    </div>
</section>

<script>
    function filterProducts(category, event) {
        // Remove active class from all buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-black', 'text-white');
            btn.classList.add('bg-gray-100', 'text-black');
        });

        // Add active class to clicked button
        event.target.classList.add('active', 'bg-black', 'text-white');
        event.target.classList.remove('bg-gray-100', 'text-black');

        // Get all product cards
        const products = document.querySelectorAll('[data-categories]');

        // Filter products
        products.forEach(product => {
            const categories = product.dataset.categories.split(' ');
            if (category === 'all' || categories.includes(category)) {
                product.classList.remove('hidden');
            } else {
                product.classList.add('hidden');
            }
        });
    }
</script>
