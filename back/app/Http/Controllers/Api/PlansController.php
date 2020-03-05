<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    protected $filters = [
        'equals' => ['date', 'is_finished'],
        'like' => ['comment']
    ];

    public function index(Request $request)
    {
        $query = auth()->user()->plans();

        $this->filter($request, $query);

        if ($request->has('count')) {
            return $query->count();
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $plan = auth()->user()->plans()->create($request->all());
        return $plan;
    }

    public function update(Plan $plan, Request $request)
    {
        $plan->update($request->all());
        return $plan;
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
    }

    public function toggle(Plan $plan)
    {
        if (!$plan->is_finished) {
            auth()->user()->entries()->create([
                'comment' => $plan->comment,
                'desc' => $plan->desc,
                'pts' => $plan->pts,
            ]);
        } else {
            auth()->user()->entries()
                ->where('comment', $plan->comment)
                ->where('desc', $plan->desc)
                ->where('pts', $plan->pts)
                ->whereDate('created_at', $plan->date)
                ->delete();
        }
        $plan->update([
            'is_finished' => !$plan->is_finished
        ]);
    }
}
