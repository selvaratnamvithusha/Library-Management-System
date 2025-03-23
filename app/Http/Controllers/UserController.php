<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;

use Illuminate\Http\Request;

use GuzzleHttp\Client;

class UserController extends Controller
{
    // public function getUserHistories($userId)
    // {
    //     set_time_limit(300);

    //     $user = User::with([
    //         'searchHistories',
    //         'bookViewHistories.book',
    //         'borrowHistories.book'
    //     ])->find($userId);

    //     if (!$user) {
    //         return response()->json(['message' => 'User not found'], 404);
    //     }


    //     $userHistories = [
    //         'id' => $user->id,
    //         'name' => $user->name,
    //         'email' => $user->email,
    //         'search_histories' => $user->searchHistories,
    //         'book_view_histories' => $user->bookViewHistories->map(function ($history) {
    //             return [
    //                 'id' => $history->id,
    //                 'viewed_at' => $history->viewed_at,
    //                 'book' => $history->book,
    //             ];
    //         }),
    //         'borrow_histories' => $user->borrowHistories->map(function ($history) {
    //             return [
    //                 'id' => $history->id,
    //                 'status' => $history->status,
    //                 'borrowed_at' => $history->borrowed_at,
    //                 'returned_at' => $history->returned_at,
    //                 'book' => $history->book,
    //             ];
    //         }),
    //     ];

    //     $books = Book::with('category')->get();
    //     $bookData = $books->toArray();


    //     $client = new Client();

    //     $response = $client->post('http://localhost:11434/api/chat', [
    //         'headers' => [
    //             'Content-Type' => 'application/json'
    //         ],
    //         'json' => [
    //             'model' => 'llama3.2',
    //             'messages' => [
    //                 [
    //                     'role' => 'user',
    //                     'content' => 'why is the sky blue?'
    //                 ]
    //             ],
    //             'stream' => false
    //         ],
    //         'timeout' => 300,
    //     ]);

    //     $body = json_decode($response->getBody()->getContents(), true);

    //     $messageContent = $body['message']['content'] ?? 'No content found';


    //     return response()->json(['content' => $messageContent], 200);
    // }





    public function getUserHistories($userId)
    {
        set_time_limit(300);

        $user = User::with([
            'searchHistories',
            'bookViewHistories.book',
            'borrowHistories.book'
        ])->find($userId);

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

        return response()->json(['recommended_books' => $recommendations], 200);
    }
}
