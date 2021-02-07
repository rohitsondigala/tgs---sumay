<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->index('uuid');
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->bigInteger('country')->unsigned()->index();
            $table->foreign('country')
                ->references('id')
                ->on('countries');
            $table->bigInteger('state')->unsigned()->index();
            $table->foreign('state')
                ->references('id')
                ->on('states');
            $table->bigInteger('city')->unsigned()->index();
            $table->foreign('city')
                ->references('id')
                ->on('cities');
            $table->string('image',500)->nullable();
            $table->rememberToken()->nullable();
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
        Schema::dropIfExists('users')->nullable();
    }
}
