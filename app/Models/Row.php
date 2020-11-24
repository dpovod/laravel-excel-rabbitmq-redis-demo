<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Row
 * @package App\Models
 *
 * @property string $uuid
 * @property int $id
 * @property string $name
 * @property \DateTime $date
 */
class Row extends Model
{
    /** @var string */
    protected $table = 'rows';

    /** @var string */
    protected $connection = 'main_service';

    /** @var string[] */
    protected $fillable = [
        'id',
        'name',
        'date',
    ];

    /** @var string[] */
    protected $casts = [
        'date' => 'datetime',
    ];
}
