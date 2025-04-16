<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Domains\User\UserController;

Route::prefix('users')->group(function () {
    // Display a listing of users
    Route::get('/', [UserController::class, 'index'])->name('users.index');

   // Create a new user
   Route::post('/', [UserController::class, 'store'])->name('users.store');

    // Update a specific user by ID
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update')
    ->where('id', '[0-9]+');

     // Display a specific user by ID
   Route::get('/{id}', [UserController::class, 'show'])->name('users.show')
   ->where('id', '[0-9]+');

   // Remove a specific user by ID
   Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy')
       ->where('id', '[0-9]+');
});