<?php

namespace App\Roles;

use Cog\Ban\Contracts\HasBans as HasBansContract;
use Cog\Ban\Traits\HasBans;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


/**
 * Class Admin
 *
 * @package App\Roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cog\Ban\Models\Ban[] $bans
 * @property-read bool $banned
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property string $remember_token
 * @property string $last_login
 * @property string $banned_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\Admin whereBannedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\Admin whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\Admin whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\Admin whereLastLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\Admin whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\Admin wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\Admin whereUpdatedAt($value)
 */
class Admin extends Authenticatable implements HasBansContract {
    use Notifiable;
    use HasBans;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Make it available in the json response
     *
     * @var array
     */
    protected $appends = ['banned'];

    /**
     * implement the attribute
     *
     * @return bool
     */
    public function getBannedAttribute() {
        return $this->isBanned();
    }
}
