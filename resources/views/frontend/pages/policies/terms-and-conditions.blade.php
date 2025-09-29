@extends('layouts.master-layout')
@section('title', 'Terms & Conditions')
@push('styles')
    <style>
        .policy-section {
            scroll-margin-top: 2rem;
        }

        .terms-list {
            list-style-type: none;
            padding-left: 0;
        }

        .terms-list li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .terms-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #000;
            font-weight: bold;
        }

        .section-number {
            width: 2rem;
            height: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #000;
            color: #fff;
            margin-right: 0.75rem;
            font-weight: bold;
        }
    </style>
@endpush
@section('content')
    <div class="max-w-4xl mx-auto px-6 py-10">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-black mb-4 tracking-wide">TERMS & CONDITIONS</h1>
            <p class="text-gray-700 uppercase text-sm tracking-wider">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <!-- Introduction -->
        <div class="mb-12 border-b border-gray-300 pb-8">
            <p class="text-gray-700 leading-relaxed">Please read these Terms and Conditions carefully before using our
                website and making any purchases. These Terms govern your access to and use of our services, and constitute
                a binding legal agreement between you and our company.</p>
        </div>

        <!-- Agreement Acceptance -->
        <div class="mb-10 bg-gray-50 p-6 border-l-4 border-black">
            <p class="text-black font-medium">By accessing or using our website, you acknowledge that you have read,
                understood, and agree to be bound by these Terms. If you do not agree with these Terms, you must not use our
                website or services.</p>
        </div>

        <!-- Terms Content -->
        <div class="space-y-12">
            <!-- Section 1 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">1</span>
                    DEFINITIONS
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>In these Terms, the following definitions apply:</p>
                    <ul class="terms-list">
                        <li>"Company", "we", "us", or "our" refers to our business entity</li>
                        <li>"You" or "your" refers to the individual accessing or using our services</li>
                        <li>"Website" refers to our online platform accessible via our domain</li>
                        <li>"Products" refers to the goods available for purchase on our website</li>
                        <li>"Services" refers to all services provided through our website</li>
                    </ul>
                </div>
            </section>

            <!-- Section 2 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">2</span>
                    ACCOUNT REGISTRATION
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>To access certain features of our website, you may be required to create an account. You agree to:
                    </p>
                    <ul class="terms-list">
                        <li>Provide accurate, current, and complete information during registration</li>
                        <li>Maintain and promptly update your account information</li>
                        <li>Maintain the security of your password and accept all risks of unauthorized access</li>
                        <li>Notify us immediately of any unauthorized use of your account</li>
                        <li>Be responsible for all activities that occur under your account</li>
                    </ul>
                </div>
            </section>

            <!-- Section 3 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">3</span>
                    PRODUCT INFORMATION & PRICING
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>We strive to ensure that all product information and pricing is accurate. However, we may
                        occasionally make errors.</p>
                    <ul class="terms-list">
                        <li>We reserve the right to correct any errors, inaccuracies, or omissions</li>
                        <li>We may change or update information at any time without notice</li>
                        <li>We reserve the right to modify or discontinue products without notice</li>
                        <li>All prices are subject to change without notice</li>
                        <li>We are not responsible for typographical errors regarding price or product information</li>
                    </ul>
                </div>
            </section>

            <!-- Section 4 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">4</span>
                    ORDER ACCEPTANCE & PROCESSING
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>All orders placed through our website are subject to our acceptance.</p>
                    <ul class="terms-list">
                        <li>We may refuse any order for any reason at our discretion</li>
                        <li>Your order constitutes an offer to purchase our products</li>
                        <li>Order acceptance occurs when we send shipping confirmation</li>
                        <li>We reserve the right to limit or cancel quantities purchased per person, household, or order
                        </li>
                        <li>In the event of order cancellation, we will notify you and provide a full refund</li>
                    </ul>
                </div>
            </section>

            <!-- Section 5 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">5</span>
                    PAYMENT TERMS
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>We accept various payment methods as indicated on our website.</p>
                    <ul class="terms-list">
                        <li>You represent and warrant that you have the legal right to use any payment method</li>
                        <li>By submitting payment information, you authorize us to charge the provided payment method</li>
                        <li>All payments must be made in the currency specified at checkout</li>
                        <li>You are responsible for any taxes, duties, or customs fees applicable to your order</li>
                        <li>We use third-party payment processors and are not responsible for their actions</li>
                    </ul>
                </div>
            </section>

            <!-- Section 6 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">6</span>
                    INTELLECTUAL PROPERTY RIGHTS
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>All content on our website is our property or the property of our licensors.</p>
                    <ul class="terms-list">
                        <li>All website content is protected by copyright, trademark, and other laws</li>
                        <li>You may not modify, reproduce, distribute, or create derivative works without permission</li>
                        <li>Our company name, logo, and all related names are our trademarks</li>
                        <li>You may not use our trademarks without our prior written permission</li>
                        <li>All rights not expressly granted are reserved</li>
                    </ul>
                </div>
            </section>

            <!-- Section 7 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">7</span>
                    USER CONTENT & SUBMISSIONS
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>If you submit content to our website, you grant us certain rights regarding that content.</p>
                    <ul class="terms-list">
                        <li>You retain ownership of any content you submit</li>
                        <li>By submitting content, you grant us a worldwide, royalty-free license to use it</li>
                        <li>You represent that you have all necessary rights to the content you submit</li>
                        <li>We may remove any content that violates these Terms</li>
                        <li>We are not responsible for any user-submitted content</li>
                    </ul>
                </div>
            </section>

            <!-- Section 8 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">8</span>
                    PROHIBITED USES
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>You may use our website only for lawful purposes and in accordance with these Terms.</p>
                    <ul class="terms-list">
                        <li>You may not use our website in any way that violates applicable laws</li>
                        <li>You may not engage in any activity that interferes with website operation</li>
                        <li>You may not attempt to gain unauthorized access to any part of our website</li>
                        <li>You may not introduce any viruses or other malicious code</li>
                        <li>You may not use any automated systems to access our website without permission</li>
                    </ul>
                </div>
            </section>

            <!-- Section 9 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">9</span>
                    LIMITATION OF LIABILITY
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>To the fullest extent permitted by law, our liability to you is limited as follows:</p>
                    <ul class="terms-list">
                        <li>We are not liable for any indirect, incidental, or consequential damages</li>
                        <li>Our total liability for any claims related to these Terms is limited to the amount you paid for
                            products</li>
                        <li>We are not liable for any products or services of third parties</li>
                        <li>We are not liable for any unauthorized access to or use of your personal information</li>
                        <li>Some jurisdictions do not allow limitations on liability, so these limitations may not apply to
                            you</li>
                    </ul>
                </div>
            </section>

            <!-- Section 10 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">10</span>
                    GOVERNING LAW & DISPUTE RESOLUTION
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>These Terms are governed by and construed in accordance with the laws of our jurisdiction.</p>
                    <ul class="terms-list">
                        <li>Any dispute arising from these Terms will be resolved in the courts of our jurisdiction</li>
                        <li>You agree to submit to the personal jurisdiction of these courts</li>
                        <li>We make no claims that our website is appropriate for use in all locations</li>
                        <li>You are responsible for compliance with local laws when accessing our website</li>
                        <li>If any provision of these Terms is found to be invalid, the remaining provisions remain in
                            effect</li>
                    </ul>
                </div>
            </section>

            <!-- Section 11 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">11</span>
                    CHANGES TO TERMS
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>We may update these Terms from time to time to reflect changes in our practices or for other
                        operational, legal, or regulatory reasons.</p>
                    <ul class="terms-list">
                        <li>We will post the updated Terms on our website</li>
                        <li>Changes become effective when posted unless otherwise noted</li>
                        <li>Your continued use of our website after changes constitutes acceptance</li>
                        <li>We encourage you to review these Terms periodically</li>
                        <li>If you do not agree to the updated Terms, you must stop using our website</li>
                    </ul>
                </div>
            </section>

            <!-- Section 12 -->
            <section class="policy-section">
                <h2 class="text-xl font-bold text-black mb-6 flex items-center">
                    <span class="section-number">12</span>
                    CONTACT INFORMATION
                </h2>
                <div class="text-gray-700 space-y-4 pl-16">
                    <p>If you have any questions about these Terms, please contact us:</p>
                    <ul class="terms-list">
                        <li>By email: <a href="mailto:legal@example.com" class="text-black underline">legal@example.com</a>
                        </li>
                        <li>By mail: 123 Legal Street, Compliance City, CC 12345</li>
                        <li>By phone: <a href="tel:+18005551234" class="text-black underline">(800) 555-1234</a></li>
                    </ul>
                    <p>We will respond to all legitimate inquiries within a reasonable time frame.</p>
                </div>
            </section>
        </div>

        <!-- Acceptance Confirmation -->
        <div class="mt-16 pt-8 border-t border-gray-300">
            <p class="text-gray-700 text-sm italic">By using our website, you acknowledge that you have read, understood,
                and agree to be bound by these Terms and Conditions.</p>
        </div>
    </div>
@endsection
