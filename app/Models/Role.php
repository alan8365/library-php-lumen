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
class Role extends Model
{
    public $table = 'roles';
    protected $keyType = 'string';
    protected $primaryKey = 'slug';

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission)
    {
        return $this->permissions()->where('slug', $permission)->count() !== 0;
    }

    /**
     * The roles that belong to the user.
     */
    public function users()
    {
        return $this
            ->belongsToMany('App\Models\User', 'user_role', 'role_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * The roles that belong to the user.
     */
    public function permissions()
    {
        return $this
            ->belongsToMany('App\Models\Permission', 'role_permission', 'role_id', 'permission_id')
            ->withTimestamps();
    }
}
