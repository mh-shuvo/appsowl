<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsAgentCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_agent_commission', function (Blueprint $table) {
            $table->increments('commission_id');
            $table->string('agent_id');
            $table->float('previous_rate',10,2)->nullable();
            $table->float('new_rate',10,2)->nullable();
            $table->mediumtext('commission_note')->nullable();
            $table->string('document')->nullable();
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
        Schema::dropIfExists('as_agent_commission');
    }
}
