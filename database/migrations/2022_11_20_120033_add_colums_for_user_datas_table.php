<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsForUserDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('user_datas', function (Blueprint $table) {
            $table->float('payment', 8, 2);
            $table->float('bonus', 8, 2);
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('user_datas', function (Blueprint $table) {
            $table->dropColumn('payment');
            $table->dropColumn('bonus');
        });
    }
}
