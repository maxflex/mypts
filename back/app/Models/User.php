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

    public function rules()
    {
        return $this->hasMany(Rule::class);
    }

    public function record()
    {
        return $this->hasOne(Record::class);
    }

    public function acheivements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function getCurrentPtsAttribute()
    {
        return config('pts.base') + $this->entries()->sum('pts');
    }

    public function updateRecord()
    {
        $currentPts = $this->currentPts;

        if ($currentPts > $this->record->pts) {
            $this->record()->update([
                'pts' => $currentPts,
                'updated_at' => now()
            ]);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($user) => $user->api_token = Str::random(80));
    }
}
