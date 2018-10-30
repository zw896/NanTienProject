<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Option
 *
 * @package App\Models
 * @mixin \Eloquent
 * @property int $option_id
 * @property string $option_name
 * @property string $option_value
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Option whereOptionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Option whereOptionName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Option whereOptionValue($value)
 */
class Option extends Model {
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
    protected $table = 'options';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['option_name', 'option_value'];

    protected $primaryKey = 'option_id';
}