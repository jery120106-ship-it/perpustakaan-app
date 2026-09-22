<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', [BookController::class, 'index']);
Route::get('/books', [BookController::class, 'index']);
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

Route::post('/categories', [BookController::class, 'storeCategory'])->name('categories.store');
Route::post('/publishers', [BookController::class, 'storePublisher'])->name('publishers.store');

Route::post('/borrowings', [BookController::class, 'storeBorrowing'])->name('borrowings.store');
Route::put('/borrowings/{id}', [BookController::class, 'updateBorrowing'])->name('borrowings.update');