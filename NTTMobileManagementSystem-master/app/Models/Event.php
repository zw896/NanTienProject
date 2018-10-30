<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Event
 *
 * @package App\Models
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EventField[] $fields
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 * @property int $id
 * @property int $eid
 * @property string $type
 * @property string $title
 * @property string $body
 * @property string $author
 * @property int $view
 * @property bool $sticky
 * @property int $priority
 * @property bool $featured
 * @property bool $pushed
 * @property bool $published
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereAuthor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereEid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereFeatured($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event wherePriority($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event wherePublished($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event wherePushed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereSticky($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Event whereView($value)
 */
class Event extends Model {
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['eid', 'pushed'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at'];

    /**
     * Get the field record associated with the event.
     */
    public function fields() {
        return $this->hasMany('App\Models\EventField', 'eid', 'id');
    }

    /**
     * Get the comment record associated with the event.
     */
    public function comments() {
        return $this->hasMany('App\Models\Comment', 'eid', 'id');
    }
}
