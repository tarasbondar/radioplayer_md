<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioStation extends Model
{
    use HasFactory;

    protected $table = 'radiostations';

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    const UPLOADS_IMAGES = 'uploads/stations_images';

    protected $fillable = ['name', 'description', 'group_id', 'source', 'source_hd', 'source_meta', 'api_id', 'status', 'image_logo', 'order'];

    public function categories() {
        $this->belongsToMany(RadioStationCategory::class, 'radiostations_2_categories');
    }

}
