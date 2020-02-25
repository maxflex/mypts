<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use Carbon\Carbon;

class PtsController extends Controller
{
    public function index()
    {
        return [
            'currentPts' =>  auth()->user()->currentPts,
            'today' =>  auth()->user()->entries()->whereDate('created_at', Carbon::today())->sum('pts'),
            'yesterday' =>  auth()->user()->entries()->whereDate('created_at', Carbon::yesterday())->sum('pts'),
        ];
    }
}
