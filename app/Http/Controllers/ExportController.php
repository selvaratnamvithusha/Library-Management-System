<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadeResponse;

class ExportController extends Controller
{
   //Export books data to CSV
    public function exportBooksCsv()
    {
        $books = Book::with('category')->get();

        $fileName = "books_data.csv";

        // Define the headers to force download
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        'Cache-Control' => 'no-store, no-cache',
        'Pragma' => 'no-cache',
    ];

        return response()->stream(function () use ($books) {
        $handle = fopen('php://output', 'w');

        // Set CSV header
        fputcsv($handle, ["Book ID", "Book Title", "Author Name", "Category", "Quantity", "Description"]);

        // Add book data
        foreach ($books as $book) {
            fputcsv($handle, [
                $book->id,
                $book->title,
                $book->author_name,
                $book->category? $book->category->cat_title: 'Uncategorized', // Handle missing category
                $book->quantity,
                $book->description,
            ]);
        }

        fclose($handle);
    },200,$headers);
       
    }



    // Export user history to CSV
//     public function exportUserHistoryCsv($userId)
// {
//     $user = User::with([
//         'searchHistories',
//         'bookViewHistories.book',
//         'borrowHistories.book'
//     ])->find($userId);

//     if (!$user) {
//         return response()->json(['message' => 'User not found'], 404);
//     }

//     $csvData = "User ID, Name, Email, Action Type, Book ID, Book Title, Timestamp\n";

//     foreach ($user->searchHistories ?? [] as $search) {
//         $csvData .= "{$user->id},{$user->name},{$user->email},Search,,,{$search->searched_at}\n";
//     }

//     foreach ($user->bookViewHistories ?? [] as $view) {
//         $csvData .= "{$user->id},{$user->name},{$user->email},Viewed," .
//             ($view->book ? "{$view->book->id},{$view->book->title}" : ",") . ",{$view->viewed_at}\n";
//     }

//     foreach ($user->borrowHistories ?? [] as $borrow) {
//         $csvData .= "{$user->id},{$user->name},{$user->email},Borrowed," .
//             ($borrow->book ? "{$borrow->book->id},{$borrow->book->title}" : ",") . ",{$borrow->borrowed_at}\n";
//     }

//     $fileName = "user_history_{$userId}.csv";
//     $filePath = "public/$fileName";
//     Storage::put($filePath, $csvData);

//     return response()->download(storage_path("app/$filePath"), $fileName, [
//         'Content-Type' => 'text/csv',
//     ]);
// }

// Export user history to CSV

// public function exportUserHistoryCsv($userId)
// {
//     $user = User::with([
//         'searchHistories',
//         'bookViewHistories.book',
//         'borrowHistories.book'
//     ])->find($userId);

//     if (!$user) {
//         return response()->json(['message' => 'User not found'], 404);
//     }

//     $fileName = "user_history_{$userId}.csv";

//     // Define response headers for CSV download
//     $headers = [
//         'Content-Type' => 'text/csv',
//         'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
//         'Cache-Control' => 'no-store, no-cache',
//         'Pragma' => 'no-cache',
//     ];

//     return response()->stream(function () use ($user) {
//         $handle = fopen('php://output', 'w');

//         // Write CSV header
//         fputcsv($handle, ["User ID", "Name", "Email", "Action Type", "Search Query", "Book ID", "Book Title", ]);

//         // Write search history (including search query)
//         foreach ($user->searchHistories ?? [] as $search) {
//             fputcsv($handle, [
//                 $user->id,
//                 $user->name,
//                 $user->email,
//                 "Search",
//                 $search->query ?? "", // Display search query
//                 "", // No book ID for search
//                 "", // No book title for search
                
//             ]);
//         }

//         // Write book view history
//         foreach ($user->bookViewHistories ?? [] as $view) {
//             fputcsv($handle, [
//                 $user->id,
//                 $user->name,
//                 $user->email,
//                 "Viewed",
//                 "", // No search query for book views
//                 $view->book?->id ?? "", // Handle null book ID
//                 $view->book?->title ?? "", // Handle null book title
                
//             ]);
//         }

//         // Write borrow history
//         foreach ($user->borrowHistories ?? [] as $borrow) {
//             fputcsv($handle, [
//                 $user->id,
//                 $user->name,
//                 $user->email,
//                 "Borrowed",
//                 "", // No search query for borrowed books
//                 $borrow->book?->id ?? "",
//                 $borrow->book?->title ?? "",
                
//             ]);
//         }

//         fclose($handle);
//     }, 200, $headers);
// }

public function exportUserHistoryCsv($userId)
{
    $user = User::with([
        'searchHistories',
        'bookViewHistories.book',
        'borrowHistories.book'
    ])->find($userId);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $fileName = "user_history_{$userId}.csv";

    // Define response headers for CSV download
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        'Cache-Control' => 'no-store, no-cache',
        'Pragma' => 'no-cache',
    ];

    return response()->stream(function () use ($user) {
        $handle = fopen('php://output', 'w');

        // Write CSV header
        fputcsv($handle, ["User ID", "Name", "Email", "Action Type", "Search Query", "Book ID", "Book Title"]);

        // Write search history (including search query)
        foreach ($user->searchHistories as $search) {
            fputcsv($handle, [
                $user->id,
                $user->name,
                $user->email,
                "Search",
                isset($search->search_query) ? $search->search_query : "N/A", // Ensure query exists
                "", // No book ID for search
                "", // No book title for search
            ]);
        }

        // Write book view history
        foreach ($user->bookViewHistories as $view) {
            fputcsv($handle, [
                $user->id,
                $user->name,
                $user->email,
                "Viewed",
                "", // No search query for book views
                isset($view->book) ? $view->book->id : "N/A",
                isset($view->book) ? $view->book->title : "N/A",
            ]);
        }

        // Write borrow history
        foreach ($user->borrowHistories as $borrow) {
            fputcsv($handle, [
                $user->id,
                $user->name,
                $user->email,
                "Borrowed",
                "", // No search query for borrowed books
                isset($borrow->book) ? $borrow->book->id : "N/A",
                isset($borrow->book) ? $borrow->book->title : "N/A",
            ]);
        }

        fclose($handle);
    }, 200, $headers);
}


}



