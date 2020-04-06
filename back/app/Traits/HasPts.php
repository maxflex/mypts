<?php

namespace App\Traits;

trait HasPts
{
    public function getDescHtmlAttribute()
    {
        return str_replace("\n", "<br />", $this->attributes['desc']);
    }
}
