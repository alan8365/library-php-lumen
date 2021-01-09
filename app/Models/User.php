<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 * @mixin Builder
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, SoftDeletes;

    public $incrementing = false;

    public $table = 'users';
    protected $keyType = 'string';
    protected $primaryKey = 'email';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public static function create(array $attributes = [])
    {
        $attributes['password'] = app('hash')->make($attributes['password']);

        $model = static::query()->create($attributes);

        $model->roles()->attach('reader');

        return $model;
    }

    /**
     * @inheritDoc
     * Get JWT identifier
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     * Get custom JWT claim
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @param $permission string
     * @return boolean
     */
    public function checkPermission(string $permission)
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The roles that belong to the user.
     * @return BelongsToMany
     */
    public function books()
    {
        return $this
            ->belongsToMany('App\Models\Book', 'user_favorite_book', 'user_id', 'book_id')
            ->withTimestamps();
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this
            ->belongsToMany('App\Models\Role', 'user_role', 'user_id', 'role_id')
            ->withTimestamps();
    }
}
