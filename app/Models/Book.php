<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Book
 * @package App\Models
 * @mixin Builder
 */
class Book extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';
    protected $primaryKey = 'isbn';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isbn', 'title', 'author', 'publisher', 'publication_date', 'summary', 'img_src'
    ];

    public static function searchBook(string $title)
    {
        return Book::where('title', 'like', "%$title%");
    }

    /**
     * The roles that belong to the user.
     */
    public function users()
    {
        return $this
            ->belongsToMany('App\Models\User', 'user_favorite_book', 'book_id', 'user_id')
            ->withTimestamps();
    }
}
