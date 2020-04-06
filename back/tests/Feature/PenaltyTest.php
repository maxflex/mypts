<?php

namespace Tests\Feature;

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
        ]);

        dump(
            $plan->pts,
            ($plan->pts * (int) config('pts.unfinished-plans-coeff')),
            config('pts.base') + ($plan->pts * (int) config('pts.unfinished-plans-coeff')),
        );

        Carbon::setTestNow(Carbon::tomorrow()->endOfDay());

        Artisan::call('penalty');

        $this->assertEquals(
            config('pts.base') + ($plan->pts * (int) config('pts.unfinished-plans-coeff')),
            $this->user->currentPts
        );
    }

    // public function testNoActivityPenalty()
    // {
    //     $plan = factory(Plan::class)->create([
    //         'user_id' => $this->user->id,
    //     ]);
    // }
}
