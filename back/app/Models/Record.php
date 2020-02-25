<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public $timestamps = false;

    protected $fillable = ['pts', 'updated_at'];
}
