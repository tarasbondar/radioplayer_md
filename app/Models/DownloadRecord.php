<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadRecord extends Model
{
    use HasFactory;

    protected $table = 'users_downloads';

    protected $fillable = ['user_id', 'episode_id'];

    public $timestamps = false;

}
