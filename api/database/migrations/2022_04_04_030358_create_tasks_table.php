<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->unsignedBigInteger('computing_resource_id');
            $table->unsignedBigInteger('project_id');
            $table->string('name');
            $table->string('status')->default("DRAFT");
            $table->string('files');
            $table->longText('script');
            $table->longText('jobs');
            $table->longText('computational_model_resource');
            $table->longText('type');
            $table->longText('extra');
            $table->longText('numerical_model');
            $table->longText('converter_service');
            $table->uuid('calculation_case_id')->nullable();
            $table->longText('meta_values')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
