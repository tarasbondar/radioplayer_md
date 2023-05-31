<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HistoryRecord
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $episode_id
 * @property int $time
 * @property bool $is_listened
 * @property PodcastEpisode $episode
 * @property User $user
 */
class HistoryRecord extends Model
{
    use HasFactory;

    protected $table = 'users_history';

    protected $fillable = ['user_id', 'episode_id', 'time', 'is_listened'];

    public function episode()
    {
        return $this->belongsTo(PodcastEpisode::class, 'episode_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
