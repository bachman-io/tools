<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 36)->unique();
            $table->string('username');
            $table->integer('level');
            $table->integer('max_level_granted_by_subscription');
            $table->text('profile_url');
            $table->timestamp('started_at');
            $table->boolean('subscribed');
            $table->dateTime('current_vacation_started_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
