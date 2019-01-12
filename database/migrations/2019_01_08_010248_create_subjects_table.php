<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('object', 10);
            $table->dateTime('data_updated_at');
            $table->integer('level');
            $table->text('characters')->nullable();
            $table->text('character_image')->nullable();
            $table->text('meanings');
            $table->text('on_yomi')->nullable();
            $table->text('kun_yomi')->nullable();
            $table->text('nanori')->nullable();
            $table->text('kana')->nullable();
            $table->text('parts_of_speech')->nullable();
            $table->text('document_url');
            $table->text('amalgamation_subject_ids')->nullable();
            $table->text('component_subject_ids')->nullable();

            $table->index('object');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
