<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('renter.home');
    }
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/properties', \App\Livewire\Admin\Properties::class)->name('admin.properties');
    Route::get('/users', \App\Livewire\Admin\Users::class)->name('admin.users');
    Route::get('/reservations', \App\Livewire\Admin\Reservations::class)->name('admin.reservations');
    Route::get('/inquiries', \App\Livewire\Admin\Inquiries::class)->name('admin.inquiries');
    Route::get('/reviews', \App\Livewire\Admin\Reviews::class)->name('admin.reviews');
    Route::get('/property-types', \App\Livewire\Admin\PropertyTypes::class)->name('admin.property-types');
    Route::get('/cities', \App\Livewire\Admin\Cities::class)->name('admin.cities');

    // Settings
    Route::get('/settings', fn () => redirect()->route('admin.settings.profile'))->name('admin.settings');
    Route::get('/settings/profile', \App\Livewire\Admin\Settings\Profile::class)->name('admin.settings.profile');
    Route::get('/settings/password', \App\Livewire\Admin\Settings\Password::class)->name('admin.settings.password');
    Route::get('/settings/delete', \App\Livewire\Admin\Settings\DeleteAccount::class)->name('admin.settings.delete');

    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('admin.logout');
});

// Renter Routes
Route::prefix('renter')->middleware(['auth', 'renter'])->group(function () {
    Route::get('/', \App\Livewire\Renter\Home::class)->name('renter.home');
    Route::get('/explore', \App\Livewire\Renter\Explore::class)->name('renter.explore');
    Route::get('/favorites', \App\Livewire\Renter\Favorites::class)->name('renter.favorites');
    Route::get('/reservations', \App\Livewire\Renter\MyReservations::class)->name('renter.reservations');
    Route::get('/reviews', \App\Livewire\Renter\MyReviews::class)->name('renter.reviews');
    Route::get('/profile', \App\Livewire\Renter\Profile::class)->name('renter.profile');
    Route::get('/inquiries', \App\Livewire\Renter\MyInquiries::class)->name('renter.inquiries');
    
    // Settings
    Route::get('/settings', fn () => redirect()->route('renter.settings'))->name('renter.settings');
    Route::get('/settings/account', \App\Livewire\Renter\AccountSettings::class)->name('renter.settings.account');

    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('renter.logout');
});
