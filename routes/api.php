<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\HomeApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::post('/login', [AuthController::class, 'login']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



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


Route::prefix('books')->group(function () {
    Route::get('/', [BookApiController::class, 'index']);      // GET all books
    Route::post('/', [BookApiController::class, 'store']);     // POST create book
    Route::put('/{id}', [BookApiController::class, 'update']); // PUT update book
    Route::delete('/{id}', [BookApiController::class, 'destroy']); // DELETE book

});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryApiController::class, 'index']);  
    Route::get('/{id}', [CategoryApiController::class, 'show']);    
    Route::post('/', [CategoryApiController::class, 'store']);     
    Route::put('/{id}', [CategoryApiController::class, 'update']); 
    Route::delete('/{id}', [CategoryApiController::class, 'destroy']); 

});

Route::get('/user/{userId}/histories', [UserApiController::class, 'getUserHistories']);

Route::post('/borrow/{id}', [HomeApiController::class, 'borrowBook'])->middleware('auth:api');
Route::get('/borrow-history', [HomeApiController::class, 'borrowHistory'])->middleware('auth:api');
Route::get('/search', [HomeApiController::class, 'searchBooks']);
Route::get('/book/{id}', [HomeApiController::class, 'bookDetails']);
Route::get('/recommend', [HomeApiController::class, 'recommendBooks']);