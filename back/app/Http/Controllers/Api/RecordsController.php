<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class RecordsController extends Controller
{
    public function index()
    {
        return auth()->user()->record;
    }
}
