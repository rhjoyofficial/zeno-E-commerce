
```
zeno-E-commerce
├─ .editorconfig
├─ app
│  ├─ Console
│  │  └─ Commands
│  │     └─ ClearNavigationCache.php
│  ├─ Events
│  │  └─ UserRegistered.php
│  ├─ Exceptions
│  │  └─ Handler.php
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ Admin
│  │  │  │  ├─ AdminDashboardController.php
│  │  │  │  ├─ BrandController.php
│  │  │  │  ├─ CategoryController.php
│  │  │  │  ├─ CustomerController.php
│  │  │  │  ├─ HomeSectionController.php
│  │  │  │  ├─ NavigationController.php
│  │  │  │  ├─ OrderController.php
│  │  │  │  ├─ Product
│  │  │  │  │  ├─ ProductController.php
│  │  │  │  │  └─ ProductVariantController.php
│  │  │  │  ├─ ReportController.php
│  │  │  │  └─ SettingController.php
│  │  │  ├─ Auth
│  │  │  │  ├─ LoginController.php
│  │  │  │  ├─ NewPasswordController.php
│  │  │  │  ├─ OTPVerificationController.php
│  │  │  │  ├─ PasswordResetLinkController.php
│  │  │  │  └─ RegisterController.php
│  │  │  ├─ CategoryController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ Customer
│  │  │  │  ├─ CartController.php
│  │  │  │  ├─ CartController2.php
│  │  │  │  ├─ CheckoutController.php
│  │  │  │  ├─ CustomerDashboardController.php
│  │  │  │  ├─ ProductController.php
│  │  │  │  └─ WishlistController.php
│  │  │  ├─ CustomerProfileController.php
│  │  │  ├─ HomeController.php
│  │  │  ├─ InvoiceController.php
│  │  │  ├─ InvoiceProductController.php
│  │  │  ├─ Payment
│  │  │  │  └─ PaymentController.php
│  │  │  ├─ ProductCartController.php
│  │  │  ├─ ProductController.php
│  │  │  ├─ ProductDetailController.php
│  │  │  ├─ ProductReviewController.php
│  │  │  ├─ ProductSliderController.php
│  │  │  ├─ ProductWishController.php
│  │  │  ├─ ProfileController.php
│  │  │  ├─ Public
│  │  │  │  └─ PolicyController.php
│  │  │  ├─ SslcommerzAccountController.php
│  │  │  ├─ TestingController.php
│  │  │  ├─ UserController.php
│  │  │  └─ View
│  │  │     └─ NavigationController.php
│  │  ├─ Middleware
│  │  │  ├─ AdminMiddleware.php
│  │  │  ├─ Authenticate.php
│  │  │  ├─ CheckOtpDailyLimit.php
│  │  │  ├─ CustomerMiddleware.php
│  │  │  ├─ EnsureEmailIsVerified.php
│  │  │  └─ SyncCartOnLogin.php
│  │  └─ Requests
│  │     ├─ Admin
│  │     │  └─ Product
│  │     │     ├─ StoreProductRequest.php
│  │     │     ├─ StoreProductVariantsRequest.php
│  │     │     └─ UpdateProductRequest.php
│  │     └─ HomeSectionRequest.php
│  ├─ Listeners
│  │  └─ SendWelcomeMessage.php
│  ├─ Mail
│  │  └─ OtpMail.php
│  ├─ Models
│  │  ├─ Brand.php
│  │  ├─ Category.php
│  │  ├─ Color.php
│  │  ├─ Country.php
│  │  ├─ Coupon.php
│  │  ├─ CustomerProfile.php
│  │  ├─ District.php
│  │  ├─ Division.php
│  │  ├─ HomeSection.php
│  │  ├─ HomeSectionItem.php
│  │  ├─ MegaMenuContent.php
│  │  ├─ NavigationMenu.php
│  │  ├─ NavigationMenuItem.php
│  │  ├─ Notification.php
│  │  ├─ Order.php
│  │  ├─ OrderItem.php
│  │  ├─ Policy.php
│  │  ├─ Product.php
│  │  ├─ ProductCart.php
│  │  ├─ ProductDetail.php
│  │  ├─ ProductImage.php
│  │  ├─ ProductReview.php
│  │  ├─ ProductSize.php
│  │  ├─ ProductSlider.php
│  │  ├─ ProductTag.php
│  │  ├─ ProductVariant.php
│  │  ├─ ProductWish.php
│  │  ├─ Role.php
│  │  ├─ ShippingAddress.php
│  │  ├─ SslcommerzAccount.php
│  │  ├─ Tag.php
│  │  ├─ TaxRate.php
│  │  └─ User.php
│  ├─ Notifications
│  │  ├─ ResetPasswordNotification.php
│  │  └─ WelcomeNotification.php
│  ├─ Providers
│  │  ├─ AppServiceProvider.php
│  │  ├─ EventServiceProvider.php
│  │  └─ ViewComposerServiceProvider.php
│  ├─ services
│  │  └─ OtpService.php
│  └─ View
│     └─ Components
│        ├─ admin
│        │  └─ FormActions.php
│        ├─ ApplicationLogo.php
│        ├─ Breadcrumbs.php
│        ├─ Button.php
│        ├─ FormCard.php
│        ├─ Input.php
│        ├─ ProductCard.php
│        └─ ProductCartPopup.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  └─ providers.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ queue.php
│  ├─ services.php
│  └─ session.php
├─ database
│  ├─ factories
│  │  ├─ OrderFactory.php
│  │  ├─ OrderItemFactory.php
│  │  ├─ ProductFactory.php
│  │  ├─ ShippingAddressFactory.php
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  ├─ 2023_02_16_065520_create_customer_profiles.php
│  │  ├─ 2023_02_16_065529_create_categories.php
│  │  ├─ 2023_02_16_065654_create_brands.php
│  │  ├─ 2023_02_17_114815_create_products.php
│  │  ├─ 2023_02_17_144756_create_product_reviews.php
│  │  ├─ 2023_02_17_164422_create_product_sizes_table.php
│  │  ├─ 2023_02_17_164423_create_colors_table.php
│  │  ├─ 2023_02_17_164424_create_product_details.php
│  │  ├─ 2023_02_17_184723_create_product_sliders.php
│  │  ├─ 2023_02_17_191300_create_product_wishes.php
│  │  ├─ 2023_02_17_194301_create_product_carts.php
│  │  ├─ 2023_08_06_131501_create_sslcommerz_accounts.php
│  │  ├─ 2023_08_06_131940_create_orders.php
│  │  ├─ 2023_08_06_131941_create_order_items.php
│  │  ├─ 2023_08_08_051859_create_policies.php
│  │  ├─ 2025_04_28_045623_create_roles_table.php
│  │  ├─ 2025_08_11_125815_create_notifications_table.php
│  │  ├─ 2025_08_11_183802_create_countries_table.php
│  │  ├─ 2025_08_11_183803_create_divisions_table.php
│  │  ├─ 2025_08_11_183819_create_districts_table.php
│  │  ├─ 2025_08_11_190522_create_offers_table.php
│  │  ├─ 2025_08_30_191409_create_navigation_menus_table.php
│  │  ├─ 2025_09_29_165011_create_home_sections_table.php
│  │  └─ 2025_09_29_165022_create_home_section_items_table.php
│  └─ seeders
│     ├─ BrandSeeder.php
│     ├─ CategorySeeder.php
│     ├─ ColorSeeder.php
│     ├─ CouponSeeder.php
│     ├─ DatabaseSeeder.php
│     ├─ NavigationMenuSeeder.php
│     ├─ OrderSeeder.php
│     ├─ PolicySeeder.php
│     ├─ ProductReviewSeeder.php
│     ├─ ProductSeeder.php
│     ├─ ProductSizeSeeder.php
│     ├─ RoleSeeder.php
│     ├─ ShippingAddressSeeder.php
│     ├─ SslcommerzAccountSeeder.php
│     ├─ TagSeeder.php
│     ├─ TaxRateSeeder.php
│     └─ UserSeeder.php
├─ LICENSE
├─ package-lock.json
├─ package.json
├─ phpunit.xml
├─ postcss.config.js
├─ public
│  ├─ .htaccess
│  ├─ css
│  │  ├─ app.css
│  │  └─ preloader.css
│  ├─ favicon.ico
│  ├─ images
│  │  ├─ 1.jpg
│  │  ├─ 2.jpg
│  │  ├─ 3.jpg
│  │  ├─ 4.jpg
│  │  ├─ 5.jpg
│  │  ├─ 6.jpg
│  │  ├─ 7.jpg
│  │  ├─ 8.jpg
│  │  ├─ 9BbfMTS2W0b1eOezk03BgRtvy9Z4Nm4nW64G4juL.jpg
│  │  ├─ adidas.png
│  │  ├─ cartimg.jpg
│  │  ├─ cartimg.png
│  │  ├─ default.jpg
│  │  ├─ fashion1.jpg
│  │  ├─ fashion2.jpg
│  │  ├─ favicon.png
│  │  ├─ Hero.jpg
│  │  ├─ json_structure.png
│  │  ├─ k1.jpg
│  │  ├─ k2.jpg
│  │  ├─ k3.jpg
│  │  ├─ kids-banner.jpg
│  │  ├─ kids.jpg
│  │  ├─ levis.png
│  │  ├─ m1.jpg
│  │  ├─ m2.jpg
│  │  ├─ m3.jpg
│  │  ├─ men-formal.jpg
│  │  ├─ men-summer.jpg
│  │  ├─ mens-banner.jpg
│  │  ├─ mens-banner2.jpg
│  │  ├─ mens.jpg
│  │  ├─ newsletter-bg.jpg
│  │  ├─ nike.png
│  │  ├─ pro1.jpg
│  │  ├─ pro2.jpg
│  │  ├─ pro3.jpg
│  │  ├─ pro4.jpg
│  │  ├─ puma.png
│  │  ├─ Screenshot_1.png
│  │  ├─ slider1.jpg
│  │  ├─ slider2.jpg
│  │  ├─ slider3.jpg
│  │  ├─ slider4.jpg
│  │  ├─ special-offer.jpg
│  │  ├─ svg
│  │  │  ├─ amex-svgrepo-com.svg
│  │  │  ├─ jcb-svgrepo-com.svg
│  │  │  ├─ maestro-svgrepo-com.svg
│  │  │  ├─ mastercard-svgrepo-com.svg
│  │  │  ├─ paypal-svgrepo-com.svg
│  │  │  ├─ unionpay-svgrepo-com.svg
│  │  │  ├─ visa-svgrepo-com (1).svg
│  │  │  └─ visa-svgrepo-com.svg
│  │  ├─ w1.jpg
│  │  ├─ w2.jpg
│  │  ├─ w3.jpg
│  │  ├─ watch.jpg
│  │  ├─ watchmenu.jpg
│  │  ├─ women.jpg
│  │  ├─ womens-banner.jpg
│  │  ├─ zeno-about1.jpg
│  │  ├─ zeno-about2.jpg
│  │  ├─ zeno-team1.jpg
│  │  └─ Zeno.png
│  ├─ index.php
│  ├─ js
│  │  ├─ helper.js
│  │  ├─ notification.js
│  │  ├─ preloader.js
│  │  └─ product-popup.js
│  └─ robots.txt
├─ README.md
├─ resources
│  ├─ css
│  │  ├─ app.css
│  │  └─ fonts.css
│  ├─ fonts
│  │  └─ Megumi.ttf
│  ├─ js
│  │  ├─ app.js
│  │  └─ bootstrap.js
│  └─ views
│     ├─ admin
│     │  ├─ brands
│     │  │  ├─ brand-products.blade.php
│     │  │  ├─ index.blade.php
│     │  │  └─ modals
│     │  │     ├─ create.blade.php
│     │  │     └─ edit.blade.php
│     │  ├─ categories
│     │  │  ├─ category-products.blade.php
│     │  │  ├─ index.blade.php
│     │  │  └─ modals
│     │  │     ├─ create.blade.php
│     │  │     └─ edit.blade.php
│     │  ├─ customers
│     │  │  └─ index.blade.php
│     │  ├─ dashboard.blade.php
│     │  ├─ home-sections
│     │  │  ├─ create.blade.php
│     │  │  ├─ edit.blade.php
│     │  │  └─ index.blade.php
│     │  ├─ orders
│     │  │  └─ index.blade.php
│     │  ├─ partials
│     │  │  ├─ bashboard-header.blade.php
│     │  │  ├─ sidebar-links.blade.php
│     │  │  └─ user-dropdown.blade.php
│     │  ├─ products
│     │  │  ├─ index.blade.php
│     │  │  ├─ modals
│     │  │  │  ├─ create.blade.php
│     │  │  │  ├─ edit.blade.php
│     │  │  │  └─ show.blade.php
│     │  │  └─ variants
│     │  │     ├─ create.blade.php
│     │  │     ├─ edit.blade.php
│     │  │     └─ index.blade.php
│     │  └─ profile
│     │     └─ profile.blade.php
│     ├─ auth
│     │  ├─ confirm-password.blade.php
│     │  ├─ forgot-password.blade.php
│     │  ├─ login.blade.php
│     │  ├─ register.blade.php
│     │  ├─ reset-password.blade.php
│     │  ├─ verify-email.blade.php
│     │  └─ verify-otp.blade.php
│     ├─ cart
│     │  ├─ checkout.blade.php
│     │  └─ index.blade.php
│     ├─ components
│     │  ├─ admin
│     │  │  └─ form-actions.blade.php
│     │  ├─ application-logo.blade.php
│     │  ├─ auth
│     │  │  ├─ button.blade.php
│     │  │  ├─ form-card.blade.php
│     │  │  └─ input.blade.php
│     │  ├─ breadcrumbs.blade.php
│     │  ├─ dynamic-navigation.blade.php
│     │  ├─ footer.blade.php
│     │  ├─ navbar.blade.php
│     │  ├─ notification.blade.php
│     │  ├─ product-card.blade.php
│     │  └─ product-cart-popup.blade.php
│     ├─ customer
│     │  ├─ cart-item.blade.php
│     │  ├─ checkout-copy.blade.php
│     │  ├─ checkout.blade.php
│     │  ├─ dashboard.blade.php
│     │  └─ profile.blade.php
│     ├─ dashboard
│     │  ├─ address.blade.php
│     │  ├─ index.blade.php
│     │  └─ order.blade.php
│     ├─ dashboard.blade.php
│     ├─ emails
│     │  ├─ otp.blade.php
│     │  ├─ plain_text.blade.php
│     │  └─ welcome.blade.php
│     ├─ errors
│     │  ├─ 404.blade.php
│     │  ├─ 500.blade.php
│     │  └─ construction.blade.php
│     ├─ frontend
│     │  ├─ about-us.blade.php
│     │  ├─ accessories.blade.php
│     │  ├─ brand-trust.blade.php
│     │  ├─ brands.blade.php
│     │  ├─ contact-us.blade.php
│     │  ├─ delivery-return-policy.blade.php
│     │  ├─ dynamic-fashion.blade.php
│     │  ├─ dynamic-new-arrivals.blade.php
│     │  ├─ footer.blade.php
│     │  ├─ heroSection.blade.php
│     │  ├─ mens-fashion.blade.php
│     │  ├─ navbar.blade.php
│     │  ├─ new-arrivals.blade.php
│     │  ├─ newsletter.blade.php
│     │  ├─ pages
│     │  │  └─ policies
│     │  │     ├─ exchange-policy.blade.php
│     │  │     ├─ privacy-policy.blade.php
│     │  │     ├─ shipping-policy.blade.php
│     │  │     └─ terms-and-conditions.blade.php
│     │  ├─ product-details.blade.php
│     │  ├─ product-list.blade.php
│     │  └─ womens-fashion.blade.php
│     ├─ home
│     │  ├─ about.blade.php
│     │  ├─ contact.blade.php
│     │  └─ index.blade.php
│     ├─ home.blade.php
│     ├─ invoices
│     │  ├─ index.blade.php
│     │  └─ show.blade.php
│     ├─ layouts
│     │  ├─ admin.blade.php
│     │  ├─ app.blade.php
│     │  └─ master-layout.blade.php
│     ├─ partials
│     │  ├─ flash-messages.blade.php
│     │  ├─ loading-overlay.blade.php
│     │  ├─ membership.blade.php
│     │  ├─ preloader.blade.php
│     │  └─ user-dropdown.blade.php
│     ├─ products
│     │  ├─ index.blade.php
│     │  └─ show.blade.php
│     └─ testing.blade.php
├─ routes
│  ├─ console.php
│  └─ web.php
├─ storage
│  ├─ app
│  │  └─ private
│  ├─ debugbar
│  ├─ framework
│  │  ├─ cache
│  │  │  └─ data
│  │  ├─ sessions
│  │  ├─ testing
│  │  └─ views
│  └─ logs
├─ tailwind.config.js
├─ tests
│  ├─ Feature
│  │  ├─ Auth
│  │  │  ├─ AuthenticationTest.php
│  │  │  ├─ EmailVerificationTest.php
│  │  │  ├─ PasswordConfirmationTest.php
│  │  │  ├─ PasswordResetTest.php
│  │  │  ├─ PasswordUpdateTest.php
│  │  │  └─ RegistrationTest.php
│  │  ├─ ExampleTest.php
│  │  └─ ProfileTest.php
│  ├─ Pest.php
│  ├─ TestCase.php
│  └─ Unit
│     └─ ExampleTest.php
└─ vite.config.js

```