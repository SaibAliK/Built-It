<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->longText('name');
            $table->longText('description');
            $table->double('price', 18, 2);
            $table->double('discount', 5, 2)->default(0.00);
            $table->unsignedInteger('discount_expiration')->nullable();
            $table->double('price_after_discount', 18, 2);
            $table->boolean('is_offer')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->integer('quantity')->nullable()->default(0);
            $table->integer('reserve')->nullable()->default(0);
            $table->integer('sold')->nullable()->default(0);
            $table->double('shipping', 18, 2)->default(0);
            $table->longText('size_guides');
            $table->unsignedInteger('expiry_date')->nullable();
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_featured_expired')->default(1);
            $table->unsignedBigInteger('image_dependent')->nullable();
            $table->unsignedBigInteger('price_dependent')->nullable();
            $table->unsignedBigInteger('quantity_dependent')->nullable();
            $table->double('average_rating', 5, 2);
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
            $table->integer('deleted_at')->nullable();
            $table->integer('owner_id')->nullable()->default(0);
            $table->foreign('supplier_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
