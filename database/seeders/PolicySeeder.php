<?php

namespace Database\Seeders;

use App\Models\Policy;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->firstOrFail();

        $policies = [
            [
                'type'        => 'about',
                'description' => '<h2>About Zeno</h2>
<p>Welcome to <strong>Zeno</strong> — your one-stop destination for premium fashion, electronics, and lifestyle products in Bangladesh.</p>
<p>Founded with a passion for quality and affordability, Zeno brings the best local and international brands directly to your door. We are committed to delivering a seamless online shopping experience with fast delivery, secure payments, and easy returns.</p>
<p>Our mission is to make premium shopping accessible to everyone across Bangladesh.</p>',
                'created_by'  => $admin->id,
                'updated_by'  => $admin->id,
            ],
            [
                'type'        => 'refund',
                'description' => '<h2>Refund &amp; Return Policy</h2>
<p>We want you to be completely satisfied with your purchase. If you are not happy, we are here to help.</p>
<ul>
  <li><strong>Return Window:</strong> Products may be returned within <strong>7 days</strong> of delivery.</li>
  <li><strong>Condition:</strong> Items must be unused, in original packaging, and with all tags attached.</li>
  <li><strong>Refund Process:</strong> Once we receive the returned item and verify its condition, your refund will be processed within <strong>5–7 business days</strong>.</li>
  <li><strong>Non-Returnable Items:</strong> Undergarments, swimwear, and sale/clearance items are final sale and cannot be returned.</li>
</ul>
<p>To initiate a return, please contact our support team at <a href="mailto:support@zeno.com.bd">support@zeno.com.bd</a>.</p>',
                'created_by'  => $admin->id,
                'updated_by'  => $admin->id,
            ],
            [
                'type'        => 'terms',
                'description' => '<h2>Terms &amp; Conditions</h2>
<p>By accessing and using the Zeno website, you agree to the following terms and conditions:</p>
<ol>
  <li>You must be at least 18 years of age to make a purchase.</li>
  <li>All product information, prices, and availability are subject to change without notice.</li>
  <li>Zeno reserves the right to cancel any order due to pricing errors or stock unavailability.</li>
  <li>Unauthorised use of this website may give rise to a claim for damages.</li>
  <li>All content on this site is the intellectual property of Zeno and may not be reproduced without permission.</li>
</ol>',
                'created_by'  => $admin->id,
                'updated_by'  => $admin->id,
            ],
            [
                'type'        => 'how to buy',
                'description' => '<h2>How to Buy</h2>
<p>Shopping on Zeno is simple and secure. Follow these steps:</p>
<ol>
  <li><strong>Browse:</strong> Explore our wide range of products using the search bar or categories.</li>
  <li><strong>Select:</strong> Choose your preferred size, colour, and quantity.</li>
  <li><strong>Add to Cart:</strong> Click "Add to Cart" to save items for checkout.</li>
  <li><strong>Checkout:</strong> Proceed to checkout, enter your shipping address, and select a payment method.</li>
  <li><strong>Payment:</strong> Pay securely via SSLCommerz (bKash, Nagad, Card, or Net Banking).</li>
  <li><strong>Confirmation:</strong> You will receive an order confirmation via email/SMS.</li>
  <li><strong>Delivery:</strong> Sit back and wait for your order to arrive at your doorstep.</li>
</ol>',
                'created_by'  => $admin->id,
                'updated_by'  => $admin->id,
            ],
            [
                'type'        => 'contact',
                'description' => '<h2>Contact Us</h2>
<p>We\'d love to hear from you! Reach us through any of the channels below:</p>
<ul>
  <li><strong>Email:</strong> <a href="mailto:support@zeno.com.bd">support@zeno.com.bd</a></li>
  <li><strong>Phone:</strong> +880 1700-000000 (Sun–Thu, 10am–6pm)</li>
  <li><strong>Address:</strong> House 12, Road 5, Dhanmondi, Dhaka-1205, Bangladesh</li>
  <li><strong>Facebook:</strong> <a href="https://facebook.com/zenoshop" target="_blank">facebook.com/zenoshop</a></li>
</ul>',
                'created_by'  => $admin->id,
                'updated_by'  => $admin->id,
            ],
            [
                'type'        => 'complain',
                'description' => '<h2>Complaint Policy</h2>
<p>Your satisfaction is our top priority. If you have a complaint, please follow the process below:</p>
<ol>
  <li>Email us at <a href="mailto:complaints@zeno.com.bd">complaints@zeno.com.bd</a> with your order number and a detailed description of the issue.</li>
  <li>Our support team will acknowledge your complaint within <strong>24 hours</strong>.</li>
  <li>We aim to resolve all complaints within <strong>5 business days</strong>.</li>
  <li>If you are not satisfied with our resolution, you may escalate the matter to our management team.</li>
</ol>
<p>We are committed to resolving every complaint fairly and transparently.</p>',
                'created_by'  => $admin->id,
                'updated_by'  => $admin->id,
            ],
        ];

        Policy::upsert($policies, ['type'], ['description', 'created_by', 'updated_by']);
    }
}
