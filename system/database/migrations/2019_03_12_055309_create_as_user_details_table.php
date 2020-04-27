<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_user_details', function (Blueprint $table) {
            $table->increments('id_user_details');
            $table->integer('user_id')->nullable();
            $table->integer('domain_id')->nullable();
            $table->integer('agent_id')->nullable();
            $table->integer('added_by')->nullable();
            $table->integer('store_id')->nullable();
            $table->string('first_name',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->string('dob',20)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('address',200)->nullable();
            $table->string('permanent_address',200)->nullable();
            $table->string('country',20)->nullable();
            $table->string('country_code',20)->nullable();
            $table->mediumtext('zone')->nullable();
            $table->mediumtext('area')->nullable();
            $table->string('profile_image',100)->nullable();
            $table->string('nid_card',50)->nullable();
            $table->string('attach',100)->nullable();
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
        Schema::dropIfExists('as_user_details');
    }
}
