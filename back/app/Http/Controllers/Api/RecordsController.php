<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function index()
    {
        return [
            'min' => auth()->user()->minRecord,
            'max' => auth()->user()->maxRecord,
        ];
    }
}
