<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    use HasFactory;

    protected $table = 'podcasts';

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    protected $fillable = [
        'name',
        'description',
        'status',
        'tags'
    ];



}
