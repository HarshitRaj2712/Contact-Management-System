<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactPhoneController;
use App\Http\Controllers\ContactEmailController;
use App\Http\Controllers\ContactAddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Contact routes
    // Import/Export and utility routes must come before Route::resource('contacts', ...) so static paths like /contacts/export don't get captured by /contacts/{contact}
    Route::get('/contacts/export', [\App\Http\Controllers\ImportExportController::class, 'exportCsv'])->name('contacts.export');
    Route::get('/contacts/export-pdf', [\App\Http\Controllers\ImportExportController::class, 'exportPdf'])->name('contacts.exportPdf');
    Route::post('/contacts/import', [\App\Http\Controllers\ImportExportController::class, 'importCsv'])->name('contacts.import');
    Route::get('/contacts/duplicates', [\App\Http\Controllers\ImportExportController::class, 'duplicates'])->name('contacts.duplicates');
    Route::post('/contacts/bulk-delete', [\App\Http\Controllers\ImportExportController::class, 'bulkDelete'])->name('contacts.bulkDelete');

    Route::resource('contacts', ContactController::class);

    // Trash routes
    Route::get('/contacts-trash', [ContactController::class, 'trash'])->name('contacts.trash');
    Route::get('/contacts/{contact}/share', [ContactController::class, 'share'])->name('contacts.share');
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

    // Activity logs viewer
    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity.logs');

    // Locale switch
    Route::get('/lang/{locale}', [\App\Http\Controllers\LocaleController::class, 'switch'])->name('locale.switch');
});

require __DIR__.'/auth.php';
