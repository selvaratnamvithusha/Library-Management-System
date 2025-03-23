<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use App\Models\Category;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\SearchHistory;
use App\Models\BookViewHistory;
use App\Models\BorrowHistory;
use GuzzleHttp\Client;

class HomeController extends Controller
{
  public function index()
  {
    $data = Book::all();

    return view('home.index', compact('data'));
  }

  // public function book_details($id)
  // {
  //     $book=Book::find($id);
  //     return view('home.book_details', compact('book'));
  // }

  public function borrow_books($id)
  {
    $data = Book::find($id);
    $book_id = $id;
    $quantity = $data->quantity;
    if ($quantity >= '1') {
      if (Auth::id()) {
        $user_id = Auth::user()->id;


        $borrow = new Borrow;
        $borrow->book_id = $book_id;
        $borrow->user_id = $user_id;
        $borrow->status = 'Applied';

        $borrow->save();

        BorrowHistory::create([
          'user_id' => $user_id,
          'book_id' => $book_id,
          'status' => 'Applied',
          'borrowed_at' => now()
        ]);

        return redirect()->back()->with('message', 'A request is send to admin to borrow this book');
      } else {
        return redirect('/login');
      }
    } else {
      return redirect()->back()->with('message', 'Not enough book Available');
    }
  }

  public function book_history()
  {
    if (Auth::id()) {
      $userid = Auth::user()->id;
      $data = Borrow::where('user_id', '=', $userid)->get();
      return view('home.book_history', compact('data'));
    }
  }

  public function cancel_req($id)
  {
    $data = Borrow::find($id);
    $data->delete();
    return redirect()->back()->with('message', 'Book Borrow request canceled successfully');
  }

  public function explore()
  {
    $category = Category::all();
    $data = Book::all();
    return view('home.explore', compact('data', 'category'));
  }

  //     public function search(Request $request)
  // {

  //     $category=Category::all();

  //     $search = $request->search; // Correctly assign the search query to $search
  //     $data = Book::where('title', 'LIKE', '%' . $search . '%')->orWhere('author_name', 'LIKE', '%' . $search . '%')->get(); // Use $search here
  //     return view('home.explore', compact('data', 'category'));
  // }

  public function cat_search($id)
  {
    $category = Category::all();
    $data = Book::where('category_id', $id)->get();
    return view('home.explore', compact('data', 'category'));
  }

  public function search(Request $request)
  {
    $category = Category::all();
    $search = $request->search;

    // Save search history if user is authenticated
    if (Auth::check() && !empty($search)) {
      SearchHistory::create([
        'user_id' => Auth::id(),
        'search_query' => $search
      ]);
    }

    $data = Book::where('title', 'LIKE', '%' . $search . '%')
      ->orWhere('author_name', 'LIKE', '%' . $search . '%')
      ->get();

    return view('home.explore', compact('data', 'category'));
  }


  // public function viewBook($bookId)
  // {
  //     $book = Book::findOrFail($bookId);

  //     // Save view history
  //     if (Auth::check() && !empty($search)){
  //         BookViewHistory::create([
  //             'user_id' => Auth::id(),
  //             'book_id' => $book->id,
  //         ]);
  //     }

  //     return view('books.details', compact('book'));
  // }

  public function book_details($id)
  {
    // Fetch the book details
    $book = Book::find($id);

    // Log the book view history
    if (Auth::check()) { // Ensure the user is logged in
      BookViewHistory::create([
        'user_id' => Auth::id(),
        'book_id' => $book->id,
        'viewed_at' => now(), // Current timestamp
      ]);
    }

    // Return the view with book details
    return view('home.book_details', compact('book'));
  }

  public function updateBorrowHistory($borrowId, $newStatus)
  {
    $borrow = Borrow::find($borrowId);

    if ($borrow) {
      BorrowHistory::where([
        'user_id' => $borrow->user_id,
        'book_id' => $borrow->book_id,
      ])
        ->latest()
        ->first()
        ->update([
          'status' => $newStatus,
          'return_date' => $newStatus === 'returned' ? now() : null
        ]);
    }
  }

  public function recommend()
  {
    set_time_limit(300);
    if (Auth::id()) {
      $userid = Auth::user()->id;



      $user = User::with([
        'searchHistories',
        'bookViewHistories.book',
        'borrowHistories.book'
      ])->find($userid);

      if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
      }

      $userHistories = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'search_histories' => $user->searchHistories->pluck('query')->toArray(),
        'book_view_histories' => $user->bookViewHistories->pluck('book.title')->toArray(),
        'borrow_histories' => $user->borrowHistories->pluck('book.title')->toArray(),
      ];

      // Get all books
      $books = Book::with('category')->get();
      $bookData = $books->map(function ($book) {
        return [
          'id' => $book->id,
          'title' => $book->title,
          'author' => $book->author,
          'category' => $book->category->name ?? 'Unknown',
        ];
      })->toArray();

      $client = new Client();

      $prompt = "Based on the user's search history: " . implode(', ', $userHistories['search_histories']) .
        ", viewed books: " . implode(', ', $userHistories['book_view_histories']) .
        ", and borrowed books: " . implode(', ', $userHistories['borrow_histories']) .
        ", Suggest 5 books from the following list that the user might like: " . json_encode($bookData) .
        ", can you return suggested books ids in array i dont need any text from your side just return only 5 books id in array";

      $response = $client->post('http://localhost:11434/api/chat', [
        'headers' => [
          'Content-Type' => 'application/json'
        ],
        'json' => [
          'model' => 'llama3.2',
          'messages' => [
            [
              'role' => 'user',
              'content' => $prompt
            ]
          ],
          'stream' => false
        ],
        'timeout' => 300,
      ]);

      $body = json_decode($response->getBody()->getContents(), true);
      $recommendations = $body['message']['content'] ?? 'No recommendations found';
      preg_match_all('/\d+/', $recommendations, $matches);

      // Convert matches to an array of integers
      $bookIds = array_map('intval', $matches[0]);
      $recommendedBooks = [];

      foreach ($bookIds as $id) {
        $book = Book::find($id);
        if ($book) {
          $recommendedBooks[] = $book;
        }
      }

      return view('home.recommend', compact('recommendedBooks'));
    } else {
      return response()->json(['message' => 'Please login'], 401);
    }
  }
}
