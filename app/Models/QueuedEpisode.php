<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueuedEpisode extends Model
{
    use HasFactory;

    protected $table = 'listen_later';

    protected $fillable = ['user_id', 'episode_id'];

    public $timestamps = false;
}
