<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioStationStatRecord extends Model
{
    use HasFactory;

    protected $table = 'radiostations_history';

    public $timestamps = false;

    protected $fillable = ['station_id', 'user_id', 'ip', 'click_time'];

}
