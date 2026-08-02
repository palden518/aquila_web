<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Whether this user is allowed into /admin at all.
     */
    public function canAccessAdmin(): bool
    {
        return $this->can('access admin panel');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cateringEnquiries()
    {
        return $this->hasMany(CateringEnquiry::class);
    }
}
