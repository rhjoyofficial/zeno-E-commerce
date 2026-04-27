@extends('layouts.app')
@section('title', 'Delivery & Return Policy')
@push('styles')
    <style>
        .policy-section {
            scroll-margin-top: 2rem;
        }

        .policy-section h2 {
            scroll-margin-top: 6rem;
        }
    </style>
@endpush
@section('content')
    <div class="max-w-7xl mx-auto px-6 py-8" id="cart-container">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Delivery & Return Policy</h1>
            <p class="text-gray-600 max-w-3xl mx-auto">We strive to make your shopping experience seamless. Below you'll find
                all the details about our delivery options and return procedures.</p>
        </div>

        <!-- Quick Navigation -->
        <div id="quick-navigation" class="bg-gray-50 rounded-lg p-6 mb-10">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Navigation</h2>
            <div class="flex flex-wrap gap-3">
                <a href="#delivery"
                    class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition">Delivery
                    Information</a>
                <a href="#shipping"
                    class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition">Shipping
                    Options</a>
                <a href="#returns"
                    class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition">Return
                    Policy</a>
                <a href="#refunds"
                    class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition">Refund
                    Process</a>
                <a href="#exchanges"
                    class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition">Exchanges</a>
            </div>
        </div>

        <!-- Delivery Information Section -->
        <section id="delivery" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Delivery Information</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Processing Time</h3>
                    <p class="text-gray-600">All orders are processed within 1-2 business days. Orders placed on weekends or
                        holidays will be processed the next business day.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Delivery Areas</h3>
                    <p class="text-gray-600 mb-3">We currently deliver to all 50 U.S. states. International shipping is
                        available to select countries.</p>
                    <ul class="list-disc pl-5 text-gray-600 space-y-1">
                        <li>Continental US: 3-7 business days</li>
                        <li>Alaska & Hawaii: 7-14 business days</li>
                        <li>International: 10-21 business days</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Order Tracking</h3>
                    <p class="text-gray-600">Once your order has shipped, you will receive a confirmation email with a
                        tracking number and link to track your package.</p>
                </div>
            </div>
        </section>

        <!-- Shipping Options Section -->
        <section id="shipping" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Shipping Options</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Delivery Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Standard Shipping</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5-7 business days</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$4.99</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Express Shipping</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2-3 business days</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$9.99</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Next Day Delivery</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1 business day</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$19.99</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Free Shipping</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5-10 business days</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Free on orders over $50</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Return Policy Section -->
        <section id="returns" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Return Policy</h2>

            <div class="bg-blue-50 border-l-4 border-black p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-gray-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-black">
                            We offer a 30-day return policy for all unused items in their original packaging with proof of
                            purchase.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Eligibility</h3>
                    <p class="text-gray-600 mb-3">To be eligible for a return, your item must be:</p>
                    <ul class="list-disc pl-5 text-gray-600 space-y-1">
                        <li>In the original packaging</li>
                        <li>Unused and in the same condition as received</li>
                        <li>Returned within 30 days of delivery</li>
                        <li>Accompanied by the original receipt or proof of purchase</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Non-Returnable Items</h3>
                    <p class="text-gray-600 mb-3">Certain types of items cannot be returned:</p>
                    <ul class="list-disc pl-5 text-gray-600 space-y-1">
                        <li>Gift cards</li>
                        <li>Downloadable software products</li>
                        <li>Personal care items</li>
                        <li>Items marked as final sale</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">How to Initiate a Return</h3>
                    <ol class="list-decimal pl-5 text-gray-600 space-y-2">
                        <li>Log in to your account and go to Order History</li>
                        <li>Select the item(s) you wish to return</li>
                        <li>Print the return label and packing slip</li>
                        <li>Package the items securely and attach the return label</li>
                        <li>Drop off at any authorized shipping center</li>
                    </ol>
                </div>
            </div>
        </section>

        <!-- Refund Process Section -->
        <section id="refunds" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Refund Process</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Timeline</h3>
                    <p class="text-gray-600">Once we receive your return, we will inspect it and notify you of the refund
                        status. If approved, refunds will be processed within 7-10 business days.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Refund Methods</h3>
                    <p class="text-gray-600 mb-3">Refunds will be issued to the original payment method. Some exceptions
                        apply:</p>
                    <ul class="list-disc pl-5 text-gray-600 space-y-1">
                        <li>Credit/Debit Cards: 7-10 business days</li>
                        <li>PayPal: 3-5 business days</li>
                        <li>Gift Cards: Immediately upon approval</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Late or Missing Refunds</h3>
                    <p class="text-gray-600">If you haven't received a refund yet, first check your bank account again. Then
                        contact your credit card company as it may take some time before your refund is officially posted.
                        If you've done all of this and you still have not received your refund, please contact us at <a
                            href="mailto:support@example.com" class="text-blue-600 hover:underline">support@example.com</a>.
                    </p>
                </div>
            </div>
        </section>

        <!-- Exchanges Section -->
        <section id="exchanges" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Exchanges</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Damaged or Defective Items</h3>
                    <p class="text-gray-600">We replace items if they are defective or damaged. If you need to exchange it
                        for the same item, contact us at <a href="mailto:support@example.com"
                            class="text-blue-600 hover:underline">support@example.com</a> and include your order number and
                        details about the product you would like to exchange.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Exchanges for Size</h3>
                    <p class="text-gray-600">We are happy to exchange items for a different size, subject to availability.
                        Please follow the return process and place a new order for the desired size.</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Need Help?</h3>
                    <p class="text-gray-600 mb-4">If you have any questions about our delivery and return policy, please
                        contact our customer service team.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#"
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-base font-medium text-white bg-black hover:bg-gray-700">Live
                            Chat</a>
                        <a href="mailto:endbrackets@gmail.com"
                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50">Email
                            Us</a>
                        <a href="tel:+18005551234"
                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50">Call:
                            (+880) 1714-532308</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navContainer = document.getElementById('quick-navigation');
            if (navContainer) {
                const navLinks = navContainer.querySelectorAll('a');
                const activeClasses = ['bg-black', 'text-white'];
                const inactiveClasses = ['bg-white', 'text-gray-700'];

                function setActive(element) {
                    navLinks.forEach(link => {
                        link.classList.remove(...activeClasses);
                        link.classList.add(...inactiveClasses);
                    });

                    element.classList.remove(...inactiveClasses);
                    element.classList.add(...activeClasses);
                }

                if (navLinks.length > 0) {
                    setActive(navLinks[0]);
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