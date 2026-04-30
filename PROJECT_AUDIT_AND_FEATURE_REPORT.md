# Project Audit & Comprehensive Feature Report

Project: Zeno Ecommerce  
Audit date: 2026-04-29  
Audited from: `c:\laragon\www\Zeno`  
Audit method: Static code review, route inspection, dependency review, database migration review, and automated test run.

## 1. Executive Summary

Zeno is a Laravel 11 ecommerce application focused on fashion retail. The project includes a public storefront, customer account area, cart and checkout flow, admin dashboard, product/catalog management, coupon and tax support, order management, dynamic navigation, configurable home sections, contact inquiries, email/OTP flows, and a growing automated test suite.

The codebase is feature-rich and already has several strong engineering choices:

- Business logic for cart, checkout, product formatting, images, home sections, OTP, and invoice sequencing is separated into service classes.
- Checkout uses database transactions, row locking, price-change checks, stock validation, coupon validation, and order item snapshots.
- Core catalog and order tables have meaningful indexes, unique constraints, and foreign keys.
- Role-based admin/customer middleware exists.
- Security headers are applied globally to the web middleware stack.
- Automated tests cover cart behavior, checkout behavior, invoice sequencing, category descendants, final prices, auth, admin authorization, and order confirmation.

The main risks are operational readiness and consistency issues:

- The test suite currently fails because `public/build/manifest.json` is missing, causing Vite-backed views to return 500 in feature tests.
- Several legacy Breeze-style profile/auth tests no longer match the current route design.
- Debugbar routes are active in the current route list, so production environment configuration must be checked before deployment.
- README content is mostly a generated tree and contains mojibake/encoding artifacts.
- Payment routes/models mention SSLCommerz and non-COD payment methods, but the current checkout service only finalizes COD-style order creation and does not complete a full gateway flow.
- Navigation/home-section cache invalidation is partial and may need broader coverage after edits.

## 2. Technology Stack

Backend:

- PHP `^8.2`
- Laravel Framework `^11.31`
- Laravel Tinker
- Spatie Image Optimizer

Frontend/build:

- Vite `^6.0.11`
- Laravel Vite Plugin
- Tailwind CSS `^3.1.0`
- PostCSS and Autoprefixer
- Axios
- Font Awesome
- Swiper

Development/testing:

- Pest `^3.8`
- Pest Laravel Plugin
- Laravel Pint
- Laravel Sail
- Laravel Pail
- Faker
- Mockery
- Collision
- Barryvdh Laravel Debugbar
- Concurrently for combined local dev process

Important scripts:

- `composer dev`: runs Laravel server, queue listener, logs, and Vite together.
- `npm run dev`: starts Vite.
- `npm run build`: builds frontend assets.
- `php artisan test`: runs Pest/PHPUnit tests.

## 3. Application Architecture

The project follows a Laravel MVC structure:

- `app/Http/Controllers`: public, auth, customer, admin, payment, and profile controllers.
- `app/Models`: ecommerce domain entities such as products, variants, carts, orders, coupons, tax rates, navigation menus, home sections, settings, policies, and users.
- `app/Services`: reusable business logic for cart, checkout, product management, product card formatting, image handling, OTP, home sections, and sequence generation.
- `app/Http/Requests`: validation for checkout, product management, and home sections.
- `app/Http/Middleware`: auth, role checks, OTP throttling, cart sync, email verification, and security headers.
- `resources/views`: Blade views for storefront, customer area, admin area, auth, emails, layouts, components, invoices, and errors.
- `database/migrations`: schema for ecommerce, auth, content, settings, regional data, navigation, and operational tables.
- `database/seeders`: default users, roles, settings, products, categories, brands, colors, sizes, reviews, coupons, tax rates, policies, navigation, and home sections.
- `tests`: unit and feature coverage using Pest.

## 4. Route Surface

`php artisan route:list` shows 154 registered routes.

Major route groups:

- Public storefront: home, about, contact, policy pages.
- Product browsing: product list, product detail, variant lookup.
- Cart: add, update, remove, sync, item list, variant price lookup.
- Checkout: checkout page, order storage, order confirmation.
- Auth: register, login, logout, forgot password, reset password, OTP verification/resend.
- Profile: view profile, update info, update password.
- Customer area: dashboard, orders, order details, addresses, wishlist.
- Admin area: dashboard, brands, categories, products, variants, customers, orders, reports, coupons, settings, navigation, home sections.
- Framework/dev routes: Livewire and Debugbar routes.
- Health route: `/up`.
- Fallback route with special admin redirect behavior.

## 5. Feature Inventory

### 5.1 Public Storefront

Implemented:

