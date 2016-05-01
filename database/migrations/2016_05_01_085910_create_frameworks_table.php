<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrameworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frameworks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('appid', 30);
            $table->string('secretKey', 64);
            $table->string('token', 64);
            $table->string('aeskey');
            $table->string('oauthScopes', 30);
            $table->string('redirectUrl', 64);
            $table->string('oauthCallback', 64);
            $table->string('refreshToken');

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
        Schema::drop('frameworks');
    }
}
