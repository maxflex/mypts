<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    protected $filters = [
        'equals' => ['date'],
        'like' => ['comment']
    ];

    public function index(Request $request)
    {
        $query = auth()->user()->plans();

        $this->filter($request, $query);

        return $query->get();
    }

    public function store(Request $request)
    {
        $plan = auth()->user()->plans()->create($request->all());
        return $plan;
    }

    public function update(Plan $plan)
    {
        if (!$plan->is_finished) {
            auth()->user()->entries()->create([
                'comment' => $plan->comment,
                'pts' => $plan->pts,
            ]);
        } else {
            auth()->user()->entries()
                ->where('comment', $plan->comment)
                ->where('pts', $plan->pts)
                ->whereDate('created_at', $plan->date)
                ->delete();
        }
        $plan->update([
            'is_finished' => !$plan->is_finished
        ]);
    }
}
