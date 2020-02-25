<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email',  'password',
    ];

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($user) => $user->api_token = Str::random(80));
    }
}
