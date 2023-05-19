<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioStationCategory extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    protected $table = 'radiostations_categories';

    protected $fillable = ['key', 'status'];

    public function categories() {
        $this->belongsToMany(RadioStation::class, 'radiostations_2_categories');
    }

}
