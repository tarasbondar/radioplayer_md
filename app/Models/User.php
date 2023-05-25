<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property $timestamps
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property int $role
 * @property int $status
 * @property string|null $language
 * @property string|null $remember_token
 *
 * @property Playlist[] $playlist
 * @property-read string $role_name
 * @property-read string $status_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_USER = 0;
    const ROLE_AUTHOR = 1;
    const ROLE_ADMIN = 2;

    const STATUS_NORMAL = 0;
    const STATUS_BLOCKED = 1;

    public static array $roles = [
        self::ROLE_USER => 'User',
        self::ROLE_AUTHOR => 'Author',
        self::ROLE_ADMIN => 'Admin'
    ];

    public static array $statuses = [
        self::STATUS_NORMAL => 'Normal',
        self::STATUS_BLOCKED => 'Blocked'
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'language'
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

    public function playlist()
    {
        return $this->hasMany(Playlist::class, 'user_id')->orderBy('sort');
    }

}
