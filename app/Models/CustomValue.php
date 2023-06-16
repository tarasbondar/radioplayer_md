<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomValue extends Model
{
    use HasFactory, Translatable;

    protected $table = 'custom_values';

    protected $fillable = ['key', 'value'];
    protected $translatable = ['description'];

}
