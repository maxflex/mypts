<?php

namespace Tests\Feature;

use App\Models\Plan;
use Tests\FeatureTestCase;

class PlansTest extends FeatureTestCase
{
    public function testCompletePlan()
    {
        $plan = factory(Plan::class)->create([
            'user_id' => $this->user->id,
        ]);
        $this->assertEquals(config('pts.base'), $this->user->currentPts);
        $this->assertCount(1, $this->user->plans);

        $plan->complete();

        $this->assertEquals(config('pts.base') + $plan->pts, $this->user->currentPts);
    }
}
