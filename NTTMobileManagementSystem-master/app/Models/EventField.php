<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EventField
 *
 * @package App\Models
 * @property-read \App\Models\Event $event
 * @mixin \Eloquent
 * @property-read \App\Models\EventFieldDefinition $definition
 * @property-read mixed $field_type
 * @property int $id
 * @property int $eid
 * @property string $field_value
 * @property int $field_define
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EventField whereEid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EventField whereFieldDefine($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EventField whereFieldValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EventField whereId($value)
 */
class EventField extends Model {
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_field';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'eid', 'definition', 'field_define'];

    /**
     * Make it available in the json response
     *
     * @var array
     */
    protected $appends = ['field_type'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return mixed
     */
    public function getFieldTypeAttribute() {
        return $this->definition->define;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event() {
        return $this->belongsTo('App\Models\Event', 'eid', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function definition() {
        return $this->belongsTo('App\Models\EventFieldDefinition', 'field_define', 'id');
    }
}
