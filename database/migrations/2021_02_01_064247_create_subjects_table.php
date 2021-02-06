<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->uuid('stream_uuid');
            $table->foreign('stream_uuid')
                ->references('uuid')
                ->on('streams');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('is_standard')->nullable();
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
        Schema::dropIfExists('subjects');
    }
}
