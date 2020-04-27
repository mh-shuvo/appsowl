<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_country', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',3);
            $table->string('name',150);
            $table->integer('phonecode');
            $table->string('currency_name',20);
            $table->string('currency_symbol',20);
            $table->string('currency_code',20);
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
        Schema::dropIfExists('as_country');
    }
}
