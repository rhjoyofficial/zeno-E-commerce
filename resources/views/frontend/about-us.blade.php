@extends('layouts.app')
@section('title', 'About Us')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
<style>
    @media (min-width: 1280px) {
        .dynamic-margin-start {
            margin-left: calc((100vw - 1280px) / 2);
        }
    }
</style>
@endpush
@push('scripts')
<!-- Include Splide JS -->
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
            new Splide('#fashion-slider', {
                type: 'loop',
                perPage: 1,
                gap: '1rem',
                pagination: false,
                arrows: false,
                breakpoints: {
                    640: { perPage: 1 },
                    768: { perPage: 2 },
                    1024: { perPage: 3 },
                    1280: { perPage: 3 }
                }
            }).mount();
        });
</script>
@endpush
@section('content')
<div
    class="flex items-center justify-center gap-2 self-stretch bg-black px-4 py-20 sm:px-10 sm:py-32 md:px-24 md:py-36 lg:px-48 xl:px-[420px] xl:py-[143px]">
    <h1
        class="text-center font-megumi text-3xl leading-none tracking-tight text-white uppercase sm:text-4xl md:text-5xl lg:text-6xl xl:text-[64px]">
        About Us
    </h1>
</div>

<div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16">
    <!-- Breadcrumb -->
    @php
    $breadcrumbs = [
    ['title' => 'Home', 'url' => '/'],
    ['title' => 'About Us']
    ];
    @endphp
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

    <div class="space-y-6">
        <h1 class="text-[40px] font-bold tracking-tight text-black font-megumi uppercase leading-10">
            Fashion Forward.<br>Bangladeshi Proud.
        </h1>
        <p class="text-base text-black max-w-xl leading-6 tracking-tight">
            At ZENO, we believe style shouldn't be a luxury—it should be a standard. Born in 2025, we set out to
            redefine fashion for a new generation of Bangladeshis—those who crave confidence, comfort, and modern design
            without breaking the bank.
        </p>
    </div>

    <div class="relative h-[50vh] w-full overflow-hidden sm:h-[60vh] md:h-[400px] mt-10">
        <img src="{{ asset('images/zeno-about1.jpg') }}" alt="Fashion model wearing Zeno clothing"
            class="object-cover w-full h-full" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
    </div>
</div>

<hr class="h-[2px] bg-[#D7D7D7]">

<!-- Slider Section -->
<div class="relative ps-[16px] sm:ps-[32px] md:ps-[40px] lg:ps-[24px] pr-0 py-16 overflow-hidden dynamic-margin-start">
    <h1 class="text-[40px] font-bold tracking-tight text-black font-megumi uppercase leading-10">
        what we stand for
    </h1>
    <div id="fashion-slider" class="splide">
        <div class="splide__track">
            <ul class="splide__list">
                @foreach($fashionCategories as $category)
                <li class="splide__slide px-2 lg:max-w-[calc(100%-450px)]">
                    <div class="bg-white cursor-pointer group overflow-hidden">
                        <!-- Content -->
                        <div class="py-4 text-left">
                            <p class="text-base font-medium max-w-2xl leading-6 tracking-tight">{{ $category['title'] }}
                            </p>
                        </div>
                        <!-- Image -->
                        <div class="w-full h-[500px] overflow-hidden">
                            <img src="{{ $category['image'] }}" alt="{{ Str::limit($category['title'], 20) }}"
                                class="w-full h-full object-cover transition-transform duration-500">
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<hr class="h-[2px] bg-[#D7D7D7]">
<div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16">

    <div class="space-y-6">
        <h1 class="text-[40px] font-bold tracking-tight text-black font-megumi uppercase leading-10">
            why zeno?
        </h1>
        <p class="text-base text-black max-w-xl leading-6 tracking-tight">
            Because we’re not just another fashion brand. We’re a movement to raise the standard of style in Bangladesh.
            We want you to feel confident, capable, and unapologetically you—whether you’re at school, at work, or out
            chasing dreams.
        </p>
    </div>

    <div class="relative h-[50vh] w-full overflow-hidden sm:h-[60vh] md:h-[400px] mt-10">
        <img src="{{ asset('images/zeno-about2.jpg') }}" alt="Fashion model wearing Zeno clothing"
            class="object-cover w-full h-full" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
    </div>
