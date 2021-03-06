<?php namespace Gzero\Entity;

use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Gzero\Entity\Presenter\UserPresenter;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Robbo\Presenter\PresentableInterface;

/**
 * This file is part of the GZERO CMS package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Class User
 *
 * @package    Gzero\Entity
 * @author     Adrian Skierniewski <adrian.skierniewski@gmail.com>
 * @copyright  Copyright (c) 2014, Adrian Skierniewski
 */
class User extends Base implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, PresentableInterface {

    use Authenticatable, Authorizable, CanResetPassword, HasApiTokens;

    /**@TODO proper method for adding new fillable fields from package with migrations */
    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'first_name',
        'has_social_integrations',
        'last_name',
        'nick',
        'password',
        'remember_token'
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'is_admin' => false
    ];

    /**
     * Permission map
     *
     * @var array
     */
    protected $permissionsMap = null;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * The roles that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'acl_user_role')->withTimestamps();
    }

    /**
     * Checks is user have super admin permissions
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return (boolean) $this->is_admin;
    }

    /**
     * Only GuestUser should have it set to true
     *
     * @return boolean
     */
    public function isGuest()
    {
        return false;
    }

    /**
     * It checks if given user have specified permission
     *
     * @param string $permission Permission name
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (!is_array($this->permissionsMap)) {
            $permissionsMap = cache()->get('permissions:' . $this->id, null);
            if ($permissionsMap === null) { // Not in cache
                $this->permissionsMap = $this->buildPermissionsMap();
                cache()->forever('permissions:' . $this->id, $this->permissionsMap);
            } else {
                $this->permissionsMap = $permissionsMap;
            }
        }
        return in_array($permission, $this->permissionsMap);
    }

    /**
     * Return a created presenter.
     *
     * @return \Robbo\Presenter\Presenter
     */
    public function getPresenter()
    {
        return new UserPresenter($this);
    }

    /**
     * It build permission map.
     * Later we store this map cache.
     *
     * @return array
     */
    private function buildPermissionsMap()
    {
        $permissionsMap = [];
        $roles          = $this->roles()->with('permissions')->get()->toArray();
        foreach ($roles as $role) {
            if (!empty($role['permissions'])) {
                foreach ($role['permissions'] as $permission) {
                    $permissionsMap[] = $permission['name'];
                }
            }
        }
        return array_unique($permissionsMap);
    }
}
