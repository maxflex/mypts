<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'comment', 'pts', 'desc'
    ];
}
