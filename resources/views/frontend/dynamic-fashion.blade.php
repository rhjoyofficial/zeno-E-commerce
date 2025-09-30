<!-- Fashion Section -->
<div x-data="slider({
    categories: @json($section->items->map(function($item) {
        return [
            'title' => $item->title,
            'subtitle' => $item->subtitle,
            'image' => Storage::url($item->image)
        ];
    }))
})" class="relative max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16 ">
    <!-- Hero Banner -->
    <div class="relative h-[400px] bg-cover bg-center flex items-center justify-center text-center"
        style="background-image: url('{{ Storage::url($section->banner_image) }}');">
        <div class="bg-black bg-opacity-40 w-full h-full absolute top-0 left-0"></div>
        <div class="relative z-10 text-white">
            <h2 class="text-5xl font-semibold tracking-wide font-megumi uppercase">{{ $section->title }}</h2>
            <p class="mt-2 text-lg tracking-wider">{{ $section->subtitle }}</p>
        </div>
    </div>

    <!-- Category Slider -->
    <div class="mt-4 overflow-hidden relative">
        <div class="flex gap-3 overflow-x-hidden scroll-smooth no-scrollbar" x-ref="slider"
            @scroll.debounce.50="updateProgress" style="scroll-behavior: smooth;">
            <template x-for="(category, index) in categories" :key="index">
                <div @click="goToCategory(category)"
                    class="relative min-w-[calc(33.333%-1rem)] bg-white cursor-pointer group overflow-hidden">
                    <div class="w-full h-96 overflow-hidden">
                        <img :src="category.image" :alt="category.title"
                            class="w-full h-full object-cover transform transition-transform duration-500">
                    </div>
                    <div class="pt-6 text-left mt-2">
                        <h3 class="text-2xl font-normal font-megumi uppercase" x-text="category.title"></h3>
                        <p class="text-lg text-gray-800 capitalize" x-text="category.subtitle"></p>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex items-center justify-between mt-6 gap-4">
            <div class="flex-1 h-1.5 bg-gray-100 rounded-full">
                <div class="h-full bg-indigo-600 transition-all duration-300 rounded-full"
                    :style="{ width: `${progress}%` }"></div>
            </div>
            <div class="flex items-center gap-2">
                <!-- Your navigation buttons -->
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <section class="max-w-7xl mx-auto mt-10">
        <div class="text-left mb-8">
            <p class="text-gray-800 text-xl font-semibold uppercase">level up</p>
            <h2 class="text-[40px] font-normal text-black mb-2 font-megumi tracking-tight">Your Fashion Game</h2>
        </div>
        @php
        $products = $section->getProducts();
        @endphp
        @include('products.index', ['products' => $products])
        <div class="mt-16 text-center">
            <button onclick="window.location.href='{{ route('products.list') }}'"
                class="bg-black text-white px-10 py-3 text-xl transition-colors tracking-[2px] font-semibold uppercase">Find
                My Style</button>
        </div>
    </section>
</div>