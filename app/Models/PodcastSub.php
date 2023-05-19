<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastSub extends Model
{
    use HasFactory;

    protected $table = 'podcasts_subscriptions';

    protected $fillable = ['user_id', 'podcast_id'];

    public $timestamps = false;

}
