<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioStation2Tag extends Model
{
    use HasFactory;

    protected $table = 'radiostations_2_tags';

    protected $fillable = ['station_id', 'tag_id', 'created_at'];

    public $timestamps = false;

}
