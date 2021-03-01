<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasedPackagesPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_packages_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->index('uuid');
            $table->uuid('purchase_package_uuid');
            $table->foreign('purchase_package_uuid')
                ->references('uuid')
                ->on('purchased_packages')
                ->cascadeOnDelete();
            $table->string('payment_id')->nullable();
            $table->integer('price')->nullable();
            $table->timestamp('date')->useCurrent();
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
        Schema::dropIfExists('purchased_packages_payments');
    }
}
