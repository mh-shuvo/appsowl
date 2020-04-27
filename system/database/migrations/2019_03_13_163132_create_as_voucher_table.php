<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_voucher', function (Blueprint $table) {
            $table->increments('voucher_id');
            $table->string('voucher_title',30)->nullable();
            $table->string('voucher_code')->nullable()->unique();
            $table->decimal('voucher_amount',20,2)->nullable();
            $table->decimal('voucher_price',20,2)->nullable();
            $table->string('generated_by')->nullable();
            $table->enum('voucher_available', ['available', 'not_available','reject'])->default('available');
            $table->string('user_id',30)->nullable();
            $table->mediumText('voucher_note')->nullable();
            $table->string('voucher_document')->nullable();
            $table->enum('voucher_status', ['active', 'inactive', 'cancel'])->default('active');
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
        Schema::dropIfExists('as_voucher');
    }
}
