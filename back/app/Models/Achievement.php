<?php

namespace App\Models;

use App\Traits\HasPts;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasPts;

    protected $fillable = [
        'name', 'desc', 'pts', 'achieved_at'
    ];
}
