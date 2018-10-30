<?php

namespace App\Roles;

use Cog\Ban\Contracts\HasBans as HasBansContract;
use Cog\Ban\Traits\HasBans;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;

/**
 * Class User
 *
 * @package App\Roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cog\Ban\Models\Ban[] $bans
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feedback[] $feedback
 * @property-read bool $banned
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\ResetPassword $resetPassword
 * @mixin \Eloquent
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $register_ip
 * @property string $login_ip
 * @property bool $gender
 * @property bool $facebook
 * @property string $banned_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User whereBannedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User whereFacebook($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User whereLoginIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User whereRegisterIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Roles\User whereUsername($value)
 */
class User extends Authenticatable implements AuthenticatableUserContract, HasBansContract {
    use Notifiable;
    use HasBans;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * Protected value
     *
     * @var array
     */
    protected $guarded = ['id'];

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

    /**
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();  // Eloquent model method
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims() {
        return [
            'isBanned' => $this->isBanned(),
            'app' => 'NanTien',
            'username' => $this->username,
        ];
    }

    /**
     * Get the comment record associated with the event.
     */
    public function comments() {
        return $this->hasMany('App\Models\Comment', 'uid', 'id');
    }

    /**
     * Get the feedback record associated with the event.
     */
    public function feedback() {
        return $this->hasMany('App\Models\Feedback', 'uid', 'id');
    }

    /**
     * Get the feedback record associated with the event.
     */
    public function resetPassword() {
        return $this->hasOne('App\Models\ResetPassword', 'uid', 'id');
    }
}
