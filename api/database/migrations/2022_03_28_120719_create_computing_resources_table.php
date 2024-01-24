<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComputingResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computing_resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('port');
            $table->string('host');
            $table->string('login');
            $table->string('password');
            $table->string('public_key');
            $table->string('private_key');
            $table->string('template_type');

            $table->rememberToken();
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
        Schema::dropIfExists('computing_resources');
    }
}
