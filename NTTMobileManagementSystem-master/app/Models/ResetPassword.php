<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 *
 * @package App\Models
 * @property-read \App\Roles\User $user
 * @mixin \Eloquent
 * @property int $id
 * @property int $uid
 * @property string $hash
 * @property string $expire
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ResetPassword whereExpire($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ResetPassword whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ResetPassword whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ResetPassword whereUid($value)
 */
class ResetPassword extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_password_reset';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Roles\User', 'uid', 'id');
    }
}
