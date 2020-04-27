<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsPluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_plugins', function (Blueprint $table) {
            $table->increments('plugins_id');
            $table->enum('plugins_type', ['public', 'private', 'custom'])->nullable();
            $table->string('plugins_image')->nullable();
            $table->enum('plugins_billing', ['free', 'monthly', 'onetime', 'yearly'])->nullable();
            $table->enum('plugins_billing_type', ['prepaid', 'postpaid'])->nullable();
            $table->string('plugins_billing_value')->nullable();
            $table->string('plugins_name')->nullable();
            $table->string('plugins_unique_name')->unique();
            $table->string('plugins_page')->nullable();
            $table->string('plugins_page_file')->nullable();
            $table->enum('plugins_page_required', ['true', 'false'])->nullable();
            $table->mediumtext('plugins_details')->nullable();
            $table->string('plugins_software_id')->nullable();
            $table->decimal('plugins_price', 10, 2)->nullable();
            $table->dateTime('plugins_published_date')->nullable();
            $table->dateTime('plugins_update_date')->nullable();
            $table->dateTime('plugins_unpublished_date')->nullable();
            $table->string('plugins_version')->nullable();
            $table->enum('plugins_update_type', ['auto', 'manual'])->nullable();
            $table->enum('plugins_status', ['active', 'inactive', 'disable'])->nullable();
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
        Schema::dropIfExists('as_plugins');
    }
}
