<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;

class BookApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->get();
        return response()->json($books, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'book_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $book = new Book($request->except(['book_img', 'author_img']));

        if ($request->hasFile('book_img')) {
            $book->book_img = $request->file('book_img')->store('book', 'public');
        }

        if ($request->hasFile('author_img')) {
            $book->author_img = $request->file('author_img')->store('author', 'public');
        }

        $book->save();

        return response()->json(['message' => 'Book added successfully!', 'book' => $book], 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $book->fill($request->except(['book_img', 'author_img']));

        if ($request->hasFile('book_img')) {
            $book->book_img = $request->file('book_img')->store('book', 'public');
        }

        if ($request->hasFile('author_img')) {
            $book->author_img = $request->file('author_img')->store('author', 'public');
        }

        $book->save();

        return response()->json(['message' => 'Book updated successfully!', 'book' => $book], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted successfully!'], 200);
    }
    
    }

