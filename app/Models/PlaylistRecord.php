<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlaylistRecord
 *
 * @property int $id
 * @property int $user_id
 * @property int $episode_id
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PodcastEpisode|null $episode
 * @property-read \App\Models\User|null $user
 */
class PlaylistRecord extends Model
{
    use HasFactory;

    protected $table = 'playlists';

    protected $fillable = [
        'user_id',
        'episode_id',
        'sort',
        // Add other fillable columns here
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function episode()
    {
        return $this->belongsTo(PodcastEpisode::class);
    }
}
