<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    public function index()
    {
        return auth()->user()->rules()->get();
    }


    public function store(Request $request)
    {
        $rule = auth()->user()->rules()->create($request->all());
        return $rule;
    }

    public function update(Rule $rule, Request $request)
    {
        $rule->update($request->all());
        return $rule;
    }

    public function apply(Rule $rule)
    {
        auth()->user()->entries()->create([
            'desc' => $rule->desc . ' (правило)',
            'pts' => $rule->pts,
            'comment' => $rule->comment,
        ]);
    }
}
