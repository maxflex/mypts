<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function get()
    {
        return new ProfileResource(auth()->user());
    }

    public function update(Request $request)
    {
        auth()->user()->update($request->all());
        return new ProfileResource(auth()->user());
    }
}
