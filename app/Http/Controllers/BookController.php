<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $books = Book::with('category')
                        ->where('title', 'like', '%' . $search . '%') 
                        ->orWhere('author_name', 'like', '%' . $search . '%')
                       ->paginate(10);
        $categories = Category::all(); 
        return view('admin.book', compact('books', 'categories', 'search'));
    }

    


    public function store(Request $request)
{
    $book = new Book($request->except(['book_img', 'author_img']));

    if ($request->hasFile('book_img')) {
        $book->book_img = $request->file('book_img')->store('book', 'public');
    }

    if ($request->hasFile('author_img')) {
        $book->author_img = $request->file('author_img')->store('author', 'public');
    }

    $book->save();

    return redirect()->route('books.index')->with('success', 'Book added successfully!');
}

    
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->fill($request->except(['author_img', 'book_img']));
        if ($request->hasFile('author_img')) {
            $book->author_img = $request->file('author_img')->store('author', 'public');
        }
        if ($request->hasFile('book_img')) {
            $book->book_img = $request->file('book_img')->store('book', 'public');
        }
        $book->save();
        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }

    public function getBooksData()
    {
        $books=Book::with('category')->get();
        $bookData=$books->toArray(); 
        return response()->json($bookData);
    }

    
}
