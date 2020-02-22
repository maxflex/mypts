<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use Illuminate\Http\Request;

class EntriesController extends Controller
{
    public function index(Request $request)
    {
        $query = Entry::latest();

        if ($request->has('comment')) {
            $query->where('comment', 'like', "%{$request->comment}%");
        }

        return $query->paginate(30);
    }

    public function store(Request $request)
    {
        $action = Entry::create($request->all());
        return $action;
    }
}
