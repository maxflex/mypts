<?php

namespace App\Models;

class Rule extends HasPts
{
    public $timestamps = false;

    protected $fillable = [
        'comment', 'pts', 'desc', 'is_food'
    ];
}
