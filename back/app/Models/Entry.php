<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = ['comment', 'pts', 'user_id', 'created_at', 'desc'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(fn ($entry) => $entry->user->updateRecord());
        static::creating(fn ($entry) => $entry->new_pts = $entry->pts + $entry->user->current_pts);
        static::deleted(fn ($entry) => $entry->user->updateRecord());
    }
}
