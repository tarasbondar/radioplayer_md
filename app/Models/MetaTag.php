<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
 * @package App\Models
 *
 * @property int $id
 * @property string $route
 * @property bool $is_default
 *
 * @property Translation[] $translations
 */
class MetaTag extends Model
{
    use HasFactory, Translatable;

    protected $table = 'meta_tags';

    protected $fillable = ['route', 'is_default'];

    protected $translatable = [
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];
}
