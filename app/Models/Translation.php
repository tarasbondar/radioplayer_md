<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $table = 'translations';

    protected $fillable = [
        'lang',
        'object_id',
        'object_type',
        'title',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang', 'code');
    }

    public function translatable()
    {
        return $this->morphTo('object');
    }
}

