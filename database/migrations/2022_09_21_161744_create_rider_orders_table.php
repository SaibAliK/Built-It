<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_detail_id');
            $table->unsignedBigInteger('rider_id');
            $table->unsignedBigInteger('delivery_company_id');
            $table->enum('status', ['pending', 'confirmed', 'shipped', 'completed', 'delivered', 'cancelled', 'rejected'])->default('pending');
            $table->integer('product_price')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('total_price')->default(0);

            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
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
        Schema::dropIfExists('rider_orders');
    }
}
