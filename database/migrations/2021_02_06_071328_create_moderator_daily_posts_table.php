<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModeratorDailyPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moderator_daily_posts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->index('uuid');
            $table->uuid('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')
                ->on('users');
            $table->uuid('moderator_subject_uuid');
            $table->foreign('moderator_subject_uuid')
                ->references('uuid')
                ->on('moderator_subjects');
            $table->string('title')->nullable();;
            $table->string('slug')->nullable();;
            $table->string('description')->nullable();;
            $table->string('image')->nullable();
            $table->boolean('status')->default(1)->nullable();
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
        Schema::dropIfExists('moderator_daily_posts');
    }
}
