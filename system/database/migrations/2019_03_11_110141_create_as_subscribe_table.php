<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsSubscribeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_subscribe', function (Blueprint $table) {
            $table->increments('subscribe_id');
            $table->integer('user_id')->nullable();
            $table->integer('agent_id')->nullable();
            $table->integer('software_id')->nullable();
            $table->integer('software_variation_id')->nullable();
            $table->string('plugins_id')->nullable();
            $table->string('service_id')->nullable();
            $table->enum('subscribe_type', ['software', 'service', 'plugins'])->nullable();
            $table->dateTime('subscribe_date')->nullable();
            $table->dateTime('subscribe_activation_date')->nullable();
            $table->decimal('subscribe_amount',20,2)->nullable();
            $table->enum('subscribe_payment_terms', ['free', 'monthly', 'yearly', 'onetime'])->nullable();
            $table->string('subscribe_payment_terms_value')->nullable();
            $table->string('subscribe_reminder')->nullable();
            $table->enum('subscribe_status', ['active', 'inactive', 'cancel', 'expire','return'])->nullable();
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
        Schema::dropIfExists('as_subscribe');
    }
}
