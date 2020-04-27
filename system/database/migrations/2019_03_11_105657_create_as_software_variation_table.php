<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsSoftwareVariationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_software_variation', function (Blueprint $table) {
            $table->increments('software_variation_id');
            $table->string('software_id',100)->nullable();
            $table->string('software_variation_name',100)->nullable();
            $table->string('software_variation_icon',100)->nullable();
            $table->decimal('software_variation_price',10,2)->nullable();
            $table->decimal('software_subscribe_fee',10,2)->nullable();
            $table->enum('software_subscribe_in_advance', ['true', 'false'])->nullable();
            $table->string('software_variation_sort', 10)->nullable();
            $table->string('required_plugins')->nullable();
            $table->string('required_services')->nullable();
            $table->enum('software_variation_status', ['active', 'inactive'])->nullable();
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
        Schema::dropIfExists('as_software_variation');
    }
}
