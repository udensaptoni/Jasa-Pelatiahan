<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\FrontendTestimonialController; // ✅ Testimoni frontend

use App\Models\Product;
use App\Models\Article;

// =================== FRONTEND ===================

// Halaman utama
Route::get('/', [FrontendController::class, 'index'])->name('home');

// Landing page semua produk
Route::get('/produk', function () {
    $q = request()->query('q');
    $productsQuery = Product::query();
    if ($q) {
        $productsQuery->where('name', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%");
    }
    $products = $productsQuery->get();
    return view('produk-index', compact('products','q'));
})->name('produk.index');

// Detail produk menggunakan controller
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.detail');

// Form pendaftaran Produk/Jasa
Route::get('/produk/{id}/register', function ($id) {
    $product = Product::findOrFail($id);
    return view('produk-register', compact('product'));
})->name('produk.register.form');

// Submit pendaftaran Produk/Jasa
Route::post('/produk/{id}/register', [RegistrationController::class, 'store'])->name('produk.register');

// Detail Artikel
// Semua Artikel (index)
Route::get('/artikel', function () {
    $articles = Article::latest()->get();
    return view('artikel-index', compact('articles'));
})->name('artikel.index');

// Detail Artikel
Route::get('/artikel/{id}', function ($id) {
    $article = Article::findOrFail($id);
    return view('artikel-detail', compact('article'));
})->name('artikel.detail');

// About (public)
Route::get('/about', [FrontendController::class, 'about'])->name('about.show');

// =================== FRONTEND TESTIMONIALS ===================

// Use route names and view that match the frontend view at resources/views/frontend/testimoni.blade.php
Route::get('/testimonials', [FrontendTestimonialController::class, 'index'])->name('frontend.testimoni.index');
Route::post('/testimonials', [FrontendTestimonialController::class, 'store'])->name('frontend.testimoni.store');

// =================== AUTH ===================

// Login & Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =================== ADMIN ===================

Route::name('admin.')->middleware('admin')->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD Admin
    Route::resource('articles', ArticleController::class);
    Route::resource('products', ProductController::class);
    Route::resource('about', AboutUsController::class);

    // Registrations Admin
    Route::resource('registrations', \App\Http\Controllers\Admin\RegistrationController::class)
        ->only(['index', 'show', 'destroy']);

    // Testimonial Admin CRUD
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class)
        ->except(['show']);

    // Approve/unapprove testimonial (quick action)
    Route::post('/testimonials/{testimonial}/approve', [\App\Http\Controllers\Admin\TestimonialController::class, 'approve'])
        ->name('testimonials.approve');

    // Export Registrasi
    Route::get('/registrations/export/excel', [\App\Http\Controllers\Admin\RegistrationController::class, 'exportExcel'])
        ->name('registrations.export.excel');
    Route::get('/registrations/export/pdf', [\App\Http\Controllers\Admin\RegistrationController::class, 'exportPDF'])
        ->name('registrations.export.pdf');

    // Payments admin
    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    // Reconcile payment (re-query provider)
    Route::post('/payments/{payment}/reconcile', [\App\Http\Controllers\Admin\PaymentController::class, 'reconcile'])->name('payments.reconcile');

    // Reviews moderation
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
});

// Payments (public)
Route::get('/payments/{registration}/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('payments.create');
Route::post('/payments/{registration}', [App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');
Route::get('/payments/show/{payment}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payments.show');

// Reviews
Route::post('/products/{product}/reviews', [App\Http\Controllers\ReviewsController::class, 'store'])->name('products.reviews.store');

// Status endpoint (AJAX polling)
Route::get('/payments/status/{payment}', [App\Http\Controllers\PaymentController::class, 'status'])->name('payments.status');

// Webhook (public endpoint)
Route::post('/payments/webhook/midtrans', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('payments.webhook.midtrans');

// Download QR via proxy (streams Midtrans QR PNG to user)
Route::get('/payments/{payment}/download-qr', [App\Http\Controllers\PaymentController::class, 'downloadQr'])->name('payments.download_qr');

// Invoice (HTML / PDF)
Route::get('/payments/{payment}/invoice', [App\Http\Controllers\PaymentController::class, 'invoice'])->name('payments.invoice');

// User dashboard (simple)
Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->middleware('auth')->name('user.dashboard');
