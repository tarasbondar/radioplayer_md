<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_USER = 0;
    const ROLE_AUTHOR = 1;
    const ROLE_ADMIN = 2;

    const STATUS_NORMAL = 0;
    const STATUS_BLOCKED = 1;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status'

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin() {
        return ($this->role == self::ROLE_ADMIN);
    }

    public function isAuthor() {
        return ($this->role == self::ROLE_AUTHOR);
    }

}
