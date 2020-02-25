<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'comment', 'pts', 'date', 'is_finished'
    ];

    public function scopeUnfinished($query)
    {
        return $query->where('is_finished', false);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(
            'default-order',
            fn ($query) => $query->orderBy('is_finished', 'asc')->latest()
        );
    }
}
