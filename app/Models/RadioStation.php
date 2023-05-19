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

    protected $fillable = ['name', 'description', 'source', 'source_hd', 'status', 'image'];

    public function categories() {
        $this->belongsToMany(RadioStationCategory::class, 'radiostations_2_categories');
    }
}
