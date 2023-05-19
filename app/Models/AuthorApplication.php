<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorApplication extends Model
{
    use HasFactory;

    const STATUS_DECLINED = 0;
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;

    protected $fillable = ['user_id', 'name', 'description', 'category', 'image', 'example', 'status', 'feedback_message'];

    protected $table = 'author_applications';


}
