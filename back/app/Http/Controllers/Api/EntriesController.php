<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntryResource;
use App\Models\Entry;
use Illuminate\Http\Request;

class EntriesController extends Controller
{
    protected $filters = [
        'like' => ['comment']
    ];

    public function index(Request $request)
    {
        $query = auth()->user()->entries()->latest();

        $this->filter($request, $query);

        return $this->handleIndexRequest($request, $query, EntryResource::class);
    }

    public function store(Request $request)
    {
        $action = auth()->user()->entries()->create(
            $request->all()
        );
        return $action;
    }
}