</div>
<hr class="h-[2px] bg-[#D7D7D7]">
<div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16">

    <div class="space-y-6">
        <h1 class="text-[40px] font-bold tracking-tight text-black font-megumi uppercase leading-10">
            meet the minds.
        </h1>
        <p class="text-base text-black max-w-xl leading-6 tracking-tight">
            We’re more than a team—we’re the heartbeat of Zeno. From sketch to stitch, everything we create is powered
            by passion, collaboration, and a deep love for bold Bangladeshi fashion.
        </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-10">
        <div class="group">
            <div class="relative mb-4 aspect-square">
                <img src="{{ asset('images/zeno-team1.jpg') }}" alt="Rahul Ornob"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </div>
            <div class="pb-3">
                <h3 class="text-xl font-semibold text-black mb-1">Rahul Ornob</h3>
                <p class="text-black text-xs">Creative Director</p>
            </div>
        </div>

        <div class="group">
            <div class="relative mb-4 aspect-square">
                <img src="{{ asset('images/zeno-team1.jpg') }}" alt="Rifat Hossain"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </div>
            <div class="pb-3">
                <h3 class="text-xl font-semibold text-black mb-1">Rifat Hossain</h3>
                <p class="text-black text-xs">Visual Merchandiser</p>
            </div>
        </div>

        <div class="group">
            <div class="relative mb-4 aspect-square">
                <img src="{{ asset('images/zeno-team1.jpg') }}" alt="Junaid Islam"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </div>
            <div class="pb-3">
                <h3 class="text-xl font-semibold text-black mb-1">Junaid Islam</h3>
                <p class="text-black text-xs">Fashion Content Creator</p>
            </div>
        </div>

        <div class="group">
            <div class="relative mb-4 aspect-square">
                <img src="{{ asset('images/zeno-team1.jpg') }}" alt="Imran Karim"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </div>
            <div class="pb-3">
                <h3 class="text-xl font-semibold text-black mb-1">Imran Karim</h3>
                <p class="text-black text-xs">Product Photographer</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="group">
            <div class="relative mb-4 aspect-square">
                <img src="{{ asset('images/zeno-team1.jpg') }}" alt="Sarah Ahmed"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </div>
            <div class="pb-3">
                <h3 class="text-xl font-semibold text-black mb-1">Sarah Ahmed</h3>
                <p class="text-black text-xs">Brand Strategist</p>
            </div>
        </div>

        <div class="group">
            <div class="relative mb-4 aspect-square">
                <img src="{{ asset('images/zeno-team1.jpg') }}" alt="Fahim Rahman"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </div>
            <div class="pb-3">
                <h3 class="text-xl font-semibold text-black mb-1">Fahim Rahman</h3>
                <p class="text-black text-xs">Digital Marketing Lead</p>
            </div>
        </div>

        <div class="group">
            <div class="relative mb-4 aspect-square">
                <img src="{{ asset('images/zeno-team1.jpg') }}" alt="Nadia Khan"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </div>
            <div class="pb-3">
                <h3 class="text-xl font-semibold text-black mb-1">Nadia Khan</h3>
                <p class="text-black text-xs">Fashion Designer</p>
            </div>
        </div>

        <div class="group">
            <div class="relative mb-4 aspect-square">
                <img src="{{ asset('images/zeno-team1.jpg') }}" alt="Arif Hassan"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </div>
            <div class="pb-3">
                <h3 class="text-xl font-semibold text-black mb-1">Arif Hassan</h3>
                <p class="text-black text-xs">Operations Manager</p>
            </div>
        </div>
    </div>


</div>
@endsection