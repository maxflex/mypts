<?php

use App\Enums\RecordType;
use App\Models\Record;
use App\Models\User;
use Carbon\Carbon;
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
            foreach (RecordType::getValues() as $type) {
                factory(Record::class)->create([
                    'type' => $type,
                    'user_id' => $user->id,
                    'pts' => $currentPts
                ]);
            }
        }
    }
}
