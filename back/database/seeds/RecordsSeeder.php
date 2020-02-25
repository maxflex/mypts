<?php

use App\Models\Record;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user) {
            $currentPts = $user->currentPts;
            factory(Record::class)->create([
                'user_id' => $user->id,
                'pts' => $currentPts
            ]);
        }
    }
}
