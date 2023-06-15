<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
 * @package App\Models
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $sort
 * @property bool $is_main
 * @property bool $published
 *
 * @property Translation[] $translations
 */
class Language extends Model
{
    const STATUS_PUBLISHED = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_ALL = -1;


    protected $table = 'languages';

    protected $fillable = ['code', 'name', 'sort', 'is_main', 'published'];

    public function translations()
    {
        return $this->hasMany(Translation::class, 'lang', 'code');
    }
}
