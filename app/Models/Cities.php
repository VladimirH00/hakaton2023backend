<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property string $name
 * @property string $code
 *
 * @package App\Models
 */
class Cities extends Model
{
    use HasFactory;
    public $table = 'cities';
    public $timestamps = false;
}
