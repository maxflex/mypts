<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasPts extends Model
{
    protected $appends = ['desc_html'];

    public function getDescHtmlAttribute()
    {
        return str_replace("\n", "<br />", $this->attributes['desc']);
    }
}
