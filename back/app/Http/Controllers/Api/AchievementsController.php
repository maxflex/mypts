<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(Request $request)
    {
        return auth()->user()->acheivements()
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $achievement = auth()->user()->acheivements()->create($request->all());
        return $achievement;
    }

    public function achieve(Achievement $achievement)
    {
        $achievement->update([
            'achieved_at' => now()
        ]);

        auth()->user()->entries()->create([
            'comment' => 'Достижение: ' . $achievement->name,
            'desc' => $achievement->desc,
            'pts' => $achievement->pts,
        ]);

        return $achievement;
    }
}
