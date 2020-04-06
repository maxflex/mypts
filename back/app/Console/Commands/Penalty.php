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
        $this->noActivityPenalty();
    }

    /**
     * Штраф за неиспользование приложения
     * 5 * (который день подряд не используется)
     */
    public function noActivityPenalty()
    {
        foreach (User::all() as $user) {
            if (
                !$user
                    ->entries()
                    ->whereDate('created_at', Carbon::yesterday())
                    ->exists()
            ) {
                $latestEntry = $user
                    ->entries()
                    ->where('pts', '>=', 0)
                    ->whereDate('created_at', '<', Carbon::yesterday())
                    ->latest()
                    ->first();
                $daysWithoutPlanInRow = Carbon::yesterday()->diffInDays($latestEntry->created_at);
                $user->entries()->create([
                    'pts' => $daysWithoutPlanInRow * config('pts.no-activity-penalty'),
                    'comment' => 'Отсутствие активности за день',
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
