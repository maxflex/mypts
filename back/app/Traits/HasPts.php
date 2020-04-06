<?php

namespace App\Traits;

trait HasPts
{
    protected $appends = ['desc_html'];

    public function getDescHtmlAttribute()
    {
        return str_replace("\n", "<br />", $this->attributes['desc']);
    }
}
