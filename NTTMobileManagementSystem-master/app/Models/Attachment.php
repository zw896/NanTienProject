<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

/**
 * Class Attachment
 *
 * @package App\Models
 * @property-read \App\Models\Comment $comment
 * @mixin \Eloquent
 * @property int $id
 * @property int $cid
 * @property bool $type
 * @property string $filename
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attachment whereCid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attachment whereFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attachment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attachment whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attachment whereUpdatedAt($value)
 * @property-read bool $url
 * @property int $size
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attachment whereSize($value)
 */
class Attachment extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comment_attachment';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['cid', 'uid', 'updated_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Make it available in the json response
     *
     * @var array
     */
    protected $appends = ['url'];

    /**
     * implement the attribute
     *
     * @return bool
     */
    public function getUrlAttribute() {
        return Storage::url((($this->type) == 0 ? 'public/attachments/images/' : 'public/attachments/videos/') . $this->filename);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment() {
        return $this->belongsTo('App\Models\Comment', 'cid', 'id')->select(['id', 'uid', 'eid']);
    }
}
