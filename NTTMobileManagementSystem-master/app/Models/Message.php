<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 *
 * @package App\Models
 * @mixin \Eloquent
 * @property int $id
 * @property string $type
 * @property string $title
 * @property string $content
 * @property string $url
 * @property bool $viewed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message whereViewed($value)
 */
class Message extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';

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
}
