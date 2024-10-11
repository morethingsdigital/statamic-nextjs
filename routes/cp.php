<?php

use Illuminate\Support\Facades\Route;
use Morethingsdigital\StatamicNextjs\Http\Controllers\IndexController;
use Morethingsdigital\StatamicNextjs\Http\Controllers\InvalidateController;

Route::prefix('nextjs')->name('nextjs.')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::post('/invalidate', [InvalidateController::class, 'invalidate'])->name('invalidate');
});
