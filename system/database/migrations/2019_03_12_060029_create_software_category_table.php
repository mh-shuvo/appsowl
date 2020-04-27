<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoftwareCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('software_category', function (Blueprint $table) {
            $table->increments('pos_category_id');
            $table->integer('software_id');
            $table->string('pos_category_key',200)->unique();
            $table->string('pos_category_icon',200);
            $table->string('pos_category_name',100);
            $table->text('pos_category_tagline');
            $table->text('pos_category_about');
            $table->text('pos_category_details');
            $table->string('pos_category_module',200);
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
        Schema::dropIfExists('software_category');
    }
}
