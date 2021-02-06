<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForgotPasswordOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forgot_password_otps', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->index('uuid');
            $table->string('user_uuid')->nullable();
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
        Schema::dropIfExists('forgot_password_otps');
    }
}
