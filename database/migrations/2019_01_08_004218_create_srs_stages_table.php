<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSrsStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('srs_stages', function (Blueprint $table) {
            $table->integer('srs_stage')->unique();
            $table->text('srs_stage_name');
            $table->integer('interval');
            $table->integer('accelerated_interval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('srs_stages');
    }
}
