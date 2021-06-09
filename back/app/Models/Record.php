<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public $timestamps = false;

    protected $fillable = ['pts', 'updated_at'];
    protected $appends = ['is_new'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Сейчас новый рекорд?
     */
    public function getIsNewAttribute()
    {
        // Надпись "новый рекорд" актуальна в течение N минут
        if (Carbon::parse($this->updated_at) > now()->subMinutes(10)) {
            if ($this->user->currentPts === $this->pts) {
                return true;
            }
        }
        return false;
    }
}
