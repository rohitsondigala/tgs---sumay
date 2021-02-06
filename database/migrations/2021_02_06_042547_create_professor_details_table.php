<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professor_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->index('uuid');
            $table->uuid('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')
                ->on('users');
            $table->string('university_name')->nullable();
            $table->string('college_name')->nullable();
            $table->string('education_qualification')->nullable();
            $table->string('research_of_expertise')->nullable();
            $table->string('achievements')->nullable();
            $table->string('preferred_language')->nullable();
            $table->string('other_information')->nullable();
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
        Schema::dropIfExists('professor_details');
    }
}
