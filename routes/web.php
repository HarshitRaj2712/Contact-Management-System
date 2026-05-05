<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactPhoneController;
use App\Http\Controllers\ContactEmailController;
use App\Http\Controllers\ContactAddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Contact routes
    Route::resource('contacts', ContactController::class);
    
    // Trash routes
    Route::get('/contacts-trash', [ContactController::class, 'trash'])->name('contacts.trash');
    Route::post('/contacts/{contact}/restore', [ContactController::class, 'restore'])->name('contacts.restore');
    Route::delete('/contacts/{contact}/force-delete', [ContactController::class, 'forceDelete'])->name('contacts.forceDelete');
    Route::post('/contacts/{contact}/favorite', [ContactController::class, 'toggleFavorite'])->name('contacts.toggleFavorite');

    // Nested phone routes
    Route::post('/phones', [ContactPhoneController::class, 'store'])->name('phones.store');
    Route::delete('/phones/{phone}', [ContactPhoneController::class, 'destroy'])->name('phones.destroy');

    // Nested email routes
    Route::post('/emails', [ContactEmailController::class, 'store'])->name('emails.store');
    Route::delete('/emails/{email}', [ContactEmailController::class, 'destroy'])->name('emails.destroy');

    // Nested address routes
    Route::post('/addresses', [ContactAddressController::class, 'store'])->name('addresses.store');
    Route::delete('/addresses/{address}', [ContactAddressController::class, 'destroy'])->name('addresses.destroy');

    // Category routes
    Route::resource('categories', CategoryController::class);

    // Tag routes
    Route::resource('tags', TagController::class);
});

require __DIR__.'/auth.php';
