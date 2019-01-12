<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_statistics', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->dateTime('data_updated_at');
            $table->integer('subject_id');
            $table->integer('meaning_correct');
            $table->integer('meaning_incorrect');
            $table->integer('meaning_max_streak');
            $table->integer('meaning_current_streak');
            $table->integer('reading_correct');
            $table->integer('reading_incorrect');
            $table->integer('reading_max_streak');
            $table->integer('reading_current_streak');
            $table->integer('percentage_correct');

            $table->index('percentage_correct');

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
        Schema::dropIfExists('review_statistics');
    }
}
