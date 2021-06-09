<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    protected $filters = [
        'equals' => ['date', 'is_finished'],
        'interval' => ['interval'],
        'like' => ['comment']
    ];

    protected $mapFilters = ['interval' => 'date'];

    public function index(Request $request)
    {
        $query = auth()->user()->plans();

        $this->filter($request, $query);

        if ($request->has('count')) {
            return $query->count();
        }

        return $query
            ->orderBy('date', 'desc')
            ->orderByRaw('IF (time is null, 0, 1) DESC')
            ->orderBy('time', 'asc')
            ->orderBy('pts', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'comment' => ['required'],
            'pts' => ['required']
        ]);
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
        if ($plan->is_finished) {
            $plan->uncomplete();
        } else {
            $plan->complete();
        }
    }

    public function events()
    {
        return auth()->user()->plans()->unfinished()->whereDate('date', '>', now())->pluck('date');
    }
}
