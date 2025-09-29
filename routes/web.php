<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\{
    HomeController,
    ProfileController,
    TestingController
};

use App\Http\Controllers\Public\{
    PolicyController,
};

use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    OTPVerificationController,
    PasswordResetLinkController,
    NewPasswordController
};

use App\Http\Controllers\Customer\{
    CartController,
    WishlistController,
    CheckoutController,
    CustomerDashboardController,
    ProductController as CustomerProductController
};

use App\Http\Controllers\View\{
    NavigationController
};

use App\Http\Controllers\Admin\{
    AdminDashboardController,
    BrandController,
    CategoryController,
    CustomerController,
    OrderController,
    ReportController,
    SettingController
};

use App\Http\Controllers\Admin\Product\{
    ProductController as AdminProductController,
    ProductVariantController
};
use Illuminate\Support\Facades\Auth;

// ==================== PUBLIC ROUTES ====================
Route::post('/clear-session-notifications', function () {
    session()->forget(['status', 'success', 'error', 'warning', 'info']);
    return response()->json(['success' => true]);
})->name('clear.session.notifications');

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about-us', 'aboutUs')->name('about.us');
    Route::match(['get', 'post'], '/contact-us', 'contactUs')->name('contact.us');
});

// Policy Routes
Route::prefix('policies')->controller(PolicyController::class)->name('policies.')->group(function () {

    Route::get('/privacy-policy', 'privacyPolicy')
        ->name('privacy.policy');

    Route::get('/shipping-policy', 'shippingPolicy')
        ->name('shipping.policy');

    Route::get('/exchange-policy', 'exchangePolicy')
        ->name('exchange.policy');

    Route::get('/terms-conditions', 'termsConditions')
        ->name('terms-conditions.policy');
});

// Product Routes
Route::prefix('products')->controller(CustomerProductController::class)->name('products.')->group(function () {
    Route::get('/', 'index')->name('list');
    Route::get('/{product}', 'show')->name('show');
    Route::post('/variants', 'getVariants')->name('variants');
});

// Cart Routes
Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
    Route::post('/add', 'addToCart')->name('add');
    Route::get('/items', 'index')->name('index');
    Route::post('/update/{item}', 'update')->name('update');
    Route::post('/remove/{item}', 'remove')->name('remove');
    Route::post('/sync', 'syncCart')->name('sync');
    Route::post('/get-variant-price', 'getVariantPrice')->name('get-variant-price');
});
// Checkout
Route::controller(CheckoutController::class)->name('checkout.')->group(function () {
    Route::post('/checkout', 'index')->name('index');
    Route::post('/store', 'store')->name('store');
    Route::post('/checkout/place-order', 'placeOrder')->name('placeOrder');
});

// ==================== AUTHENTICATION ROUTES ====================

Route::middleware('guest')->group(function () {
    // Registration
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'showRegistrationForm')->name('register.form');
        Route::post('/register', 'register')->name('register');
    })->middleware('throttle:60,1');

    // Login
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
    })->middleware('throttle:5,1');

    // Password Reset
    Route::controller(PasswordResetLinkController::class)->group(function () {
        Route::get('/forgot-password', 'create')->name('password.request');
        Route::post('/forgot-password', 'store')->name('password.email');
    });

    Route::controller(NewPasswordController::class)->group(function () {
        Route::get('/reset-password/{token}', 'create')->name('password.reset');
        Route::post('/reset-password', 'store')->name('password.update');
    });

    // OTP Verification
    Route::controller(OTPVerificationController::class)->group(function () {
        Route::get('/otp/verify/{token}', 'showOtpForm')->name('otp.verify');
        Route::post('/otp/verify', 'verifyOtp')->name('otp.verify.post');
        Route::post('/otp/resend/{token}', 'resendOtp')->name('otp.resend');
        Route::get('/otp/resend/{email}', 'resendOtp')->name('otp.resend.get');
    });
});

// ==================== AUTHENTICATED USER ROUTES ====================

