<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Feedback
 *
 * @package App\Models
 * @property-read \App\Roles\User $user
 * @mixin \Eloquent
 * @property int $id
 * @property int $uid
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Feedback whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Feedback whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Feedback whereUid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Feedback whereUpdatedAt($value)
 */
class Feedback extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedback';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['uid'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Roles\User', 'uid', 'id')->select(['id', 'username']);;
    }
}
