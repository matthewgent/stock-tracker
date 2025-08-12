<?php

use App\Http\Controllers\Web;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('', [Web\GuestController::class, 'home'])->name('home')->middleware('guest');

Route::get('legal', [Web\GuestController::class, 'legal'])->name('legal');
Route::get('contact-us', [Web\GuestController::class, 'contactUs'])->name('contact-us');
Route::get('plans', [Web\GuestController::class, 'plans'])->name('plans');
Route::get('how-it-works', [Web\GuestController::class, 'howItWorks'])->name('how-it-works');

Route::group(['prefix' => 'member', 'as' => 'member.'], function () {

    Route::group(['middleware' => ['auth', 'non-premium']], function () {
        Route::get('premium', [Web\PremiumController::class, 'show'])->name('premium');
        Route::post('premium/payment', [Web\PremiumController::class, 'processPayment'])->name('premium.payment');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('premium/cancel', [Web\PremiumController::class, 'cancel'])->name('premium.cancel');

        Route::get('wealth', [Web\BoardController::class, 'wealth'])->name('wealth');
        Route::get('assets', [Web\BoardController::class, 'assets'])->name('assets');
        Route::get('debts', [Web\BoardController::class, 'debts'])->name('debts');
        Route::get('currencies', [Web\BoardController::class, 'currencies'])->name('currencies');

        Route::get('settings', [Web\SettingsController::class, 'index'])->name('settings');
        Route::patch('settings', [Web\SettingsController::class, 'update'])->name('settings.update');

        Route::get('account', [Web\AccountController::class, 'show'])->name('account');
        Route::patch('account', [Web\AccountController::class, 'update'])->name('account.update');
        Route::delete('account', [Web\AccountController::class, 'destroy'])->name('account.destroy');

        Route::resource('item', Web\ItemController::class)->only([
            'show',
            'create',
            'store',
            'update',
            'destroy',
        ]);

        Route::resource('item-value', Web\ItemValueController::class)->only([
            'store',
            'update',
            'destroy',
        ]);

        Route::resource('stock-quantity', Web\StockQuantityController::class)->only([
            'store',
            'update',
            'destroy',
        ]);
    });
});