- Dynamic home page driven by active home sections.
- Top-level category loading.
- Product cards formatted through `ProductCardService`.
- About page with fashion content and images.
- Contact page with contact inquiry persistence.
- Policy pages for privacy, shipping, exchange, and terms.
- Dynamic navigation components and frontend partials.
- Static assets for fashion banners, brand logos, product imagery, payment logos, and newsletter imagery.

Observations:

- Contact inquiry validation exists and stores name, email, message, and IP address.
- README and some source strings contain encoding artifacts such as `â€”`, suggesting file encoding or copy/paste issues.

### 5.2 Product Catalog

Implemented:

- Products with title, short description, SKU, slug, base price, discount flag, discount price, stock quantity, stock alert, status, category, brand, tags, and new-arrival flag.
- Product variants with color, size, SKU, price/discount behavior, stock, and status.
- Product images with primary/additional image handling.
- Product detail relationship.
- Product tags.
- Product reviews with approved review relationship.
- Category hierarchy with descendant lookup.
- Brand management.
- Product filtering/listing surfaces in customer views.

Strong points:

- Product and variant final price accessors are tested.
- Category descendant lookup is cached and has a loop/depth guard.
- Product creation/update/deletion is handled through `ProductService` transactions.
- Product image upload validation limits files to images up to 2 MB.

Risks:

- `ProductService::updateProduct()` expects `existing_images`, while `UpdateProductRequest` defines `remove_images`; confirm the view/request contract.
- Product image deletion occurs inside a database transaction, but filesystem deletes are not transactional. A DB rollback after file deletion can leave references broken.
- Product update request does not validate SKU updates, while store validates SKU uniqueness.

### 5.3 Cart

Implemented:

- Guest session cart.
- Authenticated database-backed cart.
- Add to cart.
- Quantity updates.
- Item removal.
- Session-to-database cart sync after login.
- Variant price lookup.
- Stock validation before cart additions/updates.
- Cart count calculation.

Strong points:

- Unit tests cover session cart creation, stock limits, variant ownership, inactive variants, and quantity increments.
- Guest cart uses stable product/variant keys.

Risks:

- Cart sync increments database quantities without rechecking final stock during sync.
- Cart prices are stored, but checkout recalculates from current product/variant prices. This is good for correctness, but UX needs clear price-change messaging, which is partially handled.

### 5.4 Checkout and Orders

Implemented:

- Selected item checkout.
- Checkout session snapshot.
- VAT/tax lookup from `tax_rates` with config fallback.
- Coupon validation and application.
- Transactional order creation.
- Inventory row locking.
- Stock deduction.
- Order item snapshot records.
- Shipping address creation for authenticated users and guests.
- Guest order support via session ID.
- Authenticated order ownership check on confirmation page.
- Atomic invoice/sequence generation.
- COD order auto-confirmation.

Strong points:

- `CheckoutService::processOrder()` uses transactions and locks inventory.
- Price-change guard prevents stale checkout totals.
- Tests cover process order, stock failure, price-change failure, coupon logic, invoice sequence uniqueness, and order confirmation authorization.
- Order model defines a clear status transition map.

Risks:

- `CheckoutRequest` accepts `bkash`, `mobile-banking`, and `card`, but no complete payment gateway flow is visible in the checkout service.
- `PaymentController` exists, and SSLCommerz account models/seeders exist, but current route file does not expose a payment callback/initiation flow.
- Guest checkout confirmation depends on session ID; this is common, but fragile if the session is lost.
- Order deletion is allowed in admin through resource route; consider soft delete policy, audit history, and whether deletion should be disabled for financial records.

### 5.5 Coupons and Tax

Implemented:

- Admin coupon CRUD.
- Coupon fields for code, type, value, minimum order amount, validity window, usage limit, active flag, and used count.
- Percentage and fixed discounts.
- Fixed discounts capped at subtotal.
- Active coupon scope validates active window and usage limit.
- Tax rates table and seeder.
- VAT calculation during checkout.

Risks:

- Coupon code is uppercased in checkout, but admin store/update does not visibly normalize code before saving. This can cause case inconsistencies.
- `used_count` increments at checkout, but reporting/rollback/refund behavior is not apparent.

### 5.6 Customer Account Area

Implemented:

- Customer dashboard.
- Customer orders and order details.
- Customer addresses and address details.
- Wishlist view/add/remove.
- Profile view/update.
- Password update.
- Customer role middleware.

Risks:

- Customer route authorization should be checked carefully for address/order detail ownership inside `CustomerDashboardController`.
- Profile tests expect older delete/profile routes and currently fail with 405 responses.

### 5.7 Admin Area

Implemented:

