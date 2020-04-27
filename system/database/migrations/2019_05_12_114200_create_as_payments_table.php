<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_payment', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->string('payment_transaction_id')->nullable();
            $table->enum('payment_type',['office','gateway','voucher','system'])->nullable();
            $table->date('payment_load_date')->nullable();
            $table->string('voucher_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('office_payment_by')->nullable();
            $table->string('payment_token')->nullable();
            $table->decimal('payment_amount',20,2)->nullable();
            $table->string('payment_currency')->nullable();
            $table->decimal('payment_charge',20,2)->nullable();
            $table->decimal('payment_discount',20,2)->nullable();
            $table->decimal('payment_total_amount',20,2)->nullable();
            $table->string('payment_request_ip')->nullable();
            $table->mediumText('payment_txn_msg')->nullable();
            $table->string('payment_txn_status')->nullable();
            $table->mediumText('payment_txn_details')->nullable();
            $table->mediumText('payment_card_details')->nullable();
            $table->string('payment_card')->nullable();
            $table->string('card_code')->nullable();
            $table->string('payment_method')->nullable();
            $table->dateTime('payment_request_time')->nullable();
            $table->dateTime('payment_time')->nullable();
            $table->string('payment_ref_id')->nullable();
            $table->mediumText('payment_note')->nullable();
            $table->enum('payment_status',['paid','due','cancel','hold'])->nullable();
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
        Schema::dropIfExists('as_payment');
    }
}
