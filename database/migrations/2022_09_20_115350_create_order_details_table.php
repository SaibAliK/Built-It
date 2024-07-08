<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('store_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->enum('status', ['accepted', 'pending', 'completed', 'cancelled'])->default('pending');
            $table->string('order_no')->nullable();
            $table->longText('image')->nullable();
            $table->longText('invoice')->nullable();
            $table->longText('cancel_reason')->nullable();
            $table->double('subtotal', 20, 2)->nullable();
            $table->double('vat_percentage', 20, 2)->nullable();
            $table->double('vat', 20, 2)->nullable();
            $table->double('shipping', 20, 2)->nullable();
            $table->double('discount', 20, 2)->nullable();
            $table->double('total', 20, 2)->nullable();
            $table->integer('updated_at')->nullable();
            $table->integer('created_at')->nullable();
            $table->integer('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
