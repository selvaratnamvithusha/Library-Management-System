<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Support\Facades\Auth; 

class AdminController extends Controller
{
    public function index()
    {
       if(Auth::id())
       {
        $user_type=Auth::user()->usertype;

        if($user_type=='1')
        {
           $user=User::all()->count();
           $book=Book::all()->count();
           $borrow=Borrow::where('status', 'approved')->count();
           $returned=Borrow::where('status', 'returned')->count();
           return view('admin.index', compact('user','book','borrow','returned'));
        }

        else if($user_type=='0')
        {
            $data=Book::all();
            return view('home.index',compact('data'));
        }

        else
        {
            return redirect()->back();
        }
       }
    }



 
    public function borrow_request()
    {
        $book=Borrow::all();
        return view('admin.borrow_request', compact('book'));
    }

    public function approve_book($id)
    {
        $data=Borrow::find($id);
        $status=$data->status;

        if($status == 'approved')
        {
          return redirect()->back();
        }
        else
        {
            $data->status='approved';
            $data->save();
    
    
            $bookid=$data->book_id;
            $book=Book::find($bookid);
            $book_qty=$book->quantity -'1';
            $book->quantity=$book_qty;
            $book->save();
            return redirect()->back();
        }

    }

    public function return_book($id)
    {
        $data=Borrow::find($id);
        $status=$data->status;

        if($status == 'returned')
        {
          return redirect()->back();
        }
        else
        {
            $data->status='returned';
            $data->save();
    
    
            $bookid=$data->book_id;
            $book=Book::find($bookid);
            $book_qty=$book->quantity +'1';
            $book->quantity=$book_qty;
            $book->save();

            return redirect()->back();
        }

    }

    public function rejected_book($id)
    {
        $data=Borrow::find($id);
        $data->status="rejected";
        $data->save();
        return redirect()->back();
    }


}
