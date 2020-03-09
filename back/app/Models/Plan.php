<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'comment', 'pts', 'date', 'is_finished', 'desc'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnfinished($query)
    {
        return $query->where('is_finished', false);
    }

    public function complete()
    {
        $this->user->entries()->create([
            'pts' => $this->pts,
            'comment' => $this->comment,
            'desc' => $this->desc,
            'created_at' => now() > $this->date ? $this->date->endOfDay() : now()
        ]);
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
