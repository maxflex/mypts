<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use Carbon\Carbon;

class PtsController extends Controller
{
    public function index()
    {
        $base = 1200;
        return [
            'total' =>  $base + Entry::sum('pts'),
            'today' =>  Entry::whereDate('created_at', Carbon::today())->sum('pts'),
            'yesterday' =>  Entry::whereDate('created_at', Carbon::yesterday())->sum('pts'),
        ];
    }
}
