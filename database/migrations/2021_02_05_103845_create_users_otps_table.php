<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_otps', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->index('uuid');
            $table->uuid('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');
            $table->integer('otp')->nullable();
            $table->boolean('status')->default(0)->nullable();
            $table->integer('attempt')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('users_otps');
    }
}
