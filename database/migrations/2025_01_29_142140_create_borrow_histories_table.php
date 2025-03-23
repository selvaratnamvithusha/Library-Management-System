<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borrow_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->enum('status', ['Applied', 'approved', 'rejected', 'returned'])->default('Applied'); 
            $table->timestamp('borrowed_at')->nullable(); // When the book was borrowed
            $table->timestamp('returned_at')->nullable(); // When the book was returned
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
           $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_histories');
    }
};
