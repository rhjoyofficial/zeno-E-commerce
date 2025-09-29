@extends('layouts.master-layout')
@section('title', 'Exchange Policy')
@push('styles')
    <style>
        .policy-section {
            scroll-margin-top: 2rem;
        }
    </style>
@endpush
@section('content')
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-black mb-4">Exchange Policy</h1>
            <p class="text-gray-700 max-w-3xl mx-auto">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <!-- Quick Navigation -->
        <div id="quick-navigation" class="bg-gray-100 p-6 mb-10">
            <h2 class="text-lg font-semibold text-black mb-4">Quick Navigation</h2>
            <div class="flex flex-wrap gap-3">
                <a href="#eligibility"
                    class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Eligibility Criteria</a>
                <a href="#timeframe" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Exchange
                    Timeframe</a>
                <a href="#process" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Exchange
                    Process</a>
                <a href="#conditions" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Item
                    Conditions</a>
                <a href="#non-exchangeable"
                    class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Non-Exchangeable
                    Items</a>
                <a href="#costs" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Shipping
                    Costs</a>
                <a href="#exceptions"
                    class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Exceptions</a>
            </div>
        </div>

        <!-- Introduction -->
        <div class="mb-12">
            <p class="text-gray-700">We want you to be completely satisfied with your purchase. If you are not entirely
                happy with your item, we offer exchanges for most products within a specified period. This Exchange Policy
                outlines the terms and conditions for exchanging items purchased from our store.</p>
        </div>

        <!-- Eligibility Criteria Section -->
        <section id="eligibility" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Eligibility Criteria</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">To be eligible for an exchange, your item must meet the following criteria:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>The item must be in its original condition</li>
                        <li>It must not be worn, used, or washed</li>
                        <li>All original tags must be attached</li>
                        <li>The item must be in its original packaging</li>
                        <li>You must provide proof of purchase (order number or receipt)</li>
                        <li>The exchange must be requested within 14 days of delivery</li>
                    </ul>
                </div>

                <div class="bg-gray-100 border-l-4 border-black p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-black">
                                Exchanges are only available for items that are defective, damaged, or incorrect upon
                                receipt.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Exchange Timeframe Section -->
        <section id="timeframe" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Exchange Timeframe</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">You have <strong>14 days</strong> from the date of delivery to request an
                        exchange.</p>
                </div>

                <div>
                    <p class="text-gray-700">Once we receive your returned item, we will process your exchange within
                        <strong>3-5 business days</strong>.</p>
                </div>

                <div>
                    <p class="text-gray-700">The shipping time for your exchanged item will depend on your location and the
                        shipping method selected.</p>
                </div>
            </div>
        </section>

        <!-- Exchange Process Section -->
        <section id="process" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Exchange Process</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Step-by-Step Process</h3>
                    <ol class="list-decimal pl-5 text-gray-700 space-y-2">
                        <li>Contact our customer service team at <a href="mailto:exchanges@example.com"
                                class="text-black underline">exchanges@example.com</a> within 14 days of receiving your
                            order</li>
                        <li>Provide your order number and details about the item you wish to exchange</li>
                        <li>Specify the reason for exchange and the replacement item you prefer</li>
                        <li>We will email you a return authorization and shipping label if applicable</li>
                        <li>Package the item securely in its original packaging</li>
                        <li>Include all original tags, labels, and accessories</li>
                        <li>Ship the item back to us using the provided label or your preferred carrier</li>
                        <li>Once we receive and inspect the returned item, we will process your exchange</li>
                        <li>You will receive a confirmation email when your exchange ships</li>
                    </ol>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">In-Store Exchanges</h3>
                    <p class="text-gray-700">If you have a physical store location nearby, you may also exchange items in
                        person. Please bring your original receipt or order confirmation email.</p>
                </div>
            </div>
        </section>

        <!-- Item Conditions Section -->
        <section id="conditions" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Item Conditions</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">For an exchange to be processed, returned items must meet the following
                        conditions:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>Unworn, unused, and unwashed</li>
                        <li>Original tags still attached</li>
                        <li>No stains, marks, or alterations</li>
                        <li>Original packaging included</li>
                        <li>All accessories and documentation included</li>
                        <li>No signs of wear or damage caused by the customer</li>
                    </ul>
                </div>

                <div>
                    <p class="text-gray-700">We reserve the right to refuse exchanges for items that do not meet these
                        conditions.</p>
                </div>
            </div>
        </section>

        <!-- Non-Exchangeable Items Section -->
        <section id="non-exchangeable" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Non-Exchangeable Items</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">The following items cannot be exchanged:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>Underwear, swimwear, and hosiery for hygiene reasons</li>
                        <li>Earrings for hygiene reasons</li>
                        <li>Personal care products</li>
                        <li>Downloadable software or digital products</li>
                        <li>Gift cards</li>
                        <li>Items marked as "Final Sale" or "Non-Exchangeable"</li>
                        <li>Custom-made or personalized items</li>
                        <li>Items purchased from third-party sellers</li>
                    </ul>
                </div>

                <div>
                    <p class="text-gray-700">For defective or damaged non-exchangeable items, please contact our customer
                        service team for assistance.</p>
                </div>
            </div>
        </section>

        <!-- Shipping Costs Section -->
        <section id="costs" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Shipping Costs</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Return Shipping</h3>
                    <p class="text-gray-700">Customers are responsible for return shipping costs unless the exchange is due
                        to our error (wrong item shipped, defective item, etc.).</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Exchange Shipping</h3>
                    <p class="text-gray-700">We will cover the shipping costs for sending the exchanged item to you.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">International Exchanges</h3>
                    <p class="text-gray-700">For international orders, customers are responsible for all return shipping
                        costs and any applicable customs duties or taxes.</p>
                </div>
            </div>
        </section>

        <!-- Exceptions Section -->
        <section id="exceptions" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Exceptions</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Defective or Damaged Items</h3>
                    <p class="text-gray-700">If you receive a defective or damaged item, please contact us within 48 hours
                        of delivery. We will arrange for a free return and exchange.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Wrong Item Received</h3>
                    <p class="text-gray-700">If you receive the wrong item, contact us immediately. We will cover all
                        shipping costs for the return and exchange.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Out of Stock Items</h3>
                    <p class="text-gray-700">If the item you want to exchange for is out of stock, we will offer you a store
                        credit or refund instead.</p>
                </div>
            </div>
        </section>

        <!-- Contact Information -->
        <div class="bg-gray-100 p-6 mt-12">
            <h3 class="text-xl font-semibold text-black mb-4">Exchange Questions?</h3>
            <p class="text-gray-700 mb-4">If you have any questions about our exchange policy, please contact us:</p>
            <ul class="text-gray-700 space-y-2">
                <li>By email: <a href="mailto:exchanges@example.com" class="text-black underline">exchanges@example.com</a>
                </li>
                <li>By phone: <a href="tel:+18005551234" class="text-black underline">(800) 555-1234</a></li>
                <li>Hours: Monday-Friday, 9AM-5PM EST</li>
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navContainer = document.getElementById('quick-navigation');
            if (navContainer) {
                const navLinks = navContainer.querySelectorAll('a');
                const activeClass = 'bg-black text-white';

                function setActive(element) {
                    navLinks.forEach(link => {
                        link.classList.remove('bg-black', 'text-white');
                        link.classList.add('bg-white', 'text-black');
                    });

                    element.classList.remove('bg-white', 'text-black');
                    element.classList.add('bg-black', 'text-white');
                }

                navLinks.forEach(link => {
                    link.addEventListener('click', (event) => {
                        event.preventDefault();
                        setActive(event.currentTarget);
                        setTimeout(() => {
                            const targetId = event.currentTarget.getAttribute('href')
                                .substring(1);
                            const targetSection = document.getElementById(targetId);
                            if (targetSection) {
                                targetSection.scrollIntoView({
                                    behavior: 'smooth'
                                });
                            }
                        }, 50);
                    });
                });
            }
        });
    </script>
@endpush
