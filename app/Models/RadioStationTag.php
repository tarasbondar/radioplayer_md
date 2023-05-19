<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioStationTag extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    protected $table = 'radiostations_tags';

    protected $fillable = ['key', 'status'];

}
