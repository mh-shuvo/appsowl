<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_notification', function (Blueprint $table) {
            $table->bigIncrements('notification_id');
            $table->string('title')->nullable();
            $table->mediumText('message')->nullable();
            $table->mediumText('link')->nullable();
            $table->enum('status',['active','deactive'])->nullable();
            $table->integer('user_id')->nullable();
            $table->enum('notification_type',['user','admin'])->nullable();
            $table->enum('read_status',['read','unread'])->nullable();
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
        Schema::dropIfExists('as_notification');
    }
}
