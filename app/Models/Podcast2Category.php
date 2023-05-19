<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast2Category extends Model
{
    use HasFactory;

    protected $table = 'podcasts_2_categories';

    protected $fillable = ['podcast_id', 'category_id'];

    public $timestamps = false;

}
