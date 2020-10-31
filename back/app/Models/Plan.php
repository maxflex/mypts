<?php

namespace App\Models;

class Plan extends HasPts
{
    protected $fillable = [
        'comment', 'pts', 'date', 'is_finished', 'desc', 'time'
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
        $this->is_finished = true;
        $this->save();
    }

    public function uncomplete()
    {
        $this->user->entries()
            ->where('comment', $this->comment)
            ->where('desc', $this->desc)
            ->where('pts', $this->pts)
            ->whereDate('created_at', $this->date)
            ->delete();
        $this->is_finished = false;
        $this->save();
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
