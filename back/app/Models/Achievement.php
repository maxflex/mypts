<?php

namespace App\Models;

class Achievement extends HasPts
{
    protected $fillable = [
        'name', 'desc', 'pts', 'achieved_at'
    ];
    protected $appends = ['is_achieved', 'desc_html'];

    public function getIsAchievedAttribute()
    {
        return $this->achieved_at !== null;
    }
}
