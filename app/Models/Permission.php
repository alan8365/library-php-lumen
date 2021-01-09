<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 * @mixin Builder
 */
class Permission extends Model
{
    public $table = 'permissions';
    protected $keyType = 'string';
    protected $primaryKey = 'slug';

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this
            ->belongsToMany('App\Models\Role', 'role_permission', 'permission_id', 'role_id')
            ->withTimestamps();
    }
}
