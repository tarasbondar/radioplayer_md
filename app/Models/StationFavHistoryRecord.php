<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationFavHistoryRecord extends Model
{
    use HasFactory;

    protected $table = 'radiostations_favorites_history';

    public $timestamps = false;

    protected $fillable = ['user_id', 'station_id', 'action', 'created_at'];

    const ACTION_UNFAV = 0;
    const ACTION_FAV = 1;

}
