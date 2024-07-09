<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
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

    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function createToken($name, array $abilities = ['*'])
    {
        return $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', Str::random(40)),
            'abilities' => $abilities,
        ]);
    }
}
