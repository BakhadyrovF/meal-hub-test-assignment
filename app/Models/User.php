<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'santa_id'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function cart(): HasMany
    {
        return $this->hasMany(UserCart::class, 'user_id', 'id');
    }

    public function santa(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'santa_id');
    }

    public function receiver(): HasOne
    {
        return $this->hasOne(User::class, 'santa_id', 'id');
    }

}
