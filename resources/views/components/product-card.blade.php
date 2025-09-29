<div class="group relative bg-white flex flex-col items-center gap-4 w-full md:h-[415px] overflow-hidden"
    data-categories="{{ implode(' ', $categories) }}">
    <div class="relative overflow-hidden !w-full">

        @if ($stock && $badge && $badge !== 'New')
            <span
                class="absolute top-2 left-2 bg-white text-black px-2 py-1 text-[10px] sm:text-xs font-semibold uppercase rounded-2xl z-10">
                {{ $badge }}
            </span>
        @elseif (!$stock)
            <span
                class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 text-[10px] sm:text-xs font-semibold uppercase rounded-2xl z-10">
                SOLD OUT
            </span>
        @else
            <span
                class="absolute top-2 left-2 bg-white text-black px-2 py-1 text-[10px] sm:text-xs font-semibold uppercase rounded-2xl z-10">
                NEW
            </span>
        @endif

        <button class="absolute top-2 right-2 p-2 text-black hover:text-gray-700 z-10">
            <svg width="24" height="21" viewBox="0 0 24 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M16.6928 1.5C15.732 1.51494 14.7922 1.78315 13.9682 2.27753C13.1442 2.77192 12.4653 3.47497 12 4.3157C11.5347 3.47497 10.8557 2.77192 10.0318 2.27753C9.20779 1.78315 8.26795 1.51494 7.30716 1.5C5.77554 1.56654 4.33251 2.23656 3.29332 3.36368C2.25414 4.49079 1.70328 5.98338 1.76109 7.51535C1.76109 11.395 5.8447 15.6322 9.26961 18.5051C10.0343 19.1477 11.0011 19.5 12 19.5C12.9988 19.5 13.9657 19.1477 14.7304 18.5051C18.1553 15.6322 22.2389 11.395 22.2389 7.51535C22.2967 5.98338 21.7458 4.49079 20.7066 3.36368C19.6675 2.23656 18.2244 1.56654 16.6928 1.5ZM13.6339 17.1996C13.1768 17.5852 12.598 17.7968 12 17.7968C11.4019 17.7968 10.8232 17.5852 10.366 17.1996C5.98207 13.5213 3.46757 9.99231 3.46757 7.51535C3.40924 6.43576 3.78017 5.3769 4.49945 4.56971C5.21873 3.76251 6.22801 3.27248 7.30716 3.20648C8.38631 3.27248 9.39558 3.76251 10.1149 4.56971C10.8341 5.3769 11.2051 6.43576 11.1467 7.51535C11.1467 7.74164 11.2366 7.95867 11.3967 8.11868C11.5567 8.2787 11.7737 8.36859 12 8.36859C12.2263 8.36859 12.4433 8.2787 12.6033 8.11868C12.7633 7.95867 12.8532 7.74164 12.8532 7.51535C12.7949 6.43576 13.1658 5.3769 13.8851 4.56971C14.6044 3.76251 15.6137 3.27248 16.6928 3.20648C17.772 3.27248 18.7812 3.76251 19.5005 4.56971C20.2198 5.3769 20.5907 6.43576 20.5324 7.51535C20.5324 9.99231 18.0179 13.5213 13.6339 17.1962V17.1996Z"
                    fill="black" />
            </svg>
        </button>

        <div class="relative overflow-hidden w-full h-[290px] sm:h-[310px] md:h-[350px]">
            <img src="{{ Storage::url($image) }}" alt="{{ $title }}" class="!w-full h-full object-cover" />
        </div>

        <div
            class="absolute inset-x-0 bottom-0 translate-y-full opacity-0 scale-50 group-hover:translate-y-0 group-hover:opacity-100 group-hover:scale-100 transition-all duration-500 ease-in-out z-20">
            <div class="flex items-end justify-center gap-2 p-2 sm:p-3 md:p-4">
                @if ($stock)
                    <button onclick="window.location.href='{{ route('products.show', $id) }}'"
                        class="flex justify-center items-center gap-2 px-6 py-4 tracking-[2px] bg-white text-black text-[16px] font-bold uppercase cursor-pointer">
                        Buy
                    </button>
                    <button data-product-id="{{ $id }}"
                        class="add-to-cart flex justify-center items-center gap-2 px-6 py-4 tracking-[2px] bg-white text-black text-[16px] font-bold uppercase">
                        Add Cart
                    </button>
                @else
                    <button
                        class="flex flex-1 justify-center items-center gap-2 px-6 py-4 bg-white text-black text-[16px] font-bold tracking-[2px] uppercase">
                        Get Alert
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-0 items-center justify-end self-stretch p-0 m-0">
        <p class="text-xl sm:text-lg font-semibold tracking-tight">{{ $title }}</p>
        @if (isset($discountPrice))
            <p class="text-base sm:text-xl font-semibold tracking-tight">
                <span class="text-red-500 text-base line-through">${{ number_format($price, 2) }}</span>
                <span class="ml-2">${{ number_format($discountPrice, 2) }}</span>
            </p>
        @else
            <p class="text-base sm:text-xl font-semibold tracking-tight">${{ number_format($price, 2) }}</p>
        @endif
    </div>
</div>
