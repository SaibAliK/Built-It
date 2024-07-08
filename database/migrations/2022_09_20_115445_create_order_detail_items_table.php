<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_detail_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('store_id')->nullable();
            $table->longText('name')->nullable();
            $table->longText('image')->nullable();
            $table->longText('extras')->nullable();
            $table->enum('status', ['accepted', 'pending', 'completed', 'cancelled'])->default('pending');
            $table->double('price', 20, 2)->nullable();
            $table->double('subtotal', 20, 2)->nullable();
            $table->double('shipping', 20, 2)->nullable();
            $table->double('discount', 20, 2)->nullable();
            $table->double('total', 20, 2)->nullable();
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('order_detail_items');
    }
}
