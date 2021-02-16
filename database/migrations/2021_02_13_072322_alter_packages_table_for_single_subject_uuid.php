<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPackagesTableForSingleSubjectUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function($table) {
            $table->uuid('stream_uuid')->after('user_uuid');
            $table->foreign('stream_uuid')
                ->references('uuid')
                ->on('streams')
                ->onDelete('cascade');
            $table->uuid('subject_uuid')->after('stream_uuid');
            $table->foreign('subject_uuid')
                ->references('uuid')
                ->on('subjects')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
