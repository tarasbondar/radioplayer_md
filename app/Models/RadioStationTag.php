<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioStationTag extends Model
{
    use HasFactory, Translatable;

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    protected $table = 'radiostations_tags';

    protected $fillable = ['key', 'status'];
    protected $translatable = ['title'];

}
