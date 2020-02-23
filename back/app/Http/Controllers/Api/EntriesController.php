<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntryResource;
use App\Models\Entry;
use Illuminate\Http\Request;

class EntriesController extends Controller
{
    public function index(Request $request)
    {
        $query = Entry::latest();


        return $this->handleIndexRequest($request, $query, EntryResource::class);
    }

    public function store(Request $request)
    {
        $action = Entry::create($request->all());
        return $action;
    }

    public function autocomplete(Request $request)
    {
        $request->validate([
            'comment' => ['required', 'min:3']
        ]);

        return Entry::latest()
            ->where('comment', 'like', "%{$request->comment}%")
            ->take(10)
            ->get();
    }
}
