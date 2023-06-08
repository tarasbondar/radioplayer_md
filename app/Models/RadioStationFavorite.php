<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiostationFavorite extends Model
{
    use HasFactory;

    protected $table = 'radiostations_favorites';

    protected $fillable = ['user_id', 'station_id', 'created_at'];
}
