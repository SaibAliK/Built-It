<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('full_name')->nullable();
            $table->enum('status', ['accepted', 'pending', 'completed', 'cancelled'])->default('pending');
            $table->longText('address')->nullable();
            $table->longText('transaction_details')->nullable();
            $table->longText('cancel_reason')->nullable();
            $table->longText('invoice')->nullable();
            $table->longText('image')->nullable();
            $table->longText('charges')->nullable();
            $table->longText('paypal_response')->nullable();
            $table->string('order_number')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payer_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('payer_status')->nullable();
            $table->string('currency')->nullable();
            $table->double('subtotal', 20, 2)->nullable();
            $table->double('shipping', 20, 2)->nullable();
            $table->double('discount', 20, 2)->nullable();
            $table->double('vat', 20, 2)->nullable();
            $table->double('vat_percentage', 20, 2)->nullable();
            $table->double('discount_percentage', 20, 2)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
