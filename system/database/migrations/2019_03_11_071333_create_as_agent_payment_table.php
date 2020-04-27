<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsAgentPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_agent_payment', function (Blueprint $table) {
            $table->increments('agent_payment_id');
            $table->string('user_id')->nullable();
            $table->string('agent_id')->nullable();
            $table->enum('payment_type',['receive','withdraw'])->nullable();
            $table->integer('subscribe_id')->nullable();
            $table->string('subscribe_payment_id')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('payment_amount',20,2)->nullable();
            $table->decimal('payment_charge',20,2)->nullable();
            $table->mediumText('payment_details')->nullable();
            $table->datetime('pay_date')->nullable();
            $table->string('pay_document')->nullable();
            $table->string('pay_note')->nullable();
            $table->enum('payment_status',['paid','due','cancel'])->nullable();
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
        Schema::dropIfExists('as_agent_payment');
    }
}
