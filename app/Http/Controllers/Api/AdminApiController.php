<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Support\Facades\Auth; 

class AdminApiController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::id()) {
            $user_type = Auth::user()->usertype;

            if ($user_type == '1') {
                $user = User::all()->count();
                $book = Book::all()->count();
                $borrow = Borrow::where('status', 'approved')->count();
                $returned = Borrow::where('status', 'returned')->count();
                return response()->json([
                    'user_count' => $user,
                    'book_count' => $book,
                    'borrow_count' => $borrow,
                    'returned_count' => $returned
                ]);
            } else if ($user_type == '0') {
                $data = Book::all();
                return response()->json($data);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
    }

    public function borrowRequest(Request $request)
    {
        $book = Borrow::all();
        return response()->json($book);
    }

    public function approveBook(Request $request,$id)
    {
        $data = Borrow::find($id);
        $status = $data->status;

        if ($status == 'approved') {
            return response()->json(['message' => 'Book already approved']);
        } else {
            $data->status = 'approved';
            $data->save();

            $bookid = $data->book_id;
            $book = Book::find($bookid);
            $book_qty = $book->quantity - '1';
            $book->quantity = $book_qty;
            $book->save();
            return response()->json(['message' => 'Book approved successfully']);
        }
    }

    public function returnBook( $id)
    {
        $data = Borrow::find($id);
        $status = $data->status;

        if ($status == 'returned') {
            return response()->json(['message' => 'Book already returned']);
        } else {
            $data->status = 'returned';
            $data->save();

            $bookid = $data->book_id;
            $book = Book::find($bookid);
            $book_qty = $book->quantity + '1';
            $book->quantity = $book_qty;
            $book->save();

            return response()->json(['message' => 'Book returned successfully']);
        }
    }

    public function rejectBook($id)
    {
        $data = Borrow::find($id);
        $data->status = "rejected";
        $data->save();
        return response()->json(['message' => 'Book rejected successfully']);
    }
}
