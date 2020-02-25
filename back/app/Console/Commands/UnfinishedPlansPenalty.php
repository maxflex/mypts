<?php

namespace App\Console\Commands;

use App\Models\Entry;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UnfinishedPlansPenalty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unfinished-plans-penalty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Penalty unfinished plans for today';

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
        Plan::query()
            ->whereDate('date', Carbon::yesterday())
            ->unfinished()
            ->get()
            ->each(fn ($plan) => Entry::create([
                'pts' => $plan->pts * -1,
                'comment' => $plan->comment,
                'user_id' => $plan->user_id,
                'created_at' => Carbon::yesterday()->format('Y-m-d 23:59:59')
            ]));
    }
}
