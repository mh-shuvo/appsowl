<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('email',40)->unique();
            $table->string('username',200)->unique();
            $table->string('password',200)->nullable();
            $table->string('confirmation_key',40)->nullable();
            $table->string('sms_verify_key',6)->nullable();
            $table->enum('sms_confirmed', ['Y', 'N'])->nullable();
            $table->enum('confirmed', ['Y', 'N'])->nullable();
            $table->string('password_reset_key',200)->nullable();
            $table->enum('password_reset_confirmed', ['Y', 'N'])->nullable();
            $table->datetime('password_reset_timestamp')->nullable();
            $table->timestamp('register_date')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
            $table->integer('user_role')->nullable();
            $table->datetime('last_login')->nullable();
            $table->enum('banned', ['Y', 'N'])->default('N');
            $table->enum('status_now', ['followup', 'followed'])->nullable();
            $table->string('permission', 30)->nullable();
            $table->tinyInteger('email_varified')->default(0)->nullable();
            $table->timestamp('email_varified_at')->nullable();
            $table->string('email_varification_token')->nullable();
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
        Schema::dropIfExists('as_users');
    }
}
