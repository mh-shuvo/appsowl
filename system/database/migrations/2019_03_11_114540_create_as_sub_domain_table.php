<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsSubDomainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_sub_domain', function (Blueprint $table) {
            $table->increments('domain_id');
            $table->integer('user_id');
            $table->string('sub_domain',100)->unique();
            $table->string('root_domain',100);
            $table->string('basedir',100);
            $table->enum('domain_status', ['active','inactive']);
            $table->string('db_host',100);
            $table->string('db_username',100);
            $table->string('db_password',100);
            $table->string('db_name',100);
            $table->string('db_status',100);
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
        Schema::dropIfExists('as_sub_domain');
    }
}
