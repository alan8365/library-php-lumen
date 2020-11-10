<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFavoriteBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_favorite_book', function (Blueprint $table) {
            $table->string('user_id');
            $table->foreign('user_id')->references('email')->on('users')->onDelete('cascade');
            $table->string('book_id');
            $table->foreign('book_id')->references('isbn')->on('books')->onDelete('cascade');
            $table->timestamps();
            $table->primary(array('user_id', 'book_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_favorite_book');
    }
}
