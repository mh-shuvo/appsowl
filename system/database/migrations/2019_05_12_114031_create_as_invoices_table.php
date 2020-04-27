<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_invoices', function (Blueprint $table) {
            $table->increments('invoice_id');
            $table->string('user_id');
            $table->string('agent_id');
            $table->string('subscribe_id');
            $table->dateTime('subscribe_start_date');
            $table->dateTime('subscribe_end_date');
            $table->decimal('invoice_amount',20,2);
            $table->decimal('invoice_discount',20,2);
            $table->decimal('invoice_charge',20,2);
            $table->string('invoice_transaction_id');
            $table->string('subscribe_month');
            $table->dateTime('created_at');
            $table->dateTime('invoice_paid_date');
            $table->enum('invoice_status',['paid','due','cancel']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('as_invoices');
    }
}
