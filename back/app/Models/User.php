<?php

namespace App\Models;

use App\Enums\RecordType;
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

    public function maxRecord()
    {
        return $this->hasOne(Record::class)->where('type', RecordType::max);
    }

    public function minRecord()
    {
        return $this->hasOne(Record::class)->where('type', RecordType::min);
    }

    public function getCurrentPtsAttribute()
    {
        $base = 1200;
        return $base + $this->entries()->sum('pts');
    }

    public function updateRecords()
    {
        // seeder prevent
        if ($this->minRecord === null) {
            return;
        }

        $currentPts = $this->currentPts;

        if ($currentPts < $this->minRecord->pts) {
            $this->minRecord()->update([
                'pts' => $currentPts,
                'updated_at' => now()
            ]);
        }

        if ($currentPts > $this->maxRecord->pts) {
            $this->maxRecord()->update([
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
