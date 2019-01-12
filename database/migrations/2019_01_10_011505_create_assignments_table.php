<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->dateTime('data_updated_at');
            $table->integer('subject_id');
            $table->integer('srs_stage');
            $table->dateTime('unlocked_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('passed_at')->nullable();
            $table->dateTime('burned_at')->nullable();
            $table->dateTime('available_at')->nullable();
            $table->dateTime('resurrected_at')->nullable();
            $table->boolean('passed');
            $table->boolean('resurrected');

            $table->index('srs_stage');

            $table->foreign('subject_id')->references('id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
}
