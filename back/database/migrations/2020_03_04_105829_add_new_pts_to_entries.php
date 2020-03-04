<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewPtsToEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->integer('new_pts');
        });
        // DB::table('entries')->orderBy('id', 'asc')->get()->each(fn ($entry) => DB::table('entries')->whereId($entry->id)->update(['new_pts' => $entry->pts + 1200 + DB::table('entries')->where('id', '<', $entry->id)->sum('pts')]));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entries', function (Blueprint $table) {
            //
        });
    }
}
