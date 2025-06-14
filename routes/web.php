<?php

use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\Pages\Blog\Index;
use App\Livewire\Pages\Blog\Show;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\ProfileForm;
use App\Livewire\SuccessPage;
use App\Livewire\WishlistPage;
use Illuminate\Support\Facades\Route;

// openbare routes voor alle users
Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/products', ProductsPage::class);
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/products/{slug}', ProductDetailPage::class);
Route::get('/blog', Index::class)->name('blog.index');
Route::get('/blog/{slug}', Show::class)->name('blog.show');

// openbare routes voor niet ingelogde users
Route::middleware('guest')->group(function() {
    Route::get('/login', LoginPage::class)->name('login');
    Route::get('/register', RegisterPage::class);
    Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
});

// enkel voor ingelogde users
Route::middleware('auth')->group(function() {
    // navbar logout knop voor ingelogde user
    Route::get('/logout', function() {
       auth()->logout();
       return redirect('/');
    });
    Route::get('/checkout', CheckoutPage::class);
    Route::get('/profile', ProfileForm::class)->name('profile');
    Route::get('/my-orders', MyOrdersPage::class);
    Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('my-orders.show');
    Route::get('/success', SuccessPage::class)->name('success');
    Route::get('/cancel', CancelPage::class)->name('cancel');
    Route::get('/wishlist', WishlistPage::class)->name('wishlist');

    Route::get('/my-orders/{order_id}/invoice', function ($order_id) {
        $order = \App\Models\Order::with('items.product', 'user', 'address')->findOrFail($order_id);

        if ($order->user_id !== auth()->id()) {
            abort(403); // Je mag alleen je eigen orders zien
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', [
            'order' => $order
        ]);

        return $pdf->download('factuur-order-' . $order->id . '.pdf');
    })->name('my-orders.invoice')->middleware('auth');


});

