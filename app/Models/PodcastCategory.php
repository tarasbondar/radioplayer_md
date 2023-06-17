<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastCategory extends Model
{
    use HasFactory, Translatable;

    protected $table = 'podcasts_categories';

    protected $fillable = ['key', 'status'];
    protected $translatable = ['title'];

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

}
