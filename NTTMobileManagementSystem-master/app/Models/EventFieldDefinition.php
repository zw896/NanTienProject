<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EventField
 *
 * @package App\Models
 * @property-read \App\Models\Event $event
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EventField[] $field
 * @property int $id
 * @property string $field_name
 * @property string $define
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EventFieldDefinition whereDefine($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EventFieldDefinition whereFieldName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\EventFieldDefinition whereId($value)
 */
class EventFieldDefinition extends Model {
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
    protected $table = 'event_field_definition';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function field() {
        return $this->hasMany('App\Models\EventField', 'field_define', 'id');
    }
}
