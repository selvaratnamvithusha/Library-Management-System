<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\ApiController;

Route::get('user/{id}',[ApiController::class,'user']);

Route::middleware('auth:api')->group(function () {
    Route::get('/admin', [AdminApiController::class, 'index']);
    Route::get('/admin/borrow-request', [AdminApiController::class, 'borrowRequest']);
    Route::post('/admin/approve-book/{id}', [AdminApiController::class, 'approveBook']);
    Route::post('/admin/return-book/{id}', [AdminApiController::class, 'returnBook']);
    Route::post('/admin/reject-book/{id}', [AdminApiController::class, 'rejectBook']);
});


Route::get('/books-data',[BookController::class,'getBooksData'])->name('books.data');
Route::get('/user/{userId}/histories', [UserController::class, 'getUserHistories']);