<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $img_path
 * @property int $user_id
 * @property int $percent
 *
 * @property User $user
 * @package App\Models
 */
class Queue extends Model
{
    use HasFactory;

    protected $table = 'queue';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
