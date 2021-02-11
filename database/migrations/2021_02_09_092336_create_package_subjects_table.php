<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_subjects', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->index('uuid');
            $table->uuid('package_uuid');
            $table->foreign('package_uuid')
                ->references('uuid')
                ->on('packages')
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
        Schema::dropIfExists('package_subjects');
    }
}
