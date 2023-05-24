<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioStationGroup extends Model
{
    use HasFactory;

    protected $table = 'radiostations_groups';

    protected $fillable = ['key'];

}
