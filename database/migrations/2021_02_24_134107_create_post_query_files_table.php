<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostQueryFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_query_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->index('uuid');
            $table->uuid('post_query_uuid');
            $table->foreign('post_query_uuid')
                ->references('uuid')
                ->on('post_queries')
                ->cascadeOnDelete();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_mime_type')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_path')->nullable();
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
        Schema::dropIfExists('post_query_files');
    }
}
