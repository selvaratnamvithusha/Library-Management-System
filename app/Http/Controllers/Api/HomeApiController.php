<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class HomeApiController extends Controller
{
    // Borrow a book
    public function borrowBook($id)
    {
        $data = Book::find($id);
        if ($data && $data->quantity > 0) {
            if (Auth::check()) {
                $borrow = new Borrow();
                $borrow->book_id = $id;
                $borrow->user_id = Auth::id();
                $borrow->status = 'Applied';
                $borrow->save();

                return response()->json(['message' => 'Borrow request has been sent'], 200);
            }
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        return response()->json(['message' => 'Not enough books available'], 400);
    }

    // Get borrow history
    public function borrowHistory()
    {
        if (Auth::check()) {
            $history = Borrow::where('user_id', Auth::id())->get();
            return response()->json($history, 200);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }

    // Search books by title or author
    public function searchBooks(Request $request)
    {
        $search = $request->get('search');
        $data = Book::where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('author_name', 'LIKE', '%' . $search . '%')
                    ->get();

        return response()->json($data, 200);
    }

    // Get book details
    public function bookDetails($id)
    {
        $book = Book::find($id);

        if ($book) {
            return response()->json($book, 200);
        }

        return response()->json(['message' => 'Book not found'], 404);
    }

    // Get recommended books based on user history
    public function recommendBooks()
    {
        if (Auth::check()) {
            $user = User::with(['searchHistories', 'bookViewHistories.book', 'borrowHistories.book'])->find(Auth::id());

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Prepare user history
            $userHistories = [
                'search_histories' => $user->searchHistories->pluck('query')->toArray(),
                'book_view_histories' => $user->bookViewHistories->pluck('book.title')->toArray(),
                'borrow_histories' => $user->borrowHistories->pluck('book.title')->toArray(),
            ];

            // Get books data
            $books = Book::with('category')->get();
            $bookData = $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'category' => $book->category->name ?? 'Unknown',
                ];
            })->toArray();

            // Make an API request to LLaMA model for recommendations (or any other service)
            $client = new Client();
            $prompt = "Based on the user's history: " . implode(', ', $userHistories['search_histories']) .
                      ", viewed books: " . implode(', ', $userHistories['book_view_histories']) .
                      ", and borrowed books: " . implode(', ', $userHistories['borrow_histories']) .
                      ", suggest 5 books from the following list: " . json_encode($bookData);

            $response = $client->post('http://localhost:11434/api/chat', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'model' => 'llama3.2',
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'stream' => false,
                ],
                'timeout' => 300,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            $recommendations = $body['message']['content'] ?? 'No recommendations found';

            preg_match_all('/\d+/', $recommendations, $matches);
            $bookIds = array_map('intval', $matches[0]);
            $recommendedBooks = Book::whereIn('id', $bookIds)->get();

            return response()->json($recommendedBooks, 200);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }

}
