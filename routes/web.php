<?php
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\Admin;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\ExportController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\Api\AdminApiController;



 Route::get('/',[HomeController::class,'index']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



route::get('/home',[AdminController::class,'index']);


Route::middleware(['admin'])->group(function () {
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::get('/books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');
Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

Route::get('/categories', [CategoryController::class, 'viewCategories'])->name('categories.view');
Route::post('/add_category', [CategoryController::class, 'addCategory'])->name('category.add');
Route::get('/edit_category/{id}', [CategoryController::class, 'editCategory'])->name('category.edit');
Route::post('/update_category/{id}', [CategoryController::class, 'updateCategory'])->name('category.update');
Route::delete('/delete_category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::post('/check_category', [CategoryController::class, 'checkCategory'])->name('check_category');


route::get('/borrow_request',[AdminController::class,'borrow_request']);
route::get('/approve_book/{id}',[AdminController::class,'approve_book']);
route::get('/return_book/{id}',[AdminController::class,'return_book']);
route::get('/rejected_book/{id}',[AdminController::class,'rejected_book']);

});

route::get('/book_details/{id}',[HomeController::class,'book_details']);
route::get('/borrow_books/{id}',[HomeController::class,'borrow_books']);

route::get('/book_history',[HomeController::class,'book_history']);
route::get('/cancel_req/{id}',[HomeController::class,'cancel_req']);

route::get('/explore',[HomeController::class,'explore']);

route::get('/search',[HomeController::class,'search']);
route::get('/cat_search/{id}',[HomeController::class,'cat_search']);



Route::get('/export-books', [ExportController::class, 'exportBooksCsv'])->name('export.books');
Route::get('/export-user-history/{userId}', [ExportController::class, 'exportUserHistoryCsv'])->name('export.user.history');

// Route::get('/book-recommendations/{user_id}', [RecommendationController::class, 'getRecommendations']);

route::get('/recommend',[HomeController::class,'recommend']);


Route::middleware('auth:api')->group(function () {
    Route::get('/admin', [AdminApiController::class, 'index']);
    Route::get('/admin/borrow-request', [AdminApiController::class, 'borrowRequest']);
    Route::post('/admin/approve-book/{id}', [AdminApiController::class, 'approveBook']);
    Route::post('/admin/return-book/{id}', [AdminApiController::class, 'returnBook']);
    Route::post('/admin/reject-book/{id}', [AdminApiController::class, 'rejectBook']);
});