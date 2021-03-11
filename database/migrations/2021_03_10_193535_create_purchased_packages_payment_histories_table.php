<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasedPackagesPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_packages_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->index('uuid');
            $table->uuid('user_uuid');
            $table->foreign('user_uuid')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');
            $table->uuid('package_uuid')->nullable();
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
            $table->uuid('p_p_uuid')->nullable();
            $table->foreign('p_p_uuid')
                ->references('uuid')
                ->on('purchased_packages')
                ->onDelete('cascade');
            $table->date('purchase_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('duration_in_days')->nullable();
            $table->integer('price')->nullable();
            $table->string('payment_id')->nullable();
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
        Schema::dropIfExists('purchased_packages_payment_histories');
    }
}
