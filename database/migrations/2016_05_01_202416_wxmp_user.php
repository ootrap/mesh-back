<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WxmpUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wxmp_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wxmp_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->index(['wxmp_id', 'user_id'])->unique();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wxmp_user');
    }
}
