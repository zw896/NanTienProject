<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 *
 * @package App\Models
 * @property-read \App\Models\Event $event
 * @property-read \App\Roles\User $user
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attachment[] $attachment
 * @property int $id
 * @property int $eid
 * @property int $uid
 * @property string $content
 * @property bool $rating
 * @property bool $display
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereDisplay($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereEid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereUid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attachment[] $attachments
 */
class Comment extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['eid', 'uid', 'updated_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event() {
        return $this->belongsTo('App\Models\Event', 'eid', 'id')->select(['id', 'title']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Roles\User', 'uid', 'id')->select(['id', 'username']);
    }

    public function attachments() {
        return $this->hasMany('App\Models\Attachment', 'cid', 'id');
    }
}