- Admin dashboard with total revenue, order count, customer count, product count, recent orders, and top products.
- Brand CRUD and status update.
- Category CRUD, hierarchy support, status update, child category endpoint.
- Product CRUD, SKU check, status update, stock update.
- Product variant CRUD, SKU checks, combination checks.
- Customer list/data/export routes.
- Order list with filters by status, payment status, date range.
- Order show/update/delete.
- Order status transition validation.
- Order quick-view JSON/modal response.
- Report resource routes.
- Coupon CRUD.
- Settings resource routes.
- Navigation menus, nested menu items, and mega menu content management.
- Home section CRUD and status toggle.

Strong points:

- Admin/customer route separation is clear.
- Admin dashboard queries are straightforward and useful.
- Order status transitions prevent invalid workflow jumps.
- Resource route coverage is broad.

Risks:

- Report resource routes exist, but actual reporting depth needs verification.
- Several admin resource methods may exist only because of resource routing; verify whether create/edit/show pages are implemented for each area.
- Route list exposes Debugbar routes. This is acceptable locally, but must be disabled in production.

### 5.8 Authentication, OTP, and Notifications

Implemented:

- Custom login and registration controllers.
- Password reset request and new password controllers.
- OTP verification and resend routes.
- OTP mail.
- Welcome notification and reset password notification.
- User registered event and welcome listener.
- OTP token index migration.
- Throttling middleware attached to registration, password reset, OTP verify/resend, and checkout routes.

Risks:

- Feature tests for standard email verification/password confirmation routes fail because those routes are not present in the current route file.
- Confirm the intended auth flow: OTP verification appears to replace or supplement Laravel email verification.
- Queue/notification delivery should be validated in staging with real mail configuration.

### 5.9 Navigation and Home Sections

Implemented:

- Navigation menus with slug, position, status, mega menu support, nested items, and mega content.
- Mega menu content types: categories, featured collections, brands, promo banner.
- Dynamic navigation Blade component.
- Home sections with category associations and product retrieval by section type.
- Cached active sections and section products.
- Admin cache busting for navigation edits.

Risks:

- Home section cache invalidation is not visible in `HomeSectionService`; ensure create/update/delete/toggle operations clear `home_sections_active` and `section_products_*`.
- `section_products_{$section->id}` cache key does not include limit or category/version, which may cause stale or surprising results after content changes.

## 6. Database and Data Model

Important tables include:

- Auth/session/queue/cache: `users`, `password_reset_tokens`, `sessions`, `jobs`, `job_batches`, `failed_jobs`, `cache`, `cache_locks`.
- Roles and profiles: `roles`, `customer_profiles`, `shipping_addresses`.
- Catalog: `categories`, `brands`, `products`, `product_details`, `product_variants`, `product_images`, `product_sizes`, `colors`, `tags`, `product_tags`, `product_reviews`, `product_sliders`.
- Commerce: `product_carts`, `product_wishes`, `orders`, `order_items`, `coupons`, `tax_rates`, `sequences`, `sslcommerz_accounts`.
- Content/ops: `policies`, `notifications`, `countries`, `divisions`, `districts`, `navigation_menus`, `navigation_menu_items`, `mega_menu_contents`, `home_sections`, `home_section_categories`, `settings`, `contact_inquiries`.

Schema strengths:

- Product slugs, SKUs, category names, brand names, coupon codes, role names/slugs, settings keys, and navigation slugs have uniqueness constraints.
- Product/category/brand/order tables include useful indexes.
- Product variants enforce unique product/color/size combinations.
- Order numbers and invoice numbers are unique.
- Order items snapshot product name, SKU, price, discount, tax, quantity, and row totals.

Schema risks:

- Some migrations are named historically or broadly, e.g. `create_offers_table.php` creates coupons and tax rates.
- Several tables use soft deletes, but not every financial/admin action appears to have an audit trail.
- Settings are key/value based, which is flexible but needs validation rules and type handling in the settings controller.

## 7. Security Review

Implemented protections:

- CSRF protection through Laravel web middleware.
- Role-based admin/customer middleware.
- Account status checks for admin/customer access.
- Global security headers:
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: SAMEORIGIN`
  - `X-XSS-Protection: 1; mode=block`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Permissions-Policy: camera=(), microphone=(), geolocation=()`
  - HSTS on secure requests
- Request validation for checkout and product management.
- Throttling on sensitive routes.
- Order confirmation authorization for owner/session.

Security risks and recommendations:

- Disable Debugbar in production and verify `APP_DEBUG=false`.
- Add or verify Content Security Policy if the frontend can support it.
- Review all admin controllers for authorization beyond middleware if multi-admin permissions are planned.
- Confirm customer ownership checks for addresses, wishlists, and orders.
- Avoid exposing detailed exception messages to end users in production paths.
- Normalize coupon codes server-side on create/update.
- Consider rate limiting contact form submissions.
- Review file upload storage and public disk exposure for image-only enforcement and safe filenames.

