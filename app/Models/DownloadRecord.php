<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadRecord extends Model
{
    use HasFactory;

    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;

    protected $table = 'users_downloads';

    protected $fillable = ['user_id', 'episode_id', 'deleted'];

    public $timestamps = false;

}
