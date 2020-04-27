<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsPosRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_pos_requirements', function (Blueprint $table) {
            $table->increments('pos_requirement_id');
            $table->string('company_name',30);
            $table->string('company_website',150);
            $table->string('company_email',50);
            $table->string('company_phone',20);
            $table->mediumtext('company_address');
            $table->string('company_city',20);
            $table->string('company_country',20);
            $table->string('company_postcode',20);
            $table->string('vat_no',40);
            $table->string('vat_unit',20);
            $table->string('currency',20);
            $table->enum('vat_type', ['global','single']);
            $table->string('vat_percentage',20);
            $table->string('user_id',20);
            $table->string('software_variation_id',20);
            $table->string('software_variation_name',100);
            $table->enum('status', ['paid', 'active']);
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
        Schema::dropIfExists('as_pos_requirements');
    }
}