## 8. Test Audit

Command run:

```bash
php artisan test
```

Result:

- 52 tests passed.
- 26 tests failed.
- 127 assertions executed before completion.

Passing areas:

- Cart service unit behavior.
- Category descendant lookup.
- Checkout service coupon/order/stock/price-change behavior.
- Product and variant final price accessors.
- Sequence service invoice number generation.
- Cart controller feature flow.
- Invoice sequence feature flow.
- Several order confirmation authorization cases.

Main failure categories:

- Many feature tests fail with `Vite manifest not found at: public/build/manifest.json`.
- Several profile tests fail with 405 responses because tests use old routes such as `PATCH /profile` or account deletion flows, while the current app exposes `PUT /profile/info` and `PUT /profile/password`.
- Email verification/password confirmation tests expect Laravel default routes that are not visible in `routes/web.php`.

Recommended test fixes:

- Run `npm run build` before feature tests, or configure tests to avoid requiring the Vite manifest.
- Update auth/profile tests to match the custom auth and OTP flow.
- Remove or rewrite default scaffold tests that no longer represent the application.
- Add feature tests for admin product CRUD, product variants, coupon admin, order status transitions, navigation admin, home sections, and contact inquiry submission.

## 9. Operational Readiness

Ready/positive:

- Dependencies are installed locally (`vendor` and `node_modules` exist).
- Laravel app boots enough to list routes.
- Seeders exist for major domain data.
- Queue table and queue listener script are configured.
- Public assets and Blade views are present.

Needs attention:

- Build frontend assets before production/test feature runs: `npm run build`.
- Confirm `.env` production values: `APP_ENV`, `APP_DEBUG`, `APP_URL`, database, mail, queue, cache, session, and filesystem.
- Confirm storage symlink: `php artisan storage:link`.
- Run migrations and seeders in a clean database.
- Decide whether queue should be `database`, `redis`, or sync depending on hosting.
- Verify payment gateway settings and routes if non-COD payments are required.
- Add deployment checklist for cache/build commands.

Suggested deployment commands:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

## 10. Documentation Audit

Current README status:

- Contains a large project tree.
- Does not yet provide a clear installation guide, environment setup, feature overview, admin credentials, testing guide, deployment guide, or troubleshooting guide.
- Contains encoding artifacts/mojibake.

Recommended documentation:

- Replace or supplement README with:
  - Project overview.
  - Requirements.
  - Installation steps.
  - Environment variables.
  - Database migration/seeding steps.
  - Local development workflow.
  - Test workflow.
  - Admin/customer default credentials if seeders provide them.
  - Feature guide.
  - Deployment checklist.
  - Known limitations.

## 11. Priority Recommendations

### High Priority

1. Build or mock Vite manifest for tests so feature tests can run reliably.
2. Update failing auth/profile tests to match the actual custom routes.
3. Verify production environment disables Debugbar and debug mode.
4. Complete or clearly disable non-COD payment methods until gateway processing is fully implemented.
5. Review customer ownership authorization for orders, addresses, and wishlist operations.
6. Add cache invalidation for home sections and section products after admin edits.

### Medium Priority

1. Normalize coupon codes on admin create/update.
2. Align product image request fields between views, requests, and `ProductService`.
3. Add admin CRUD tests for products, variants, orders, coupons, settings, navigation, and home sections.
4. Improve README and remove encoding artifacts.
5. Add contact form rate limiting.
6. Add order audit history for status/payment changes.
7. Add payment status workflow tests.

### Low Priority

1. Rename historically inaccurate migration filenames in future squashed migrations or documentation.
2. Add richer reports if the admin report controller is still placeholder-level.
3. Add stronger content editing validation for navigation mega menu JSON.
4. Add frontend smoke tests for key storefront pages.

## 12. Overall Assessment

The project is beyond a basic ecommerce scaffold. It has real domain modeling, transactional checkout logic, admin tooling, catalog flexibility, dynamic content surfaces, and meaningful tests. The strongest areas are cart/checkout/invoice sequencing and the breadth of admin/catalog features.

The biggest gap is not lack of features; it is stabilization. Fixing the asset build/test setup, reconciling old tests with current routes, tightening production configuration, and completing the payment story will make the project much easier to maintain and deploy confidently.

## 13. Audit Notes

Git working tree note:

- Existing deleted files were present before this audit:
  - `.claude/settings.local.json`
  - `.claude/worktrees/cool-ardinghelli-d35ef9`

No changes were made to those files.

