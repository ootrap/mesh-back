<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wxmps', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('authorized');
            $table->bigInteger('user_id')->unsigned();
            $table->string('refresh_token');
            $table->string('avatar_url');
            $table->string('qr_url');
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
        Schema::drop('wxmps');
    }
}
