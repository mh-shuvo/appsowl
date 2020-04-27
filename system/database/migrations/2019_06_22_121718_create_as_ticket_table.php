<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_ticket', function (Blueprint $table) {
            $table->bigIncrements('ticket_no');
            $table->enum('priority',['high','medium','normal'])->nullable();
            $table->string('user_id')->nullable();
            $table->string('added_by')->nullable();
            $table->enum('ticket_type',['ticket','chat'])->nullable();
            $table->mediumText('ticket_title')->nullable();
            $table->longText('ticket_details')->nullable();
            $table->string('ticket_document')->nullable();
            $table->enum('reply_by',['user','admin'])->nullable();
            $table->longText('ticket_message')->nullable();
            $table->string('ticket_for')->nullable();
            $table->string('asign_to')->nullable();
            $table->enum('status',['pending','processing','complete'])->nullable();
            $table->enum('is_delete',['true','false'])->nullable();
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
        Schema::dropIfExists('as_ticket');
    }
}
