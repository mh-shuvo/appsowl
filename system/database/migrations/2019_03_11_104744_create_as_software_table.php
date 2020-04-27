<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsSoftwareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_software', function (Blueprint $table) {
            $table->increments('software_id');
            $table->string('software_unique_name',100)->nullable();
            $table->enum('software_billing', ['free', 'monthly', 'yearly', 'onetime'])->nullable();
            $table->enum('software_billing_type', ['prepaid', 'postpaid'])->nullable();
            $table->string('software_billing_value',100)->nullable();
            $table->string('software_title',100)->nullable();
            $table->string('software_banner',130)->nullable();
            $table->mediumtext('software_short_des')->nullable();
            $table->longtext('software_long_des')->nullable();
            $table->float('software_price', 10, 2)->nullable();
            $table->mediumtext('software_tagline')->nullable();
            $table->enum('software_status', ['active', 'inactive'])->nullable();
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
        Schema::dropIfExists('as_software');
    }
}
