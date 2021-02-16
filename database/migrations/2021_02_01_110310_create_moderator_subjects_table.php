<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModeratorSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moderator_subjects', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->index('uuid');
            $table->uuid('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');
            $table->uuid('stream_uuid');
            $table->foreign('stream_uuid')
                ->references('uuid')
                ->on('streams')
                ->onDelete('cascade');
            $table->uuid('subject_uuid');
            $table->foreign('subject_uuid')
                ->references('uuid')
                ->on('subjects')
                ->onDelete('cascade');
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
        Schema::dropIfExists('moderator_subjects');
    }
}
