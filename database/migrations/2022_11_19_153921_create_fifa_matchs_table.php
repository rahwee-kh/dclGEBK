<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFifaMatchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fifa_matchs', function (Blueprint $table) {
            $table->id();
            $table->string('match_id');
            $table->string('sport_key');
            $table->string('sport_title');
            $table->string('commence_time');
            $table->string('completed');
            $table->string('home_team');
            $table->string('away_team');
            $table->string('home_score');
            $table->string('away_score');
            $table->string('result');
            $table->string('last_update');
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
        Schema::dropIfExists('fifa_matchs');
    }
}
