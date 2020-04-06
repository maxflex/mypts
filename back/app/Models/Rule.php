<?php

namespace App\Models;

use App\Traits\HasPts;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasPts;

    public $timestamps = false;
    protected $fillable = [
        'comment', 'pts', 'desc', 'is_food'
    ];
}
