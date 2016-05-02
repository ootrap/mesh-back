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
            $table->string('name', 50)->nullable();
            $table->string('nickname', 50)->nullable();
            $table->boolean('authorized')->nullable();
            $table->string('token')->nullable();
            $table->string('refresh_token');
            $table->string('avatar_url')->nullable();
            $table->string('qr_url')->nullable();
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
