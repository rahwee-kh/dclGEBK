<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetSoccerRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_soccer_records', function (Blueprint $table) {
            $table->id();
            $table->string('eth_address');
            $table->string('match_id');
            $table->string('selection');
            $table->string('bet_type');
            $table->string('chips');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bet_soccer_records');
    }
}
