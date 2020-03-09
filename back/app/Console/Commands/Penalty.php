<?php

namespace App\Console\Commands;

use App\Models\Entry;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Penalty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'penalty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Penalty PTS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->unfinishedPlansPanalty();
        $this->noPlansPenalty();
    }

    /**
     * Штраф за отсутсвие планов на день
     * 5 * (который день подряд нет планов)
     */
    public function noPlansPenalty()
    {
        foreach (User::all() as $user) {
            if (
                !$user
                    ->plans()
                    ->whereDate('date', Carbon::yesterday())
                    ->exists()
            ) {
                $latestPlanDate = $user->plans()->where('date', '<', Carbon::yesterday())->value('date');
                $daysWithoutPlanInRow = Carbon::yesterday()->diffInDays($latestPlanDate);
                $user->entries()->create([
                    'pts' => $daysWithoutPlanInRow * config('pts.no-plans-coeff'),
                    'comment' => 'Отсутствие планов на день',
                    'desc' => $daysWithoutPlanInRow > 1 ? " {$daysWithoutPlanInRow} день подряд" : null,
                    'created_at' => Carbon::yesterday()->endOfDay(),
                ]);
            }
        }
    }

    /**
     * Штраф за невыполнение запланированного плана
     * pts * 2
     */
    private function unfinishedPlansPanalty()
    {
        Plan::query()
            ->whereDate('date', Carbon::yesterday())
            ->unfinished()
            ->get()
            ->each(fn ($plan) => Entry::create([
                'pts' => $plan->pts * (int) config('pts.unfinished-plans-coeff'),
                'comment' => $plan->comment,
                'desc' => 'Невыполнение плана на день',
                'user_id' => $plan->user_id,
                'created_at' => Carbon::yesterday()->endOfDay()
            ]));
    }
}
