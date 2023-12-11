<?php

use Illuminate\Support\Facades\Route;
use Morethingsdigital\StatamicNextjs\Http\Controllers\IndexController;

Route::prefix('nextjs')->name('nextjs.')->group(function () {
  Route::get('/', [IndexController::class, 'index'])->name('index');
});
