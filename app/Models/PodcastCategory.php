<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastCategory extends Model
{
    use HasFactory;

    protected $table = 'podcasts_categories';

    protected $fillable = ['key', 'status'];

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

}
