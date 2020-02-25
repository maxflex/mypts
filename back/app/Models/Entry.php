<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = ['comment', 'pts', 'user_id', 'created_at'];
}
