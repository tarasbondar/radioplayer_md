<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastSubHistoryRecord extends Model
{
    use HasFactory;

    protected $table = 'podcasts_subscriptions_history';

    public $timestamps = false;

    protected $fillable = ['user_id', 'podcast_id', 'action', 'created_at'];

    const ACTION_UNSUB = 0;
    const ACTION_SUB = 1;

}
