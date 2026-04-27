@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div
    class="flex items-center justify-center gap-2 self-stretch bg-black px-4 py-20 sm:px-10 sm:py-32 md:px-24 md:py-36 lg:px-48 xl:px-[420px] xl:py-[143px]">
    <h1
        class="text-center font-megumi text-3xl leading-none tracking-tight text-white uppercase sm:text-4xl md:text-5xl lg:text-6xl xl:text-[64px]">
        contact us</h1>
</div>
<div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16">
    <!-- Breadcrumb -->
    @php
    $breadcrumbs = [
    ['title' => 'Home', 'url' => '/'],
    ['title' => 'Contact Us']
    ];
    @endphp

    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    {{-- End Breadcrumbs --}}
    <div class="space-y-6">
        <!-- Heading -->
        <h1 class="text-[40px] font-bold tracking-tight text-black font-megumi uppercase  leading-10">LET’S TALK STYLE.</h1>
        <p class="text-base text-black max-w-xl leading-6 tracking-tight">At ZENO, we believe style shouldn't be a
            luxury—it should be a standard. Born in 2025, we set out to redefine fashion for a new generation of
            Bangladeshis—those who crave confidence, comfort, and modern design without breaking the bank.</p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3  mt-16">
        <!-- CUSTOMER SUPPORT -->
        <div class="bg-gray-100 p-8 text-left">
            <h3 class="text-xs font-semibold tracking-[2px] text-gray-600 uppercase">CUSTOMER SUPPORT</h3>
            <p class="text-xl font-normal pt-10 text-gray-900">+880 1710 500 699 <br> support@zenowear.com</p>
        </div>

        <!-- VISIT ZENO HQ -->
        <div class="bg-gray-100 p-8 text-left">
            <h3 class="text-xs font-semibold tracking-[2px] text-gray-600 uppercase">VISIT ZENO HQ</h3>
            <p class="text-xl font-normal pt-10 text-gray-900">Level 4, Elegant Tower, Banani,Dhaka 1213</p>
        </div>

        <!-- COLLAB & PARTNERSHIPS -->
        <div class="bg-gray-100 p-8 text-left">
            <h3 class="text-xs font-semibold tracking-[2px] text-gray-600 uppercase">COLLAB & PARTNERSHIPS</h3>
            <p class="text-xl font-normal pt-10 text-gray-900">collab@zeno.com <br>partner@zeno.com</p>
        </div>
    </div>
</div>
<hr class="h-[2px] bg-[#D7D7D7]">
<div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16">
    <div class="space-y-6">
        <!-- Heading -->
        <h1 class="text-[40px] font-bold tracking-tight text-black font-megumi uppercase leading-10">Drop Us a Line</h1>
        <p class="text-base text-black max-w-xl leading-6 tracking-tight">Have a question, idea, or just want to
            connect? Drop us a line below.We read every message—and we’ll get back to you as soon as we can.</p>
    </div>

    <form class="space-y-7 mt-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="relative">
                <label for="full-name"
                    class="absolute -top-3 left-4 bg-white px-1 text-black text-base font-medium">Full Name</label>
                <input type="text" id="full-name" placeholder="ex: Rahul Ornob"
                    class="w-full border-2 border-2-[#7C7C7C] px-6 py-5 text-black text-base font-normal placeholder-[#7C7C7C] outline-none rounded-none">
            </div>
            <div class="relative">
                <label for="email" class="absolute -top-3 left-4 bg-white px-1 text-black text-base font-medium">Your
                    Email</label>
                <input type="email" id="email" placeholder="ex: rahulornob@gmail.com"
                    class="w-full border-2 border-2-[#7C7C7C] px-6 py-5 text-black text-base font-normal placeholder-[#7C7C7C] outline-none rounded-none">
            </div>
        </div>

        <div class="relative">
            <label for="subject"
                class="absolute -top-3 left-4 bg-white px-1 text-black text-base font-medium">Subject</label>
            <input type="text" id="subject" placeholder="ex: Quick Question About Your New Product"
                class="w-full border-2 border-2-[#7C7C7C] px-6 py-5 text-black text-base font-normal placeholder-[#7C7C7C] outline-none rounded-none">
        </div>

        <div class="relative">
            <label for="message"
                class="absolute -top-3 left-4 bg-white px-1 text-black text-base font-medium">Message</label>
            <textarea id="message" placeholder="ex: Quick Question About Your New Product" rows="6"
                class="w-full border-2 border-2-[#7C7C7C] px-6 py-5 text-black text-base font-normal placeholder-[#7C7C7C] outline-none resize-none rounded-none"></textarea>
        </div>

        <div class="w-full">
            <button type="submit"
                class="w-full bg-black text-white px-6 py-[22px] text-xl font-medium hover:bg-gray-800 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 uppercase tracking-[2px]">
                Send Message
            </button>
        </div>
    </form>
</div>

@endsection