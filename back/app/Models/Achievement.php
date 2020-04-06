<?php

namespace App\Models;

class Achievement extends HasPts
{
    protected $fillable = [
        'name', 'desc', 'pts', 'achieved_at'
    ];
}
