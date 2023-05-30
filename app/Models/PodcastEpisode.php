<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PodcastEpisode
 * @package App\Models
 *
 * @property int $id
 * @property int $podcast_id
 * @property string $name
 * @property string $description
 * @property string $tags
 * @property string $source
 * @property string $filename
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Podcast $podcast
 */

class PodcastEpisode extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_BLOCKED = 0;

    const UPLOADS_AUDIO = 'uploads/podcasts_episodes';

    protected $table = 'podcasts_episodes';

    protected $fillable = ['podcast_id', 'name', 'description', 'tags', 'source', 'filename', 'status'];

    protected $appends = ['created_diff', 'is_in_playlist', 'source_path', 'source_url', 'is_in_listen_later',
        'is_in_history', 'is_downloaded', 'start_time'];

    public function podcast() {
        return $this->belongsTo(Podcast::class, 'podcast_id', 'id');
    }

    public function getSourceUrlAttribute(): string
    {
        return asset(self::UPLOADS_AUDIO . '/' . $this->source);
    }

    public function getSourcePathAttribute(): string
    {
        return public_path(self::UPLOADS_AUDIO . '/' . $this->source);
    }

    public function getCreatedDiffAttribute()
    {
        return $this->created_at->format('d F');
    }

    public function getIsInPlaylistAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        return Playlist::where('user_id', auth()->id())->where('episode_id', $this->id)->exists();
    }

    public function getIsInListenLaterAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        return QueuedEpisode::where('user_id', auth()->id())->where('episode_id', $this->id)->exists();
    }

    public function getIsInHistoryAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        return HistoryRecord::where('user_id', auth()->id())->where('episode_id', $this->id)->exists();
    }

    public function getIsDownloadedAttribute(): bool {
        if (!auth()->check()) {
            return false;
        }
        return DownloadRecord::where('user_id', auth()->id())->where('episode_id', $this->id)->exists();
    }

    public function getStartTimeAttribute(): int
    {
        if (!auth()->check()) {
            return 0;
        }
        $historyRecord = HistoryRecord::where('user_id', auth()->id())->where('episode_id', $this->id)->first();
        if (!$historyRecord) {
            return 0;
        }
        return $historyRecord->time;
    }

}
