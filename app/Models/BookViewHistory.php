<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookViewHistory extends Model
{
    protected $fillable = ['user_id', 'book_id', 'viewed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
