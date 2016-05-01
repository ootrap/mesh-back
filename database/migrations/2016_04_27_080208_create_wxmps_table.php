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
            $table->string('appId', 50)->unique();
            $table->string('name', 50);
            $table->string('nickname', 50);
            $table->boolean('authorized');
            $table->string('token');
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
