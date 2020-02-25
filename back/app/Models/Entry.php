<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = ['comment', 'pts', 'user_id', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(fn ($entry) => $entry->user->updateRecords());
        static::deleted(fn ($entry) => $entry->user->updateRecords());
    }
}
