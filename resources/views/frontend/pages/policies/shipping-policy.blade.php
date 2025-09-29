@extends('layouts.master-layout')
@section('title', 'Shipping Policy')
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
            <h1 class="text-3xl md:text-4xl font-bold text-black mb-4">Shipping Policy</h1>
            <p class="text-gray-700 max-w-3xl mx-auto">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <!-- Quick Navigation -->
        <div id="quick-navigation" class="bg-gray-100 p-6 mb-10">
            <h2 class="text-lg font-semibold text-black mb-4">Quick Navigation</h2>
            <div class="flex flex-wrap gap-3">
                <a href="#processing" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Order
                    Processing</a>
                <a href="#locations" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Shipping
                    Locations</a>
                <a href="#options" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Shipping
                    Options</a>
                <a href="#timeframes"
                    class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Delivery Timeframes</a>
                <a href="#tracking" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Order
                    Tracking</a>
                <a href="#issues" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Shipping
                    Issues</a>
                <a href="#international"
                    class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">International
                    Shipping</a>
            </div>
        </div>

        <!-- Introduction -->
        <div class="mb-12">
            <p class="text-gray-700">This Shipping Policy outlines our practices regarding order processing, shipping
                methods, delivery timeframes, and related information. Please read this policy carefully to understand how
                we handle the shipment of your orders.</p>
        </div>

        <!-- Order Processing Section -->
        <section id="processing" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Order Processing</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">All orders are processed within 1-2 business days (Monday through Friday,
                        excluding holidays) after receiving your order confirmation email.</p>
                </div>

                <div>
                    <p class="text-gray-700">You will receive another notification when your order has shipped. During peak
                        seasons or sale events, processing times may be slightly longer.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Order Verification</h3>
                    <p class="text-gray-700">For security purposes, we may need to verify your information before processing
                        an order. This may delay processing by 24-48 hours.</p>
                </div>
            </div>
        </section>

        <!-- Shipping Locations Section -->
        <section id="locations" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Shipping Locations</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">We currently ship to the following locations:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>All 50 U.S. states</li>
                        <li>Puerto Rico</li>
                        <li>U.S. Virgin Islands</li>
                        <li>APO/FPO/DPO addresses</li>
                        <li>Select international countries (see International Shipping section)</li>
                    </ul>
                </div>

                <div>
                    <p class="text-gray-700">We do not ship to P.O. boxes for certain products due to carrier restrictions.
                        Please provide a physical address for delivery.</p>
                </div>
            </div>
        </section>

        <!-- Shipping Options Section -->
        <section id="options" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Shipping Options</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-sm font-medium text-black border-b border-gray-300">Service
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-black border-b border-gray-300">Delivery
                                Time</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-black border-b border-gray-300">Cost
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">Standard Shipping</td>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">5-7 business days</td>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">$4.99</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">Express Shipping</td>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">2-3 business days</td>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">$9.99</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">Next Day Delivery</td>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">1 business day</td>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">$19.99</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">Free Shipping</td>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">5-10 business days</td>
                            <td class="px-6 py-4 text-sm text-gray-700 border-b border-gray-300">Free on orders over $50
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Delivery Timeframes Section -->
        <section id="timeframes" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Delivery Timeframes</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">Delivery timeframes begin from the date your order ships, not the date you
                        place your order. The following are estimated delivery timeframes:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>Continental US: 3-7 business days</li>
                        <li>Alaska & Hawaii: 7-14 business days</li>
                        <li>Puerto Rico & US Virgin Islands: 7-14 business days</li>
                        <li>APO/FPO/DPO: 10-21 business days</li>
                        <li>International: 10-21 business days (varies by country)</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Factors Affecting Delivery</h3>
                    <p class="text-gray-700">Please note that delivery timeframes are estimates and not guarantees. The
                        following factors may affect delivery times:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>Weather conditions</li>
                        <li>Carrier delays</li>
                        <li>Customs clearance for international orders</li>
                        <li>Peak holiday seasons</li>
                        <li>Incorrect or incomplete address information</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Order Tracking Section -->
        <section id="tracking" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Order Tracking</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">Once your order has shipped, you will receive a shipping confirmation email
                        that includes your tracking number and a link to track your package.</p>
                </div>

                <div>
                    <p class="text-gray-700">Tracking information may take 24-48 hours to update in the carrier's system
                        after your order has shipped.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Tracking Issues</h3>
                    <p class="text-gray-700">If you experience issues with tracking your package or if tracking information
                        hasn't updated for several days, please contact us for assistance.</p>
                </div>
            </div>
        </section>

        <!-- Shipping Issues Section -->
        <section id="issues" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Shipping Issues</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Failed Deliveries</h3>
                    <p class="text-gray-700">If a delivery attempt fails because no one was available to receive the
                        package, the carrier will typically leave a notice with instructions for pickup or redelivery.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Incorrect Address</h3>
                    <p class="text-gray-700">Please ensure your shipping address is correct at the time of order placement.
                        We are not responsible for orders shipped to incorrect addresses provided by the customer.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Lost or Stolen Packages</h3>
                    <p class="text-gray-700">If your tracking information shows that your package was delivered but you have
                        not received it, please contact us immediately. We will work with the carrier to resolve the issue.
                    </p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Damaged Shipments</h3>
                    <p class="text-gray-700">If your order arrives damaged, please contact us within 48 hours of delivery.
                        We may require photos of the damaged items and packaging to process your claim.</p>
                </div>
            </div>
        </section>

        <!-- International Shipping Section -->
        <section id="international" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">International Shipping</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">We ship to select international countries. Additional charges may apply,
                        including:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>International shipping fees</li>
                        <li>Customs duties</li>
                        <li>Import taxes</li>
                        <li>Other fees imposed by the destination country</li>
                    </ul>
                </div>

                <div>
                    <p class="text-gray-700">These additional charges are the responsibility of the recipient and are not
                        included in the product price or shipping cost paid at checkout.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Customs and Import Taxes</h3>
                    <p class="text-gray-700">International shipments may be subject to customs clearance procedures, which
                        can cause delays beyond our original delivery estimates.</p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Restricted Items</h3>
                    <p class="text-gray-700">Some products may not be available for international shipping due to
                        restrictions imposed by the destination country.</p>
                </div>
            </div>
        </section>

        <!-- Contact Information -->
        <div class="bg-gray-100 p-6 mt-12">
            <h3 class="text-xl font-semibold text-black mb-4">Shipping Questions?</h3>
            <p class="text-gray-700 mb-4">If you have any questions about our shipping policy, please contact us:</p>
            <ul class="text-gray-700 space-y-2">
                <li>By email: <a href="mailto:shipping@example.com" class="text-black underline">shipping@example.com</a>
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
