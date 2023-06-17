<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Podcast extends Model
{
    use HasFactory, Translatable;

    protected $table = 'podcasts';

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    const UPLOADS_IMAGES = 'uploads/podcasts_images';

    protected $fillable = [
        'name',
        'description',
        'status',
        'tags'
    ];

    protected $translatable = [
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];


    public function getMetaTitle(){
        $text = $this->getTranslation('meta_title');
        if (empty($text))
            $text = $this->name;
        return strip_tags($text);
    }

    public function getMetaKeywords(){
        $text = $this->getTranslation('meta_keywords');
        if (empty($text))
            $text = $this->tags;
        return strip_tags($text);
    }

    public function getMetaDescription(){
        $text = $this->getTranslation('meta_description');
        if (empty($text))
            $text = $this->description;
        $text = strip_tags($text);

        return Str::limit($text, 160);
    }



}
