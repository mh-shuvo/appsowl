<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {

            $table->bigIncrements('log_id');
            $table->string('subject');
            $table->mediumText('url');
            $table->string('method');
            $table->ipAddress('ip');
            $table->mediumText('agent')->nullable();
            $table->integer('user_id')->nullable();
            $table->mediumText('note')->nullable();
            $table->string('document')->nullable();
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
        Schema::dropIfExists('activity_log');
    }
}
