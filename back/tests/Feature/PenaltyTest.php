<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\FeatureTestCase;

class PenaltyTest extends FeatureTestCase
{
    public function testUnfinishedPlansPenalty()
    {
        $plan = factory(Plan::class)->create([
            'user_id' => $this->user->id,
            'is_finished' => false,
            'date' => Carbon::today(),
        ]);

        Carbon::setTestNow(Carbon::tomorrow()->endOfDay());

        Artisan::call('penalty');

        $this->assertEquals(
            config('pts.base') + ($plan->pts * (int) config('pts.unfinished-plans-coeff')),
            $this->user->currentPts
        );
    }

    public function testNoActivityPenalty()
    {
        factory(Entry::class)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::today(),
        ]);

        $currentPts = $this->user->currentPts;

        foreach (range(0, 5) as $day) {
            Carbon::setTestNow(Carbon::tomorrow()->endOfDay());
            Artisan::call('penalty');
            $penalty = $day * config('pts.no-activity-penalty');
            $currentPts += $penalty;
            $this->assertEquals(
                $currentPts,
                $this->user->currentPts
            );
        }
    }


    public function noPenaltyDuringVacationTest()
    {
        $this->user->on_vacation = true;

        factory(Entry::class)->create([
            'user_id' => $this->user->id,
            'created_at' => Carbon::today(),
        ]);

        $currentPts = $this->user->currentPts;

        foreach (range(0, 5) as $day) {
            Carbon::setTestNow(Carbon::tomorrow()->endOfDay());
            Artisan::call('penalty');
            $this->assertEquals(
                $currentPts,
                $this->user->currentPts
            );
        }
    }
}
