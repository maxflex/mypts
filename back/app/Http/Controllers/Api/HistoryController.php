<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllDataResource;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'mode' => 'required'
        ]);

        return $this->{Str::camel($request->mode)}($request);
    }

    private function all(Request $request)
    {
        return AllDataResource::collection(
            auth()->user()->entries()->latest()->paginate(30)
        );
    }

    private function byDay(Request $request)
    {
        return AllDataResource::collection(
            auth()->user()->entries()
                ->selectRaw("
                    DATE(`created_at`) as `date`,
                    SUM(IF(pts < 0, pts, 0)) as pts_minus_total,
                    SUM(IF(pts >= 0, pts, 0)) as pts_plus_total,
                    SUM(pts) as pts_total
                ")
                ->groupByRaw('DATE(created_at)')
                ->orderByRaw('DATE(created_at) DESC')
                ->paginate(30)
        );
    }
}
