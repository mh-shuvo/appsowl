<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_payment_method', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_method_name');
            $table->string('payment_method_value');
            $table->string('payment_method_number');
            $table->double('minimum_amount',10,2);
            $table->enum('status',['active','inactive']);
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
        Schema::dropIfExists('as_payment_method');
    }
}
