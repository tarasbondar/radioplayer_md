<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastStatRecord extends Model
{
    use HasFactory;

    protected $table = 'podcasts_history';

    public $timestamps = false;

    protected $fillable = ['podcast_id', 'user_id', 'ip', 'click_time'];

}
