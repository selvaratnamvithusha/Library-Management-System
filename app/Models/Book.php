<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
        

        protected $fillable = [
            'title', 
            'author_name', 
            'quantity', 
            'description', 
            'category_id', 
            'author_img', 
            'book_img',
        ];


        public function category()
        {
            return $this->belongsTo(Category::class);
        }
}
