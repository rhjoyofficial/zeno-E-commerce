@extends('layouts.master-layout')
@section('title', 'Privacy Policy')
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
            <h1 class="text-3xl md:text-4xl font-bold text-black mb-4">Privacy Policy</h1>
            <p class="text-gray-700 max-w-3xl mx-auto">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <!-- Quick Navigation -->
        <div id="quick-navigation" class="bg-gray-100 p-6 mb-10">
            <h2 class="text-lg font-semibold text-black mb-4">Quick Navigation</h2>
            <div class="flex flex-wrap gap-3">
                <a href="#information"
                    class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Information We Collect</a>
                <a href="#usage" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">How We Use
                    Information</a>
                <a href="#sharing"
                    class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Information Sharing</a>
                <a href="#security" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Data
                    Security</a>
                <a href="#rights" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Your
                    Rights</a>
                <a href="#cookies"
                    class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Cookies</a>
                <a href="#changes" class="px-4 py-2 bg-white border border-gray-300 text-black hover:bg-gray-800 hover:text-white ">Policy
                    Changes</a>
            </div>
        </div>

        <!-- Introduction -->
        <div class="mb-12">
            <p class="text-gray-700 mb-4">Your privacy is important to us. This Privacy Policy explains how we collect, use,
                disclose, and safeguard your information when you visit our website or make a purchase from us.</p>
            <p class="text-gray-700">Please read this privacy policy carefully. If you do not agree with the terms of this
                privacy policy, please do not access the site.</p>
        </div>

        <!-- Information We Collect Section -->
        <section id="information" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Information We Collect</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Personal Information</h3>
                    <p class="text-gray-700">We may collect personal information that you voluntarily provide to us when
                        you:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>Register on our website</li>
                        <li>Place an order</li>
                        <li>Subscribe to our newsletter</li>
                        <li>Contact us with inquiries</li>
                        <li>Participate in surveys or promotions</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mb-3">Automatically Collected Information</h3>
                    <p class="text-gray-700">When you visit our website, we automatically collect certain information about
                        your device, including:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>IP address</li>
                        <li>Browser type</li>
                        <li>Operating system</li>
                        <li>Referring URLs</li>
                        <li>Pages visited and time spent</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- How We Use Information Section -->
        <section id="usage" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">How We Use Information</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">We use the information we collect to:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>Process and fulfill your orders</li>
                        <li>Communicate with you about your orders, products, services, and promotions</li>
                        <li>Provide customer support</li>
                        <li>Improve our website, products, and services</li>
                        <li>Prevent fraudulent transactions and monitor against theft</li>
                        <li>Personalize your experience on our website</li>
                        <li>Send you marketing communications (where you have agreed to this)</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Information Sharing Section -->
        <section id="sharing" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Information Sharing</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">We may share your personal information with:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>Service providers who assist in our business operations (payment processing, shipping, etc.)
                        </li>
                        <li>Law enforcement or government agencies when required by law</li>
                        <li>Third parties in connection with a business transfer, such as a merger or acquisition</li>
                    </ul>
                </div>

                <div>
                    <p class="text-gray-700">We do not sell, trade, or otherwise transfer your personally identifiable
                        information to outside parties without providing you with advance notice, except as described in
                        this Privacy Policy.</p>
                </div>
            </div>
        </section>

        <!-- Data Security Section -->
        <section id="security" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Data Security</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">We implement appropriate security measures to protect your personal information
                        from unauthorized access, alteration, disclosure, or destruction.</p>
                </div>

                <div>
                    <p class="text-gray-700">While we strive to use commercially acceptable means to protect your personal
                        information, no method of transmission over the Internet or electronic storage is 100% secure. We
                        cannot guarantee absolute security of your data.</p>
                </div>
            </div>
        </section>

        <!-- Your Rights Section -->
        <section id="rights" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Your Rights</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">Depending on your location, you may have the following rights regarding your
                        personal information:</p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1 mt-3">
                        <li>The right to access the personal information we hold about you</li>
                        <li>The right to request correction of inaccurate personal information</li>
                        <li>The right to request deletion of your personal information</li>
                        <li>The right to object to processing of your personal information</li>
                        <li>The right to data portability</li>
                        <li>The right to withdraw consent at any time</li>
                    </ul>
                </div>

                <div>
                    <p class="text-gray-700">To exercise any of these rights, please contact us using the contact
                        information provided below.</p>
                </div>
            </div>
        </section>

        <!-- Cookies Section -->
        <section id="cookies" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Cookies</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">We use cookies and similar tracking technologies to track activity on our
                        website and store certain information.</p>
                </div>

                <div>
                    <p class="text-gray-700">You can instruct your browser to refuse all cookies or to indicate when a
                        cookie is being sent. However, if you do not accept cookies, you may not be able to use some
                        portions of our website.</p>
                </div>
            </div>
        </section>

        <!-- Policy Changes Section -->
        <section id="changes" class="policy-section mb-12">
            <h2 class="text-2xl font-bold text-black mb-6 pb-2 border-b border-gray-300">Policy Changes</h2>

            <div class="space-y-6">
                <div>
                    <p class="text-gray-700">We may update our Privacy Policy from time to time. We will notify you of any
                        changes by posting the new Privacy Policy on this page and updating the "Last updated" date.</p>
                </div>

                <div>
                    <p class="text-gray-700">You are advised to review this Privacy Policy periodically for any changes.
                        Changes to this Privacy Policy are effective when they are posted on this page.</p>
                </div>
            </div>
        </section>

        <!-- Contact Information -->
        <div class="bg-gray-100 p-6 mt-12">
            <h3 class="text-xl font-semibold text-black mb-4">Contact Us</h3>
            <p class="text-gray-700 mb-4">If you have any questions about this Privacy Policy, please contact us:</p>
            <ul class="text-gray-700 space-y-2">
                <li>By email: <a href="mailto:privacy@example.com" class="text-black underline">privacy@example.com</a></li>
                <li>By phone: <a href="tel:+18005551234" class="text-black underline">(800) 555-1234</a></li>
                <li>By mail: 123 Privacy Street, Data City, DC 12345</li>
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
