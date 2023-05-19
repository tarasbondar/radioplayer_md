<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioStation2Category extends Model
{
    use HasFactory;

    protected $table = 'radiostations_2_categories';

    public $timestamps = false;

    protected $fillable = ['station_id', 'category_id', 'created_at'];

}
