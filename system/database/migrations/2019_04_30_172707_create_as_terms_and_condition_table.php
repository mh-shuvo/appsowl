<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsTermsAndConditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_terms_and_condition', function (Blueprint $table) {
            $table->bigIncrements('t_c_id');
            $table->string('added_by')->nullable();
            $table->enum('type', ['t&c', 'privacy']);
            $table->string('document')->nullable();
            $table->longText('body_text')->charset('utf8');
            $table->enum('status', ['active', 'deactive']);
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
        Schema::dropIfExists('as_terms_and_condition');
    }
}
