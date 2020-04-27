<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsSubscribePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_subscribe_payment', function (Blueprint $table) {
            $table->increments('subscribe_payment_id');
            $table->integer('software_id');
            $table->integer('software_variation_id');
            $table->integer('user_id');
            $table->integer('agent_id');
            $table->dateTime('subscribe_start_date');
            $table->dateTime('subscribe_end_date');
            $table->integer('subscribe_id');
            $table->decimal('subscribe_payment_amount',20,2);
            $table->string('subscribe_payment_transaction_id',40);
            $table->string('subscribe_month',20);
            $table->string('payment_token',200);
            $table->decimal('payment_amount',20,2);
            $table->string('payment_currency',20);
            $table->decimal('payment_charge',20,2);
            $table->decimal('payment_discount',20,2);
            $table->decimal('payment_total_amount',20,2);
            $table->string('payment_request_ip',20);
            $table->mediumtext('payment_txn_msg');
            $table->string('payment_txn_status',20);
            $table->mediumText('payment_txn_details');
            $table->mediumText('payment_card_details');
            $table->string('payment_card',100);
            $table->string('card_code',100);
            $table->string('payment_method',100);
            $table->datetime('payment_request_time');
            $table->datetime('payment_time');
            $table->string('payment_ref_id',50);
            $table->enum('subscribe_payment_status', ['paid', 'due', 'cancel'])->default('cancel');
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
        Schema::dropIfExists('as_subscribe_payment');
    }
}
