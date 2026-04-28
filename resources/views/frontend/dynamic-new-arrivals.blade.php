<!-- New Arrivals Section -->
<section class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16 ">
    <div class="text-left mb-10">
        <h2 class="text-[40px] font-megumi font-semibold mb-4">{{ $section->title }}</h2>
        <p class="text-gray-700 text-base">{{ $section->subtitle }}</p>
    </div>

    <!-- Category Filters (Dynamic from top-level categories) -->
    <div class="flex flex-wrap justify-start gap-4 mb-10">
        <button onclick="filterProducts('all', event)"
            class="px-6 py-4 bg-black text-white text-base font-medium active hover:bg-black hover:text-white filter-btn uppercase tracking-[2px]">All</button>
        @foreach ($topCategories as $cat)
        <button onclick="filterProducts('{{ $cat->category_name }}', event)"
            class="px-6 py-4 bg-gray-100 text-black hover:bg-gray-800 hover:text-white text-base font-medium filter-btn uppercase tracking-[2px]">{{
            $cat->category_name }}
        </button>
        @endforeach
    </div>

    @include('products.index', ['products' => $products])

    <!-- Show More Button -->
    <div class="mt-16 text-center">
        <button onclick="window.location.href='{{ route('products.list') }}'"
            class="bg-black text-white px-10 py-3 text-xl transition-colors tracking-[2px] font-semibold uppercase">Load
            More</button>
    </div>
</section>

@push('scripts')
@include('frontend.partials.filter-script')
@endpush