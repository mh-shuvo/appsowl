<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsWithdrawalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_withdrawal', function (Blueprint $table) {
            $table->bigIncrements('withdrawal_id');
            $table->string('user_id')->nullable();
            $table->enum('withdrawal_type', ['system','user'])->nullable();
            $table->decimal('withdrawal_amount',20,2)->nullable();
            $table->decimal('withdrawal_charge',20,2)->nullable();
            $table->decimal('withdrawal_total_amount',20,2)->nullable();
            $table->mediumText('withdrawal_note')->nullable();
            $table->string('withdrawal_method')->nullable();
            $table->string('withdrawal_transaction_id')->nullable();
            $table->enum('withdrawal_status', ['paid','due','hold','cancel','requested'])->nullable();
            $table->string('withdrawal_approve_by')->nullable();
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
        Schema::dropIfExists('as_withdrawal');
    }
}
