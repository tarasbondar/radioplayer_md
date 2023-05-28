<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryRecord extends Model
{
    use HasFactory;

    protected $table = 'users_history';

    protected $fillable = ['user_id', 'episode_id'];

    public function episode()
    {
        return $this->belongsTo(PodcastEpisode::class, 'episode_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