Route::middleware('auth')->group(function () {
    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'showProfile')->name('profile');
        Route::put('/profile/info', 'updateProfile')->name('profile.info.update');
        Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
    });

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// ==================== CUSTOMER ROUTES ====================

Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::controller(CustomerDashboardController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/orders', 'orders')->name('orders');
        Route::get('/orders/{order}', 'orderDetails')->name('order.details');
        Route::get('/addresses', 'addresses')->name('addresses');
        Route::get('/addresses/{address}', 'addressDetails')->name('address.details');
    });

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'getWishList'])->name('wishlist');
});

// ==================== ADMIN ROUTES ====================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    // Brands
    Route::resource('brands', BrandController::class);
    Route::get('brands/{brand}/product/create', [AdminProductController::class, 'create'])
        ->name('brands.products.create');

    // Categories
    Route::resource('categories', CategoryController::class);
    Route::get('categories/{category}/product/create', [AdminProductController::class, 'create'])
        ->name('categories.products.create');
    Route::post('categories/{category}/update-status', [CategoryController::class, 'updateStatus'])
        ->name('categories.updateStatus');

    // Check SKU
    Route::post('products/check-sku', [AdminProductController::class, 'checkSku'])
        ->name('products.checkSku');

    // Products
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/update-status', [AdminProductController::class, 'updateStatus'])
        ->name('products.updateStatus');
    Route::post('products/{product}/update-stock', [AdminProductController::class, 'updateStock'])
        ->name('products.updateStock');

    // Product Variants
    Route::prefix('products/{product}/variants')->controller(ProductVariantController::class)->group(function () {
        Route::get('/', 'index')->name('products.variants.index');
        Route::get('/create', 'create')->name('products.variants.create');
        Route::post('/', 'store')->name('products.variants.store');
        Route::get('/{variant}/edit', 'edit')->name('products.variants.edit');
        Route::put('/{variant}', 'update')->name('products.variants.update');
        Route::delete('/{variant}', 'destroy')->name('products.variants.destroy');
        // Check SKU
        Route::post('/check-sku', 'checkSku')->name('products.variants.checkSku');
        // Check Variants Combination
        Route::post('/check-combination', [ProductVariantController::class, 'checkCombination'])
            ->name('products.variants.checkCombination');

        Route::post('/{variant}/check-combination-edit', [ProductVariantController::class, 'checkCombinationEdit'])
            ->name('products.variants.checkCombinationEdit');

        Route::post('/{variant}/check-sku-edit', [ProductVariantController::class, 'checkSkuEdit'])
            ->name('products.variants.checkSkuEdit');
    });

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::get('customers/data', [CustomerController::class, 'data'])->name('customers.data');
    Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');

    // Orders
    Route::resource('orders', OrderController::class);

    // Reports
    Route::resource('reports', ReportController::class);

    // Settings
    Route::resource('settings', SettingController::class);

    // Navigation admin routes
    Route::resource('navigation', 'Admin\NavigationController')->except(['show']);
    Route::prefix('navigation/{menu}')->group(function () {
        Route::post('items', 'Admin\NavigationController@storeItem')->name('navigation.items.store');
        Route::put('items/{item}', 'Admin\NavigationController@updateItem')->name('navigation.items.update');
        Route::delete('items/{item}', 'Admin\NavigationController@destroyItem')->name('navigation.items.destroy');

        Route::post('mega-content', 'Admin\NavigationController@storeMegaContent')->name('navigation.mega-content.store');
        Route::put('mega-content/{content}', 'Admin\NavigationController@updateMegaContent')->name('navigation.mega-content.update');
        Route::delete('mega-content/{content}', 'Admin\NavigationController@destroyMegaContent')->name('navigation.mega-content.destroy');
    });
});

// ==================== MISC ROUTES ====================

Route::get('/testing', [TestingController::class, 'index'])->name('testing');

Route::fallback(function () {
    if (Gate::allows('isAdmin')) {
        return redirect()->route('admin.dashboard')->with('info', 'The page you were looking for does not exist. You have been redirected to the admin dashboard.');
    }
    abort(404);
});
