<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastEpisode extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_BLOCKED = 0;

    protected $table = 'podcasts_episodes';

    protected $fillable = ['podcast_id', 'name', 'description', 'tags', 'source', 'status'];

}
