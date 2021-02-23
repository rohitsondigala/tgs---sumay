<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->index('uuid');
            $table->uuid('from_user_uuid');
            $table->foreign('from_user_uuid')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');
            $table->uuid('to_user_uuid');
            $table->foreign('to_user_uuid')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');
            $table->uuid('subject_uuid');
            $table->foreign('subject_uuid')
                ->references('uuid')
                ->on('subjects')
                ->onDelete('cascade');
            $table->float('rating')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('reviews');
    }
}
