<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsDesktopSoftVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_desktop_soft_version', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('software_id');
            $table->string('software_name',100);
            $table->string('software_ver',100);
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
        Schema::dropIfExists('as_desktop_soft_version');
    }
}
