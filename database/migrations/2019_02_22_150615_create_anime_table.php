<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anime', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title_english')->nullable();
            $table->text('title_romaji')->nullable();
            $table->text('title_native');
            $table->text('description');
            $table->text('status');
            $table->integer('progress');
            $table->integer('episodes')->nullable();
            $table->text('format');
            $table->integer('duration')->nullable();
            $table->decimal('average_score', 8, 1);
            $table->integer('my_score');
            $table->text('studios');
            $table->text('site_url');
            $table->text('cover_thumbnail');
            $table->text('cover_image');
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
        Schema::dropIfExists('anime');
    }
}
